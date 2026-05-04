<?php

namespace App\Http\Requests\Web\Identity;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('user create') || Gate::allows('user update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
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
