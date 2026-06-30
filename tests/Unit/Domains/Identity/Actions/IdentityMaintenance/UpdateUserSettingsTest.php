<?php

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserSettings;
use App\Domains\Identity\Enums\UserSettingKey;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it can update user settings', function () {
    $user = User::factory()->create([
        'settings' => [
            UserSettingKey::LANGUAGE->value => 'id',
            UserSettingKey::TIMEZONE->value => 'Asia/Jakarta',
        ],
    ]);

    $newSettings = [
        UserSettingKey::LANGUAGE->value => 'en',
    ];

    $action = new UpdateUserSettings();
    $action->execute($user, $newSettings);

    $updatedUser = $user->fresh();
    expect($updatedUser->settings[UserSettingKey::LANGUAGE->value])->toBe('en')
        ->and($updatedUser->settings[UserSettingKey::TIMEZONE->value])->toBe('Asia/Jakarta');
});

test('it ignores invalid settings keys', function () {
    $user = User::factory()->create(['settings' => []]);

    $newSettings = [
        'invalid_key' => 'some_value',
        UserSettingKey::NOTIFICATION->value => 1,
    ];

    $action = new UpdateUserSettings();
    $action->execute($user, $newSettings);

    $updatedUser = $user->fresh();
    expect($updatedUser->settings)->toHaveKey(UserSettingKey::NOTIFICATION->value)
        ->and($updatedUser->settings)->not->toHaveKey('invalid_key');
});
