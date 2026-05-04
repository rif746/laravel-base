<?php

namespace App\Http\Requests\Web\Identity;

use Gate;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('role create') || Gate::allows('role update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'guard_name' => ['required', 'string'],
            'permissions' => ['array', 'min:1'],
            'permissions.*' => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('domains/identity.fields.role.name'),
            'guard_name' => __('domains/identity.fields.role.guard_name'),
            'permissions' => __('domains/identity.fields.role.permissions'),
        ];
    }
}
