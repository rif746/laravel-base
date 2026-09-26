<?php

namespace App\Http\Requests\Api\Identity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['email', 'required'],
            'name' => ['string', 'required'],
            'role' => ['string', 'required'],
            'password' => Password::default(),
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => __('domains/identity/field.user.email'),
            'name' => __('domains/identity/field.user.name'),
            'role' => __('resources.role'),
            'password' => __('domains/identity/field.user.password'),
        ];
    }
}
