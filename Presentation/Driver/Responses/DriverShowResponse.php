<?php

namespace Presentation\Driver\Responses;

use Presentation\Base\Responses\BaseResponse;

readonly class DriverShowResponse extends BaseResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $licenseNumber,
        public string $phoneNumber,
        public array $vehicles,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        public ?string $deletedAt = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'license_number' => $this->licenseNumber,
            'phone_number' => $this->phoneNumber,
            'vehicles' => $this->formatVehicles(),
            'vehicles_count' => count($this->vehicles),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];

        if ($this->deletedAt) {
            $data['deleted_at'] = $this->deletedAt;
        }

        return $data;
    }

    private function formatVehicles(): array
    {
        return array_map(function ($vehicle) {
            $vehicleData = [
                'id' => $vehicle->id,
                'plate_number' => $vehicle->plateNumber,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
            ];

            // Add pivot data if exists
            if (isset($vehicle->assignedAt) || isset($vehicle->isActive) || isset($vehicle->notes)) {
                $vehicleData['assignment'] = array_filter([
                    'assigned_at' => $vehicle->assignedAt ?? null,
                    'is_active' => $vehicle->isActive ?? null,
                    'released_at' => $vehicle->releasedAt ?? null,
                    'notes' => $vehicle->notes ?? null,
                ], fn($value) => $value !== null);
            }

            return $vehicleData;
        }, $this->vehicles);
    }
}
