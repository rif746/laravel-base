<?php

namespace App\Domains\Account\Actions\Profile;

use App\Domains\Identity\Models\User;
use App\Domains\System\Actions\Files\ReplaceSingleFile;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UpdateUserAvatar
{
    public function __construct(protected ReplaceSingleFile $replaceSingleFile) {}

    public function execute(User $user, UploadedFile|TemporaryUploadedFile $file): void
    {
        $this->replaceSingleFile->execute(
            targetModel: $user,
            relationName: 'avatar',
            newFile: $file,
            disk: 'local',
            directory: 'avatars',
        );
    }
}
