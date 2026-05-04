<?php

namespace App\Actions\Account;

use App\Enums\UserSettingKey;
use App\Models\Account\UserSetting;
use App\Models\Identity\User;

class SaveUserSettingAction
{
    public function execute(User $user, UserSettingKey $key, mixed $value): UserSetting
    {
        return UserSetting::updateOrCreate(
            ['user_id' => $user->id, 'key' => $key->value],
            ['value' => $value]
        );
    }
}
