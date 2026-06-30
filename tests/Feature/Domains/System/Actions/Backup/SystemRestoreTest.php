<?php

namespace Tests\Feature\Domains\System\Actions\Backup;

use App\Domains\System\Actions\Backup\SyncBackupCatalog;
use App\Domains\System\Actions\Backup\SystemRestore;
use App\Domains\System\Models\Backup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use ZipArchive;

class SystemRestoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('test-disk');
        Process::fake();
    }

    public function test_it_restores_the_system(): void
    {
        $backup = Backup::create([
            'file_name' => 'backup.zip',
            'disk' => 'test-disk',
            'path' => 'backups/backup.zip',
            'size' => 1024,
            'type' => 'full',
        ]);

        // Create a fake zip file
        $zipPath = storage_path('temp-backup.zip');
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('db-dumps/mysql-'.config('database.connections.mysql.database').'.sql', 'SQL CONTENT');
        $zip->addEmptyDir('storage');
        $zip->close();

        // Put it in fake storage
        Storage::disk('test-disk')->put('backups/backup.zip', file_get_contents($zipPath));

        // Ensure path exists for SystemRestore to find it (SystemRestore uses physical path)
        // Since we are using Storage::fake, the path() might not point to where we put the file
        // However, the code uses Storage::disk($backup->disk)->path($backup->path)
        // Storage::fake() uses a local driver by default, so it should be fine if we mock the disk path or if it's pointing to a temp storage
        // Actually, Storage::fake() usually puts files in a temporary directory.
        // We need to make sure SystemRestore can find the file.
        // Wait, SystemRestore does `file_exists($backupPath)`.
        // In testing, I might need to make sure the file exists at the path returned by `path()`.

        // Let's mock SyncBackupCatalog
        $syncBackupCatalog = Mockery::mock(SyncBackupCatalog::class);
        $syncBackupCatalog->shouldReceive('execute')->once();

        // The action
        $action = new SystemRestore($syncBackupCatalog);

        // We need to bypass the file_exists check if Storage::fake() doesn't put it where expected
        // Actually, we can just create the file where path() says it will be.
        // But Storage::fake('test-disk') usually sets the root to a temp dir.
        // Let's check the path.
        $actualPath = Storage::disk('test-disk')->path('backups/backup.zip');
        if (!file_exists(dirname($actualPath))) {
            mkdir(dirname($actualPath), 0755, true);
        }
        copy($zipPath, $actualPath);

        $action->execute($backup);

        Process::assertRan(fn ($process) => in_array('mysql', (array) $process->command));

        // Cleanup
        unlink($zipPath);
        File::deleteDirectory(storage_path('app/restore-temp'));
    }
}
