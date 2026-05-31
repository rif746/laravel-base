<?php

namespace App\Domains\Account\Actions;

use App\Domains\Account\DTOs\UpdateUserSettingsDTO;
use App\Domains\Identity\Models\User;

class UpdateUserSettings
{
    public function execute(UpdateUserSettingsDTO $dto): void
    {
        /** @var User $user */
        $user = auth('web')->user();

        $user->settings = collect($dto->settings);
        $user->save();
    }
}
