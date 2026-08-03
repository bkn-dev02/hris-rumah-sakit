<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => Str::of($this->username)
                ->trim()
                ->lower()
                ->replaceMatches('/\s+/', '.')
                ->toString(),
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['nullable', 'integer'],

            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-z0-9._]+$/',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
            'roles'    => ['array'],
            'roles.*'  => ['exists:roles,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'employee_id' => 'pegawai',
            'username' => 'username',
            'email' => 'email',
            'password' => 'password',
            'is_active' => 'status aktif',
        ];
    }
}
