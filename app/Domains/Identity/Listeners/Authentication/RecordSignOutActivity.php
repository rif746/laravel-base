<?php

namespace App\Domains\Identity\Listeners\Authentication;

use App\Domains\Identity\Actions\Security\RecordUserActivity;
use App\Domains\Identity\DTOs\Security\RecordUserActivityDTO;
use App\Domains\Identity\Events\Authentication\UserLoggedOut;

class RecordSignOutActivity
{
    public function __construct(
        protected RecordUserActivity $recordUserActivity,
    ) {}

    public function handle(UserLoggedOut $event): void
    {
        $this->recordUserActivity->execute(new RecordUserActivityDTO(
            userId: $event->user->id,
            event: 'logout',
            ipAddress: $event->ipAddress,
            userAgent: $event->userAgent,
        ));
    }
}
