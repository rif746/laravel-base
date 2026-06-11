<?php

namespace App\Domains\Identity\Integration\Mappers;

use App\Domains\Account\Actions\Profile\UpdateProfile;
use App\Domains\Account\DTOs\Profile\UpdateProfileDTO;
use App\Domains\Account\Enums\GenderOption;
use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Actions\Onboarding\ProvisionNewUser;
use App\Domains\Identity\Actions\Onboarding\UpdateUser;
use App\Domains\Identity\DTOs\Onboarding\ProvisionUserDTO;
use App\Domains\Identity\DTOs\Onboarding\UpdateUserDTO;
use App\Domains\Identity\Enums\RoleType;
use App\Domains\Identity\Models\User;
use App\Domains\System\Support\Integration\DataPayloadMapper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class UserDataMapper implements DataPayloadMapper
{
    public function __construct(
        protected ProvisionNewUser $provisionNewUser,
        protected UpdateUser $updateUser,
        protected UpdateProfile $updateProfile
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
            'name' => trim((string)$rawData['name']),
            'email' => trim((string)$rawData['email']),
            'role' => trim((string)($rawData['role'] ?? 'user')),
            'gender' => isset($rawData['gender']) ? trim((string)$rawData['gender']) : null,
            'date_of_birth' => isset($rawData['date_of_birth']) ? trim((string)$rawData['date_of_birth']) : null,
            'phone_number' => isset($rawData['phone']) ? trim((string)$rawData['phone']) : null,
        ];
    }

    /**
     * Coordinate and execute internal domain mutations using the transformed payload.
     * @throws Throwable
     */
    public function updateOrCreateDomainState(array $payload, ?Model $model = null): void
    {
        /** @var User|null $user */
        $user = $model;

        if ($user) {
            $this->updateUser->execute(
                $user,
                new UpdateUserDTO(name: $payload['name'], email: $payload['email'])
            );
        } else {
            $user = $this->provisionNewUser->execute(
                new ProvisionUserDTO(
                    name: $payload['name'],
                    email: $payload['email'],
                    password: bcrypt(Str::random(16)),
                    role: RoleType::USER->value
                )
            );
        }

        // Handle cross-domain profile write details
        $this->updateProfile->execute(
            $user->profile ?? new Profile(['user_id' => $user->id]),
            new UpdateProfileDTO(
                userId: $user->id,
                gender:  GenderOption::fromLabel($payload['gender'])->value,
                date_of_birth: $payload['date_of_birth'],
                phone_number: $payload['phone_number']
            )
        );
    }
}
