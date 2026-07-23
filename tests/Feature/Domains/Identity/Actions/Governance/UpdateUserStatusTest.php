<?php

namespace Tests\Feature\Domains\Identity\Actions\Governance;

use App\Domains\Identity\Actions\Governance\UpdateUserStatus;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_user_status(): void
    {
        $user = User::factory()->create(['status' => UserStatus::ACTIVE]);
        $action = new UpdateUserStatus;

        $action->execute($user, UserStatus::INACTIVE);

        $this->assertEquals(UserStatus::INACTIVE, $user->fresh()->status);
    }

    public function test_it_throws_exception_when_status_is_same(): void
    {
        $user = User::factory()->create(['status' => UserStatus::ACTIVE]);
        $action = new UpdateUserStatus;

        $this->expectException(Exception::class);
        $action->execute($user, UserStatus::ACTIVE);
    }
}
