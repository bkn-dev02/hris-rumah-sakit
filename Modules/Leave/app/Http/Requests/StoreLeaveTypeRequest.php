<?php

namespace Modules\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('leave-types.manage');
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:leave_types,code'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'requires_quota' => ['boolean'],
        ];
    }
}
