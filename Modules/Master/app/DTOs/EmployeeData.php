<?php

namespace Modules\Master\DTOs;

use Carbon\Carbon;

readonly class EmployeeData
{
    public function __construct(
        public int $userId,
        public string $employeeNumber,
        public string $name,
        public string $gender,
        public int $employmentStatusId,
        public Carbon $hireDate,
        public ?string $placeOfBirth = null,
        public ?Carbon $dateOfBirth = null,
        public ?string $nationalIdNumber = null,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $maritalStatus = null,
        public ?string $educationLevel = null,
        public ?string $educationMajor = null,
        public bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            employeeNumber: $data['employee_number'],
            name: $data['name'],
            gender: $data['gender'],
            employmentStatusId: $data['employment_status_id'],
            hireDate: Carbon::parse($data['hire_date']),
            placeOfBirth: $data['place_of_birth'] ?? null,
            dateOfBirth: isset($data['date_of_birth']) ? Carbon::parse($data['date_of_birth']) : null,
            nationalIdNumber: $data['national_id_number'] ?? null,
            address: $data['address'] ?? null,
            phone: $data['phone'] ?? null,
            maritalStatus: $data['marital_status'] ?? null,
            educationLevel: $data['education_level'] ?? null,
            educationMajor: $data['education_major'] ?? null,
            isActive: $data['is_active'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'employee_number' => $this->employeeNumber,
            'name' => $this->name,
            'gender' => $this->gender,
            'employment_status_id' => $this->employmentStatusId,
            'hire_date' => $this->hireDate->toDateString(),
            'place_of_birth' => $this->placeOfBirth,
            'date_of_birth' => $this->dateOfBirth?->toDateString(),
            'national_id_number' => $this->nationalIdNumber,
            'address' => $this->address,
            'phone' => $this->phone,
            'marital_status' => $this->maritalStatus,
            'education_level' => $this->educationLevel,
            'education_major' => $this->educationMajor,
            'is_active' => $this->isActive,
        ];
    }
}
