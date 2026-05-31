<?php

namespace App\Actions\System\Backup;

use App\DTOs\System\DeleteBackupDTO;
use App\Models\System\Backup;

class DeleteBackup
{
    public function execute($id): void
    {
        $backup = Backup::findOrFail($id);

        remove_file($backup->path);

        $backup->delete();
    }
}
