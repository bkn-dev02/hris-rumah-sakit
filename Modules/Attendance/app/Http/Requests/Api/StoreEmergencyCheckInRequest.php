<?php

namespace Modules\Attendance\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmergencyCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'reason' => ['required', 'string', 'max:1000'],
            'selfie_photo' => ['required', 'file', 'image', 'max:5120'],
            'proof_photo' => ['required', 'file', 'image', 'max:5120'],
        ];
    }
}
