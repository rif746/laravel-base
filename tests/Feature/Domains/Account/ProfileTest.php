<?php

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\Actions\Profile\UpdateUserAvatar;
use App\Domains\Account\Actions\Profile\UpdateUserSettings;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Enums\UserSettingKey;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('UpdateProfile action creates or updates user profile', function () {
    $user = User::factory()->create();
    $profile = new Profile;

    $dto = new UpdateProfileDTO(
        userId: $user->id,
        gender: GenderOption::MALE->value,
        date_of_birth: '1995-03-20',
        phone_number: '081234567890'
    );

    $action = app(UpdateProfile::class);
    $action->execute($profile, $dto);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'gender' => GenderOption::MALE->value,
        'date_of_birth' => '1995-03-20 00:00:00',
        'phone_number' => '081234567890',
    ]);

    // Test updating same profile
    $dtoUpdate = new UpdateProfileDTO(
        userId: $user->id,
        gender: GenderOption::FEMALE->value,
        date_of_birth: '1995-03-20 00:00:00',
        phone_number: '089999999999'
    );

    $action->execute($profile, $dtoUpdate);

    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'gender' => GenderOption::FEMALE->value,
        'phone_number' => '089999999999',
    ]);
});

test('UpdateUserSettings action updates user setting keys and ignores others', function () {
    $user = User::factory()->create(['settings' => null]);

    $action = app(UpdateUserSettings::class);
    $action->execute($user, [
        UserSettingKey::NOTIFICATION->value => 1,
        UserSettingKey::LANGUAGE->value => 'id',
        UserSettingKey::TIMEZONE->value => 'Asia/Jakarta',
        'invalid_key' => 'hack',
    ]);

    $user->refresh();
    expect($user->settings->toArray())->toEqual([
        UserSettingKey::NOTIFICATION->value => 1,
        UserSettingKey::LANGUAGE->value => 'id',
        UserSettingKey::TIMEZONE->value => 'Asia/Jakarta',
    ]);
});

test('UpdateUserAvatar action uploads file and attaches to user', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('avatar.jpg');

    $action = app(UpdateUserAvatar::class);
    $action->execute($user, $file);

    $user->refresh();
    $avatar = $user->avatar()->first();

    expect($avatar)->not->toBeNull();
    expect($avatar->name)->toBe('avatar.jpg');
    expect($avatar->disk)->toBe('local');
    expect($avatar->relation_name)->toBe('avatar');

    Storage::disk('local')->assertExists($avatar->path);

    // Test replacement deletes old file
    $oldPath = $avatar->path;
    $newFile = UploadedFile::fake()->image('new_avatar.jpg');

    $action->execute($user, $newFile);

    $user->refresh();
    $newAvatar = $user->avatar()->first();

    expect($newAvatar->name)->toBe('new_avatar.jpg');
    Storage::disk('local')->assertMissing($oldPath);
    Storage::disk('local')->assertExists($newAvatar->path);
});
