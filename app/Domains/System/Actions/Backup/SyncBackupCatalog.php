<?php

namespace App\Domains\System\Actions\Backup;

use App\Domains\System\Models\Backup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncBackupCatalog
{
    public function execute(): void
    {
        // Spatie configurations
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $backupDirectory = config('backup.backup.name');

        // Fetch physical files from the storage disk
        $files = $disk->files($backupDirectory);
        $physicalFilePaths = [];

        foreach ($files as $file) {
            if (Str::endsWith($file, '.zip')) {
                // Ensure we just store the relative filename if that's your convention
                $filename = basename($file);
                $filename = $backupDirectory . ' ' . basename($filename);
                $physicalFilePaths[] = $file;

                // Restore the 'disappeared' record using the file's raw metadata
                Backup::updateOrCreate(
                    ['file_name' => $filename],
                    [
                        'path' => $file,
                        'size' => $disk->size($file), // Handled by your ByteUsage cast
                        'type' => 'full', // Or parse filename if you differentiate types
                        'disk' => $diskName,
                    ]
                );
            }
        }

        // Clean up ghost records (in DB, but physical file was deleted)
        Backup::whereNotIn('path', $physicalFilePaths)->delete();
    }
}
