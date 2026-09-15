<?php

namespace App\Http\Requests\Api\V1\Authentication;

use App\Domains\Identity\Actions\Passwords\SendPasswordResetLink;
use App\Domains\Identity\DTOs\Passwords\ForgotPasswordDTO;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ForgotPasswordRequest extends FormRequest
{
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
            'email' => ['required', 'string', 'email', Rule::exists(User::class, 'email')],
        ];
    }

    public function sendPasswordReset(SendPasswordResetLink $resetLink): string
    {
        $status = $resetLink->execute(new ForgotPasswordDTO(
            email: $this->post('email')
        ));


        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => __('domains/auth/messages.reset_link_failed')
            ]);
        }

        return  __('domains/auth/messages.reset_link_sent');
    }
}
