<?php

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it creates profile if it does not exist', function () {
    $user = User::factory()->create();
    $dto = new UpdateProfileDTO(
        userId: $user->id,
        gender: GenderOption::MALE,
        dateOfBirth: '1990-01-01',
        phoneNumber: '1234567890'
    );

    $action = new UpdateProfile;
    $action->execute($dto);

    expect(Profile::where('user_id', $user->id)->exists())->toBeTrue()
        ->and(Profile::where('user_id', $user->id)->first()->gender)->toBe(GenderOption::MALE);
});

test('it updates profile if it exists', function () {
    $user = User::factory()->create();
    Profile::create([
        'user_id' => $user->id,
        'gender' => GenderOption::MALE,
        'date_of_birth' => '1990-01-01',
        'phone_number' => '1234567890',
    ]);

    $dto = new UpdateProfileDTO(
        userId: $user->id,
        gender: GenderOption::FEMALE,
        dateOfBirth: '1995-01-01',
        phoneNumber: '0987654321'
    );

    $action = new UpdateProfile;
    $action->execute($dto);

    $profile = Profile::where('user_id', $user->id)->first();
    expect($profile->gender)->toBe(GenderOption::FEMALE)
        ->and($profile->date_of_birth->format('Y-m-d'))->toBe('1995-01-01')
        ->and($profile->phone_number)->toBe('0987654321');
});
