<?php

namespace App\Domains\Identity\Actions\Authentication;

use App\Domains\Identity\DTOs\Authentication\AuthenticateUserDTO;
use App\Domains\Identity\DTOs\Authentication\IssueApiTokenDTO;
use App\Domains\Identity\Models\User;
use App\Http\Resources\Identity\UserResource;
use DomainException;
use InvalidArgumentException;

class IssueApiToken
{
    /**
     * Inject base AuthenticateUser action via constructor composition.
     */
    public function __construct(
        protected AuthenticateUser $authenticateUser,
    ) {}

    /**
     * Authenticate a user and issue a new Sanctum plain text token.
     *
     * @param IssueApiTokenDTO $dto
     * @return array{user: User, token: string}
     *
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public function execute(IssueApiTokenDTO $dto): array
    {
        // 1. Delegate base authentication logic to AuthenticateUser Action
        $user = $this->authenticateUser->execute(
            new AuthenticateUserDTO(
                email: $dto->email,
                password: $dto->password,
                remember: false,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
            )
        );

        // 2. Issue Sanctum access token for API client
        $token = $user->createToken($dto->deviceName)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
