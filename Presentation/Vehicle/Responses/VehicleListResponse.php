<?php

namespace Presentation\Vehicle\Responses;

use Presentation\Base\Responses\PaginatedResponse;

readonly class VehicleListResponse extends PaginatedResponse
{
    public function __construct(
        array $items,
        int $total,
        int $perPage,
        int $page,
    ) {
        parent::__construct(
            items: $items,
            total: $total,
            perPage: $perPage,
            page: $page
        );
    }

    public function toArray(): array
    {
        return [
            'vehicles' => $this->formatVehicles(),
            'meta' => [
                'total' => $this->total,
                'per_page' => $this->perPage,
                'current_page' => $this->page,
                'last_page' => $this->perPage > 0 ? (int)ceil($this->total / $this->perPage) : 1,
                'from' => $this->total > 0 ? (($this->page - 1) * $this->perPage) + 1 : 0,
                'to' => min($this->page * $this->perPage, $this->total),
            ],
        ];
    }

    private function formatVehicles(): array
    {
        return array_map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'plate_number' => $vehicle->plateNumber,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'drivers_count' => isset($vehicle->drivers) ? count($vehicle->drivers) : 0,
                'has_active_drivers' => $this->hasActiveDrivers($vehicle),
                'created_at' => $vehicle->createdAt?->toIso8601String(),
                'updated_at' => $vehicle->updatedAt?->toIso8601String(),
            ];
        }, $this->items);
    }

    private function hasActiveDrivers($vehicle): bool
    {
        if (!isset($vehicle->drivers) || empty($vehicle->drivers)) {
            return false;
        }

        foreach ($vehicle->drivers as $driver) {
            if (($driver->isActive ?? false) === true) {
                return true;
            }
        }

        return false;
    }
}
