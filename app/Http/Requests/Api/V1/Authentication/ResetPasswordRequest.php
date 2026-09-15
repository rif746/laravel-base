<?php

namespace App\Http\Requests\Api\V1\Authentication;

use App\Domains\Identity\Actions\Passwords\ResetUserPassword;
use App\Domains\Identity\DTOs\Passwords\ResetPasswordDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Validates reset password token existence and expiration.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 1. Resolve token from route parameters or request input fallback
        $token = (string) ($this->route('token') ?? $this->input('token'));
        $email = (string) $this->input('email');

        if (empty($token) || empty($email)) {
            return false;
        }

        // 2. Fetch the associated user model by email
        $user = Password::getUser(['email' => $email]);

        // 3. Verify token validity against Laravel Password Broker
        return $user !== null && Password::tokenExists($user, $token);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', PasswordRule::defaults()],
        ];
    }

    public function resetPassword(ResetUserPassword $resetUserPassword): string
    {
        return $resetUserPassword->execute(new ResetPasswordDTO(
            token: $this->route('token'),
            email: $this->input('email'),
            password: $this->input('password'),
            password_confirmation: $this->input('password_confirmation')
        ));
    }
}
