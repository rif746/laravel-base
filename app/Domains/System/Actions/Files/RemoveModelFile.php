<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\Models\File;

class RemoveModelFile
{
    public function execute(string $model, string|int $id): void
    {
        $files = File::where('fileable_id', $id)
            ->where('fileable_type', $model)
            ->get();

        foreach ($files as $file) {
            $file->delete();
        }
    }
}
