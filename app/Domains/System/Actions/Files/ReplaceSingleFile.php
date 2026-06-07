<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class ReplaceSingleFile
{
    public function __construct(protected UploadAndAttachFile $uploadAction) {}

    public function execute(
        Model $targetModel,
        string $relationName,
        UploadedFile $newFile,
        string $disk = 'private',
        string $directory = 'uploads'): File
    {
        // 1. Fetch the old file specifically for THIS relation (e.g., just the avatar)
        if ($oldFile = $targetModel->{$relationName}()->first()) {
            $oldFile->delete(); // The physical file is destroyed via the model's booted() event
        }

        // 2. Delegate to the base upload action, passing the relation name down the chain
        return $this->uploadAction->execute(
            targetModel: $targetModel,
            relationName: $relationName,
            uploadedFile: $newFile,
            disk: $disk,
            directory: $directory
        );
    }
}
