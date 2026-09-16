<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\DTOs\FileDTO;
use App\Domains\System\Models\File;
use Illuminate\Http\UploadedFile;

class UploadAndAttachFile
{
    /**
     * Create a new Action instance with composed media converter dependency.
     *
     * @param ConvertFileMedia $mediaConverter
     */
    public function __construct(
        protected ConvertFileMedia $mediaConverter
    ) {}

    /**
     * Execute file upload, media conversion, and polymorphic attachment.
     *
     * @param UploadedFile $uploadedFile
     * @param FileDTO $dto
     * @return File
     */
    public function execute(
        UploadedFile $uploadedFile,
        FileDTO $dto
    ): File {
        // Delegate media conversion processing to dedicated ConvertFileMedia action
        [$storedPath, $fileSize, $mimeType] = $this->mediaConverter->execute(
            file: $uploadedFile,
            directory: $dto->directory,
            disk: $dto->disk
        );

        $metadata = [
            'fileable_type' => $dto->modelType,
            'fileable_id' => $dto->modelId,
            'relation_name' => $dto->relationName,
            'name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $mimeType,
            'size' => $fileSize,
            'disk' => $dto->disk,
            'path' => $storedPath,
            'options' => $dto->options,
            'uploader_id' => $dto->uploaderId,
        ];

        // Create polymorphic database entry attached to target model
        return File::create($metadata);
    }
}
