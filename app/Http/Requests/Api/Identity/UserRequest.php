<?php

namespace App\Http\Requests\Api\Identity;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends ApiRequest
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
            'email' => __('domains/identity.fields.user.email'),
            'name'  => __('domains/identity.fields.user.name'),
            'role'  => __('resources.role'),
            'password' => __('domains/identity.fields.user.password'),
        ];
    }
}
