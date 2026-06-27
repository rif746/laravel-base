<?php

namespace App\Domains\Identity\Actions\IdentityMaintenance;

use App\Domains\Identity\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;

class UpdateUserSettings
{
    public function execute(User $user, array $newSettings): void
    {
        $currentSettings = $user->settings ?? [];

        foreach (UserSettingKey::cases() as $key) {
            if (array_key_exists($key->value, $newSettings)) {
                $currentSettings[$key->value] = $newSettings[$key->value];
            }
        }

        $user->update(['settings' => $currentSettings]);
    }
}
