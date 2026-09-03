<?php

namespace Modules\Attendance\Http\Requests;

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
            'selfie_photo' => ['required', 'image', 'max:5120'],
            'proof_photo'  => ['required', 'image', 'max:5120'],
            'reason'       => ['required', 'string', 'max:500'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'selfie_photo.required' => 'Foto selfie wajib diunggah.',
            'selfie_photo.image'    => 'Foto selfie harus berupa gambar.',
            'proof_photo.required'  => 'Foto bukti kendala wajib diunggah.',
            'proof_photo.image'     => 'Foto bukti kendala harus berupa gambar.',
            'reason.required'       => 'Alasan/keterangan wajib diisi.',
            'latitude.required'     => 'Lokasi Anda wajib diaktifkan untuk mengirim presensi darurat.',
            'longitude.required'    => 'Lokasi Anda wajib diaktifkan untuk mengirim presensi darurat.',
        ];
    }
}
