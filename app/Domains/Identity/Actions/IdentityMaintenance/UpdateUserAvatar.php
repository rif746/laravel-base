<?php

namespace App\Domains\Identity\Actions\IdentityMaintenance;

use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Files\ReplaceSingleFile;
use App\Domains\System\DTOs\FileDTO;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateUserAvatar
{
    public function __construct(protected ReplaceSingleFile $replaceSingleFile) {}

    public function execute(User $user, UploadedFile|TemporaryUploadedFile $file): void
    {
        $this->replaceSingleFile->execute(
            newFile: $file,
            dto: new FileDTO(
                modelType: $user->getMorphClass(),
                modelId: $user->id,
                relationName: 'avatar',
                disk: 'local',
                directory: 'avatars',
                options: [],
                uploaderId: $user->id,
            )
        );
    }
}
