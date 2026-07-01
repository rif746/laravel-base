<?php

use App\Domains\Identity\Actions\Governance\PurgeUser;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Events\Governance\UserWasPurged;
use App\Domains\Identity\Models\Role;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('it can purge a user', function () {
    Event::fake();

    $user = User::factory()->create();
    $action = new PurgeUser();

    $action->execute($user);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    Event::assertDispatched(UserWasPurged::class);
});

test('it cannot purge an admin', function () {
    Role::create(['name' => RoleType::ADMIN->value, 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole(RoleType::ADMIN->value);

    $action = new PurgeUser();

    $this->expectException(Exception::class);
    $this->expectExceptionMessage(__('domains/identity/messages.exceptions.user_cannot_be_purged'));

    $action->execute($user);
});
