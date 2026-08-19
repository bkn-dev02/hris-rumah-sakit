<?php

namespace Modules\Leave\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type_id' => ['required', 'integer', 'exists:leave_types,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'max:2048', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ];
    }

    public function messages(): array
    {
        return [
            'leave_type_id.required' => 'Leave type is required.',
            'leave_type_id.integer' => 'Leave type must be a valid integer.',
            'leave_type_id.exists' => 'Selected leave type does not exist.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'End date cannot be before start date.',
            'reason.required' => 'Reason for leave is required.',
            'reason.string' => 'Reason must be a valid string.',
            'reason.max' => 'Reason cannot exceed 1000 characters.',
            'attachment.file' => 'Attachment must be a valid file.',
            'attachment.max' => 'Attachment size cannot exceed 2MB.',
            'attachment.mimes' => 'Attachment must be a file of type: pdf, doc, docx, jpg, jpeg, png.',
        ];
    }
}
