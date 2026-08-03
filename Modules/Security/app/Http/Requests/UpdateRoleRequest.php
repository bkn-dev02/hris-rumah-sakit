<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'code'          => ['required', 'string', 'max:50', Rule::unique('roles', 'code')->ignore($this->route('role'))],
            'description'   => ['nullable', 'string'],
            'is_active'     => ['boolean'],
            'permissions'   => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }
}
