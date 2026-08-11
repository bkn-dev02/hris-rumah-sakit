<?php

namespace Modules\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeLeaveQuotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('leave-quotas.manage');
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'leave_type_id' => ['required', 'integer', 'exists:leave_types,id'],
            'year' => ['required', 'integer', 'digits:4'],
            'quota_days' => ['required', 'integer', 'min:0', 'max:365'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exists = \Modules\Leave\Models\EmployeeLeaveQuota::query()
                ->where('employee_id', $this->employee_id)
                ->where('leave_type_id', $this->leave_type_id)
                ->where('year', $this->year)
                ->exists();

            if ($exists) {
                $validator->errors()->add('employee_id', 'Kuota untuk karyawan, jenis cuti, dan tahun ini sudah ada.');
            }
        });
    }
}
