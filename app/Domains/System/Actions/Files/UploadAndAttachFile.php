<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class UploadAndAttachFile
{
    public function execute(Model $targetModel, string $relationName, UploadedFile $uploadedFile, string $disk = 'private', string $directory = 'uploads'): File
    {
        $metadata = [
            'disk' => $disk,
            'relation_name' => $relationName,
            'name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
        ];

        $metadata['path'] = $uploadedFile->store($directory, $disk);

        // We dynamically call the relation method on the model (e.g., $targetModel->avatar())
        // This ensures Laravel hooks up the polymorphic type and ID automatically.
        return $targetModel->{$relationName}()->create($metadata);
    }
}
