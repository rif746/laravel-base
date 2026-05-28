<?php

namespace App\Actions\System\Backup;

use App\Models\System\Backup;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use RuntimeException;

class SystemRestore
{
    public function __construct(protected SyncBackupCatalog $syncBackupCatalog)
    {
    }

    /**
     * @throws Exception
     */
    public function execute(Backup $backup): void
    {
        $backupPath = Storage::disk($backup->disk)->path($backup->path);
        $restorePath = storage_path('app/restore-temp');
        if (!file_exists($backupPath)) {
            throw new Exception('Backup file not found');
        }

        $zip = new ZipArchive();
        if ($zip->open($backupPath) === TRUE) {
            $zip->extractTo($restorePath);
            $zip->close();

            $this->restoreDatabase($restorePath);
            $this->restoreFiles($restorePath);

            File::deleteDirectory($restorePath);

            $this->syncBackupCatalog->execute();
        }
    }

    private function restoreDatabase(string $tempPath): void
    {
        $dbDump = $tempPath . '/db-dumps/mysql-' . config('database.connections.mysql.database') . '.sql';
        if (file_exists($dbDump)) {
            $restoreSql = Process::run([
                'mysql',
                '-u', config('database.connections.mysql.username'),
                '-p' . config('database.connections.mysql.password'),
                '-h' . config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                '-e', "source {$dbDump}"
            ]);

            if ($restoreSql->failed()) {
                throw new RuntimeException("Database restore failed: " . $restoreSql->errorOutput());
            }
        }
    }

    private function restoreFiles(string $tempPath): void
    {
        $uploadsPath = $tempPath . '/storage';

        if (file_exists($uploadsPath)) {
            File::copyDirectory($uploadsPath, storage_path());
        }
    }
}
