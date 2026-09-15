<?php

namespace App\Domains\Identity\Actions\Authentication;

use App\Domains\Identity\DTOs\Authentication\AuthenticateUserDTO;
use App\Domains\Identity\Events\Authentication\UserLoggedIn;
use App\Domains\Identity\Models\User;
use DomainException;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class AuthenticateUser
{
    /**
     * Authenticate user credentials, check active status, and dispatch domain event.
     *
     * @param AuthenticateUserDTO $dto
     * @return User
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function execute(AuthenticateUserDTO $dto): User
    {
        // 1. Attempt credentials verification via Auth Guard
        if (! Auth::attempt(['email' => $dto->email, 'password' => $dto->password], $dto->remember)) {
            throw new InvalidArgumentException(trans('auth.failed'));
        }

        /** @var User $user */
        $user = Auth::user();

        // 2. Enforce business rule: Check if account status is active
        if (isset($user->status) && method_exists($user->status, 'isActive') && ! $user->status->isActive()) {
            Auth::logout();

            throw new DomainException(trans('auth.inactive'));
        }

        // 3. Dispatch domain event with pure primitives (no HTTP Request passed)
        UserLoggedIn::dispatch($user, $dto->ipAddress, $dto->userAgent);

        return $user;
    }
}
