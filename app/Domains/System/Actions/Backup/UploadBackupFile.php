<?php

namespace App\Domains\System\Actions\Backup;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadBackupFile
{
    public function __construct(protected SyncBackupCatalog $syncBackupCatalog)
    {
    }

    public function execute(UploadedFile|TemporaryUploadedFile $file): void
    {
        // Spatie configurations
        $diskName = config('backup.backup.destination.disks')[0];
        $backupDirectory = config('backup.backup.name');

        $file->storeAs($backupDirectory, $file->getClientOriginalName(), ['disk' => $diskName]);
        $this->syncBackupCatalog->execute();
    }
}
