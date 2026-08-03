<?php

namespace Modules\Master\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeePlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_temporary' => $this->boolean('is_temporary')]);
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'position_id'   => ['required', 'exists:positions,id'],
            'start_date'    => ['required', 'date'],
            'is_temporary'  => ['boolean'],
            'notes'         => ['nullable', 'string'],
        ];
    }
}
