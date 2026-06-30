<?php

namespace Tests\Unit\Domains\Account\Listeners;

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Listeners\SyncImportedUserProfile;
use App\Domains\Identity\Events\Integration\UserImportWasProcessed;
use Mockery;
use Tests\TestCase;

class SyncImportedUserProfileTest extends TestCase
{
    public function test_it_updates_profile_on_user_import(): void
    {
        $updateProfile = Mockery::mock(UpdateProfile::class);
        $listener = new SyncImportedUserProfile($updateProfile);

        $event = new UserImportWasProcessed(
            userId: 'user-123',
            email: 'test@example.com',
            gender: GenderOption::MALE->label(),
            dateOfBirth: '1990-01-01',
            phoneNumber: '1234567890'
        );

        $updateProfile->shouldReceive('execute')
            ->once()
            ->with(Mockery::on(function (UpdateProfileDTO $dto) use ($event) {
                return $dto->userId === $event->userId &&
                       $dto->gender === GenderOption::MALE &&
                       $dto->dateOfBirth === $event->dateOfBirth &&
                       $dto->phoneNumber === $event->phoneNumber;
            }));

        $listener->handle($event);
    }
}
