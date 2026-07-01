<?php

use App\Domains\Identity\Actions\Governance\SuspendUser;
use App\Domains\Identity\Actions\Governance\UpdateUserStatus;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Events\Governance\UserWasSuspended;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('it can suspend a user', function () {
    Event::fake();

    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);

    // Mock session
    DB::table('sessions')->insert([
        'id' => 'session_id',
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'test',
        'payload' => 'test',
        'last_activity' => time()
    ]);

    $action = new SuspendUser(app(UpdateUserStatus::class));

    $action->execute($user);

    expect($user->fresh()->status)->toBe(UserStatus::INACTIVE);
    $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
    Event::assertDispatched(UserWasSuspended::class);
});

test('it cannot suspend an admin', function () {
    Role::create(['name' => RoleType::ADMIN->value, 'guard_name' => 'web']);
    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);
    $user->assignRole(RoleType::ADMIN->value);

    $action = new SuspendUser(app(UpdateUserStatus::class));

    $this->expectException(Exception::class);
    $this->expectExceptionMessage(__('domains/identity/messages.exceptions.user_cannot_be_suspended'));

    $action->execute($user);
});

test('it throws exception if user already suspended', function () {
    $user = User::factory()->create(['status' => UserStatus::INACTIVE]);
    $action = new SuspendUser(app(UpdateUserStatus::class));

    $this->expectException(Exception::class);
    $this->expectExceptionMessage(__('domains/identity/messages.exceptions.user_already_suspended'));

    $action->execute($user);
});
