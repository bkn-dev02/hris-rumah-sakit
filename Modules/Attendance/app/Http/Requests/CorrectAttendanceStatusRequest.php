<?php

namespace Modules\Attendance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CorrectAttendanceStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attendance_status_id' => ['required', 'exists:attendance_statuses,id'],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
