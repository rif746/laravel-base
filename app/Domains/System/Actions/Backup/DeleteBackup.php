<?php

namespace App\Domains\System\Actions\Backup;

use App\Domains\System\Models\Backup;
use Illuminate\Support\Facades\Storage;

class DeleteBackup
{
    public function execute(Backup $backup): void
    {
        Storage::disk($backup->disk)->delete($backup->path);

        $backup->delete();
    }
}
