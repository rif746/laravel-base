<?php

namespace App\Domains\Identity\Actions\Registration;

use App\Domains\Identity\DTOs\Registration\RegisterUserDTO;
use App\Domains\Identity\Events\Registration\UserRegistered;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterUser
{
    /**
     * Create a new user and dispatch all registration events.
     *
     * Fires two events:
     *  - Illuminate\Auth\Events\Registered — triggers Laravel's built-in
     *    email verification notification listener automatically.
     *  - App\Domains\Identity\Events\UserRegistered — the domain event for
     *    any application-specific listeners (onboarding, role assignment, etc.).
     *
     * Note: Auth::login() is intentionally absent. Logging the user into the
     * session is a framework/HTTP concern that belongs to the Auth Gateway.
     */
    public function execute(RegisterUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        // Framework event: triggers SendEmailVerificationNotification listener.
        event(new Registered($user));

        // Domain event: for application-level listeners.
        UserRegistered::dispatch($user, $dto);

        return $user;
    }
}
