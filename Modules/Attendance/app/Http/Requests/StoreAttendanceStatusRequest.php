<?php

namespace Modules\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceStatusRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:50', 'unique:attendance_statuses,code'],
            'name' => ['required', 'string', 'max:100'],
            'category' => ['required', 'in:normal,exception,review'],
            'determination_type' => ['required', 'in:auto,manual'],
            'is_active' => ['boolean'],
        ];
    }
}
