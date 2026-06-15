<?php

namespace App\Domains\System\Actions\Backup;

use App\Domains\System\Models\Backup;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SystemBackup
{
    /**
     * @throws Exception
     */
    public function execute(): Backup
    {
        $artisanResult = Artisan::call('backup:run', []);
        if ($artisanResult !== 0) {
            throw new Exception(__('domains/system.messages.backup.backup_error'));
        }

        $disk = config('backup.backup.destination.disks')[0] ?? 'local';
        $backupName = config('backup.backup.name') ?? 'Laravel';

        $files = Storage::disk($disk)->allFiles($backupName);
        if (empty($files)) {
            throw new Exception(__('domains/system.messages.backup.verification_error'));
        }

        $latestFile = collect($files)->last();
        $fileName = $backupName . ' ' . basename($latestFile);
        $sizeInBytes = Storage::disk($disk)->size($latestFile);

        return Backup::create([
            'file_name' => $fileName,
            'disk' => $disk,
            'path' => $latestFile,
            'size' => $sizeInBytes,
            'type' => 'full',
        ]);
    }
}
