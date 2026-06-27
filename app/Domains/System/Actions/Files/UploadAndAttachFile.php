<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\DTOs\FileDTO;
use App\Domains\System\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class UploadAndAttachFile
{
    public function execute(
        UploadedFile $uploadedFile,
        FileDTO $dto
    ): File {
        $metadata = [
            'fileable_type' => $dto->modelType,
            'fileable_id' => $dto->modelId,
            'relation_name' => $dto->relationName,
            'name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
            'disk' => $dto->disk,
            'options' => $dto->options,
            'uploader_id' => $dto->uploaderId,
        ];

        $metadata['path'] = $uploadedFile->store($dto->directory, $dto->disk);

        // We dynamically call the relation method on the model (e.g., $targetModel->avatar())
        // This ensures Laravel hooks up the polymorphic type and ID automatically.
        return File::create($metadata);
    }
}
