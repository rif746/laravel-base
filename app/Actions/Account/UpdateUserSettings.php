<?php

namespace App\Actions\Account;

use App\DTOs\Account\UpdateUserSettingsDTO;
use App\Models\Identity\User;

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
