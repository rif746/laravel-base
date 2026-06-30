<?php

use App\Domains\Identity\Actions\Governance\PurgeUser;
use App\Domains\Identity\Actions\Governance\RemoveUser;
use App\Domains\Identity\Actions\Governance\SuspendUser;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it suspends active user', function () {
    $user = User::factory()->create(['status' => UserStatus::ACTIVE]);

    $suspendUser = Mockery::mock(SuspendUser::class);
    $purgeUser = Mockery::mock(PurgeUser::class);

    $suspendUser->shouldReceive('execute')->once()->with($user);
    $purgeUser->shouldNotReceive('execute');

    $action = new RemoveUser($purgeUser, $suspendUser);
    $action->execute($user);

    expect(true)->toBeTrue();
});

test('it purges inactive user', function () {
    $user = User::factory()->create(['status' => UserStatus::INACTIVE]);

    $suspendUser = Mockery::mock(SuspendUser::class);
    $purgeUser = Mockery::mock(PurgeUser::class);

    $suspendUser->shouldNotReceive('execute');
    $purgeUser->shouldReceive('execute')->once()->with($user);

    $action = new RemoveUser($purgeUser, $suspendUser);
    $action->execute($user);

    expect(true)->toBeTrue();
});
