<?php

namespace App\Domains\Account\Listeners;

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Identity\Events\Integration\UserImportWasProcessed;

class SyncImportedUserProfile
{
    public function __construct(
        protected UpdateProfile $updateProfile
    ) {}

    public function handle(UserImportWasProcessed $event): void
    {
        $this->updateProfile->execute(new UpdateProfileDTO(
            userId: $event->userId,
            gender: GenderOption::fromLabel($event->gender),
            dateOfBirth: $event->dateOfBirth,
            phoneNumber: $event->phoneNumber,
        ));
    }
}
