<?php

use App\Domains\Identity\Actions\AccessControl\UpdateUserRole;
use App\Domains\Identity\Actions\Onboarding\RegisterSelfServiceUser;
use App\Domains\Identity\DTOs\Onboarding\RegisterSelfServiceUserDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Events\Onboarding\UserWasRegistered;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('it can register self service user', function () {
    Event::fake();

    $mockUpdateUserRole = $this->mock(UpdateUserRole::class, function (MockInterface $mock) {
        $mock->shouldReceive('execute')
            ->once()
            ->with(Mockery::type(User::class), [RoleType::USER]);
    });

    $dto = new RegisterSelfServiceUserDTO(
        name: 'John Doe',
        email: 'john@example.com',
        password: 'password',
    );

    $action = new RegisterSelfServiceUser($mockUpdateUserRole);
    $user = $action->execute($dto);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');

    Event::assertDispatched(Registered::class);
    Event::assertDispatched(UserWasRegistered::class);
});
