<?php

namespace App\Domains\System\Actions\Backup;

use App\Domains\System\DTOs\DeleteBackupDTO;
use App\Domains\System\Models\Backup;

class DeleteBackup
{
    public function execute($id): void
    {
        $backup = Backup::findOrFail($id);

        remove_file($backup->path);

        $backup->delete();
    }
}
