<?php

namespace App\Domains\Identity\Events\Registration;

use App\Domains\Identity\DTOs\Registration\RegisterUserDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The DTO is carried alongside the User so listeners have full context
     * of what input triggered the registration (e.g. for welcome emails).
     */
    public function __construct(
        public readonly User $user,
        public readonly RegisterUserDTO $dto,
    ) {}
}
