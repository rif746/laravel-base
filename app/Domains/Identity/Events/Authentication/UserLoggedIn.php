<?php

namespace App\Domains\Identity\Events\Authentication;

use App\Domains\Identity\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * Accepts only strictly typed primitives / models — no request() or session().
     * The Gateway (Volt component) is responsible for extracting IP and User-Agent
     * from the HTTP request before dispatching this event.
     */
    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $userAgent,
    ) {}
}
