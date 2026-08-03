<?php

namespace Modules\Attendance\DTOs;

readonly class CheckOutData
{
    public function __construct(
        public int $employeeId,
        public float $latitude,
        public float $longitude,
        public string $photoPath,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            employeeId: $data['employee_id'],
            latitude: (float) $data['latitude'],
            longitude: (float) $data['longitude'],
            photoPath: $data['photo_path'],
        );
    }
}
