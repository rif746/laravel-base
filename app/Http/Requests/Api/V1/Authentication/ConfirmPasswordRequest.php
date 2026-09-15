<?php

namespace App\Http\Requests\Api\V1\Authentication;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @schemaName Confirm Password
 * @schemaVariant Auth
 */
class ConfirmPasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'current_password'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function confirm(): bool
    {
        $user = $this->user();
        $token = $user->currentAccessToken();
        $device_name = $this->post('device_name');

        if($token->name !== $device_name) {
            throw ValidationException::withMessages([
                'device_name' => __('validation.any_of', ['attribute' => 'device_name'])
            ]);
        }

        return true;
    }
}
