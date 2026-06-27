<?php

namespace App\Domains\Identity\Integration\Mappers;

use App\Domains\Identity\Actions\IdentityMaintenance\UpdateUserIdentity;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
use App\Domains\Identity\DTOs\IdentityMaintenance\UpdateUserIdentityDTO;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Events\Integration\UserImportWasProcessed;
use App\Domains\Identity\Models\User;
use App\Domains\System\Support\Integration\DataPayloadMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class UserDataMapper implements DataPayloadMapper
{
    public function __construct(
        protected ProvisionNewUser $provisionNewUser,
        protected UpdateUserIdentity $updateUser,
    ) {}

    /**
     * Define the unique column used to locate records across entry streams.
     */
    public function getLookupKey(): string
    {
        return 'email';
    }

    /**
     * Normalize raw incoming data structures into an internal domain-safe layout array
     */
    public function transform(array $rawData): array
    {
        return [
            'name' => trim((string) $rawData['name']),
            'email' => trim((string) $rawData['email']),
            'role' => trim((string) ($rawData['role'] ?? 'user')),
            'gender' => isset($rawData['gender']) ? trim((string) $rawData['gender']) : null,
            'date_of_birth' => isset($rawData['date_of_birth']) ? trim((string) $rawData['date_of_birth']) : null,
            'phone_number' => isset($rawData['phone']) ? trim((string) $rawData['phone']) : null,
        ];
    }

    /**
     * Coordinate and execute internal domain mutations using the transformed payload.
     *
     * @throws Throwable
     */
    public function updateOrCreateDomainState(array $payload, ?Model $model = null): void
    {
        /** @var User|null $user */
        $user = $model;

        if ($user) {
            $this->updateUser->execute(
                $user,
                new UpdateUserIdentityDTO(name: $payload['name'], email: $payload['email'])
            );
        } else {
            $user = $this->provisionNewUser->execute(
                new ProvisionUserDTO(
                    name: $payload['name'],
                    email: $payload['email'],
                    password: Str::random(8),
                    role: RoleType::USER->value
                )
            );
        }

        // Handle cross-domain profile write details
        UserImportWasProcessed::dispatch(
            userId: $user->id,
            email: $payload['email'],
            gender: $payload['gender'],
            dateOfBirth: $payload['date_of_birth'],
            phoneNumber: $payload['phone_number']
        );
    }
}
