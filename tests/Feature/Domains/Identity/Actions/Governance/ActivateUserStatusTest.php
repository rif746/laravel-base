<?php

use App\Domains\Identity\Actions\Governance\ActivateUserStatus;
use App\Domains\Identity\Actions\Governance\UpdateUserStatus;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Events\Governance\UserWasActivated;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('it can activate a user', function () {
    Event::fake();

    $user = User::factory()->create(['status' => UserStatus::INACTIVE]);
    $action = new ActivateUserStatus(app(UpdateUserStatus::class));

    $action->execute($user);

    expect($user->fresh()->status)->toBe(UserStatus::ACTIVE);
    Event::assertDispatched(UserWasActivated::class);
});

test('it throws exception if user already active', function () {
    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);
    $action = new ActivateUserStatus(app(UpdateUserStatus::class));

    $this->expectException(Exception::class);
    $this->expectExceptionMessage(__('domains/identity/messages.exceptions.user_already_active'));

    $action->execute($user);
});
