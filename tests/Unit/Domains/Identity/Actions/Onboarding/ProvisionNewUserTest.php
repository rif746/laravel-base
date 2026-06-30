<?php

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Events\Onboarding\UserWasProvisioned;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it can provision new user', function () {
    Event::fake();

    $mockUpdateUserRole = $this->mock(UpdateUserRole::class, function (MockInterface $mock) {
        $mock->shouldReceive('execute')
            ->once()
            ->with(Mockery::type(User::class), ['admin']);
    });

    $dto = new ProvisionUserDTO(
        name: 'Admin User',
        email: 'admin@example.com',
        password: 'password',
        role: 'admin'
    );

    $action = new ProvisionNewUser($mockUpdateUserRole);
    $user = $action->execute($dto);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('Admin User')
        ->and($user->email)->toBe('admin@example.com');

    Event::assertDispatched(UserWasProvisioned::class);
});
