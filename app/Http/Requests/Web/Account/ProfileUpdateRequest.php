<?php

namespace App\Http\Requests\Web\Account;

use App\Models\Identity\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date'],
            'phone_number' => ['required', 'numeric', 'max_digits:12', 'min_digits:9'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('domains/identity.fields.user.name'),
            'email' => __('domains/identity.fields.user.email'),
        ];
    }
}
