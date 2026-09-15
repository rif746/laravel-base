<?php

namespace App\Http\Requests\Api\V1\Authentication;

use App\Domains\Identity\Actions\Authentication\IssueApiToken;
use App\Domains\Identity\DTOs\Authentication\IssueApiTokenDTO;
use App\Http\Concerns\WithRateLimiting;
use App\Http\Resources\Identity\UserResource;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    use WithRateLimiting;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('domains/identity/field.user.email'),
            'password' => __('domains/identity/field.user.password'),
        ];
    }

    public function authenticate(IssueApiToken $issueTokenAction): ?array
    {
        $this->ensureIsNotRateLimited(
            keyIdentifier: 'email',
        );

        try {
             $issue = $issueTokenAction->execute(new IssueApiTokenDTO(
                email: $this->post('email'),
                password: $this->post('password'),
                deviceName: $this->post('device_name'),
                ipAddress: $this->ip(),
                userAgent: $this->userAgent()
            ));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'email' => $e->getMessage()
            ]);
        }

        $this->clearRateLimiter('email');

        return [
            'user' => new UserResource($issue['user']),
            'token' => $issue['token']
        ];
    }
}
