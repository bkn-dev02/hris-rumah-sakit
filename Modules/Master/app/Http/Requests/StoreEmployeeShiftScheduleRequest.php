<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeShiftScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shift_id'   => ['required', 'exists:shifts,id'],
            'start_date' => ['required', 'date'],
            'notes'      => ['nullable', 'string'],
        ];
    }
}
