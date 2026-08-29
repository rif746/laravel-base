<?php

namespace App\Domains\Identity\Actions\Security;

use App\Domains\Identity\DTOs\Security\RecordUserActivityDTO;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserActivity;
use Illuminate\Support\Facades\DB;

class RecordUserActivity
{
    /**
     * @throws \Throwable
     */
    public function execute(RecordUserActivityDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            UserActivity::create([
                'user_id' => $dto->userId,
                'event' => $dto->event,
                'ip_address' => $dto->ipAddress,
                'user_agent' => $dto->userAgent,
                'payload' => $dto->payload,
                'event_time' => now()
            ]);

            if ($dto->event === 'login') {
                User::where('id', $dto->userId)->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $dto->ipAddress,
                ]);
            }
        });
    }
}
