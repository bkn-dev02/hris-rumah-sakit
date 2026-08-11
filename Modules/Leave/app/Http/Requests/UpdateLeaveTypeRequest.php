<?php

namespace Modules\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('leave-types.manage');
    }

    public function rules(): array
    {
        $leaveType = $this->route('leave_type');

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('leave_types', 'code')->ignore($leaveType)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'requires_quota' => ['boolean'],
        ];
    }
}
