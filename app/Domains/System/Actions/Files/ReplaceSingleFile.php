<?php

namespace App\Domains\System\Actions\Files;

use App\Domains\System\DTOs\FileDTO;
use App\Domains\System\Models\File;
use Illuminate\Http\UploadedFile;

class ReplaceSingleFile
{
    public function __construct(protected UploadAndAttachFile $uploadAction) {}

    public function execute(
        UploadedFile $newFile,
        FileDTO $dto
    ): File {
        // 1. Fetch the old file specifically for THIS relation (e.g., just the avatar)
        $oldFile = File::where('fileable_type', $dto->modelType)
            ->where('fileable_id', $dto->modelId)
            ->first();
        $oldFile?->delete(); // The physical file is destroyed via the model's booted() event

        // 2. Delegate to the base upload action, passing the relation name down the chain
        return $this->uploadAction->execute(
            uploadedFile: $newFile,
            dto: $dto,
        );
    }
}
