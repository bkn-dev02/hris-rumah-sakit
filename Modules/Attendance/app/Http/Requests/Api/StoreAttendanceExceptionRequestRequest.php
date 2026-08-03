<?php

namespace Modules\Attendance\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceExceptionRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_date' => ['required', 'date'],
            'attendance_status_id' => ['required', 'exists:attendance_statuses,id'],
            'reason' => ['required', 'string', 'max:500'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ];
    }
}
