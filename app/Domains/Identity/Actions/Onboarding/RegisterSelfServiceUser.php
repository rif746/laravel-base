<?php

namespace App\Domains\Identity\Actions\Onboarding;

use App\Domains\Identity\DTOs\Onboarding\RegisterSelfServiceUserDTO;
use App\Domains\Identity\Events\Onboarding\UserWasRegistered;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterSelfServiceUser
{
    /**
     * Create a new user via self-service registration and dispatch all events.
     *
     * Fires two events:
     *  - Illuminate\Auth\Events\Registered — triggers Laravel's built-in
     *    email verification notification listener automatically.
     *  - App\Domains\Identity\Events\Onboarding\UserWasRegistered — the domain
     *    event for any application-specific listeners (welcome emails, etc.).
     *
     * Note: Auth::login() is intentionally absent. Logging the user into the
     * session is a framework/HTTP concern that belongs to the Auth Gateway.
     */
    public function execute(RegisterSelfServiceUserDTO $dto): User
    {
        $user = User::create([
            'name'     => $dto->name,
            'email'    => $dto->email,
            'password' => $dto->password,
        ]);

        // Framework event: triggers SendEmailVerificationNotification listener.
        event(new Registered($user));

        // Domain event: for application-level listeners.
        UserWasRegistered::dispatch($user, $dto);

        return $user;
    }
}
