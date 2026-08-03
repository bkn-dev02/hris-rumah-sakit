<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdateUserRequest extends FormRequest
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
        $slug = $this->route('slug');

        return [
            'employee_id' => ['nullable', 'integer'],

            'username' => [
                'required',
                'string',
                'min:4',
                'max:50',
                'regex:/^[a-z0-9._]+$/',
                Rule::unique('users', 'username')->ignore($slug, 'slug'),
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($slug, 'slug'),
            ],

            'is_active' => [
                'required',
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
            'is_active' => 'status aktif',
        ];
    }
}
