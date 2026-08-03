<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100'],
            'code'          => ['required', 'string', 'max:50', 'unique:roles,code'],
            'description'   => ['nullable', 'string'],
            'is_active'     => ['boolean'],
            'permissions'   => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }
}
