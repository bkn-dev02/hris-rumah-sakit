<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active', true)]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'employee_number' => ['required', 'string', 'max:30', 'unique:employees,employee_number'],
            'name' => ['required', 'string', 'max:150'],
            'gender' => ['required', 'in:male,female'],
            'place_of_birth' => ['nullable', 'string', 'max:100'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'national_id_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'in:single,married,divorced,widowed'],
            'education_level' => ['nullable', 'string', 'max:20'],
            'education_major' => ['nullable', 'string', 'max:100'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'hire_date' => ['required', 'date'],
            'employment_status_id'  => ['required', 'exists:employment_statuses,id'],
            'is_active' => ['boolean'],
        ];
    }
}
