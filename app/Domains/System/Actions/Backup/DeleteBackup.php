<?php

namespace App\Domains\System\Actions\Backup;

use App\Domains\System\Models\Backup;

class DeleteBackup
{
    public function execute(Backup $backup): void
    {
        remove_file($backup->path);

        $backup->delete();
    }
}
