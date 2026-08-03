<?php

namespace Modules\Master\DTOs;

use Carbon\Carbon;

readonly class ShiftVersionData
{
    public function __construct(
        public string $code,
        public string $name,
        public string $startTime,
        public string $endTime,
        public Carbon $effectiveDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            startTime: $data['start_time'],
            endTime: $data['end_time'],
            effectiveDate: Carbon::parse($data['effective_date']),
        );
    }
}
