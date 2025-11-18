<?php

namespace Presentation\Vehicle\Responses;
readonly class SingleVehicleResponse
{
    public function __construct(
        public string $id,
        public string $plateNumber,
        public string $brand,
        public string $model,
        public int $year,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'plate_number' => $this->plateNumber,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
