<?php

namespace App\Domains\Identity\Listeners\Authentication;

use App\Domains\Identity\Actions\Security\RecordUserActivity;
use App\Domains\Identity\DTOs\Security\RecordUserActivityDTO;
use App\Domains\Identity\Events\Authentication\UserLoggedIn;

class RecordSignInActivity
{
    public function __construct(
        protected RecordUserActivity $recordUserActivity,
    ) {}

    public function handle(UserLoggedIn $event): void
    {
        $this->recordUserActivity->execute(new RecordUserActivityDTO(
            userId: $event->user->id,
            event: 'login',
            ipAddress: $event->ipAddress,
            userAgent: $event->userAgent,
        ));
    }
}
