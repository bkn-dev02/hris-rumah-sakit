<?php

namespace Modules\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeLeaveQuotasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('leave-quotas.manage');
    }

    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'digits:4'],
            'quotas' => ['required', 'array'],
            'quotas.*.leave_type_id' => ['required', 'integer', 'exists:leave_types,id'],
            'quotas.*.quota_days' => ['required', 'integer', 'min:0', 'max:365'],
        ];
    }
}
