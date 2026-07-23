<?php

use App\Domains\Identity\Actions\Passwords\UpdatePassword;
use App\Domains\Identity\DTOs\Passwords\UpdatePasswordDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('it can update user password', function () {
    $user = User::factory()->create([
        'password' => 'old_password',
    ]);

    $dto = new UpdatePasswordDTO(
        new_password: 'new_password',
    );

    $action = new UpdatePassword;
    $action->execute($user, $dto);

    expect(Hash::check('new_password', $user->fresh()->password))->toBeTrue()
        ->and(Hash::check('old_password', $user->fresh()->password))->toBeFalse();
});
