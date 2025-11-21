<?php

namespace Presentation\Driver\Responses;

use Presentation\Base\Responses\PaginatedResponse;

readonly class DriverListResponse extends PaginatedResponse
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
            'drivers' => $this->formatDrivers(),
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

    private function formatDrivers(): array
    {
        return array_map(function ($driver) {
            return [
                'id' => $driver->id,
                'name' => $driver->name,
                'license_number' => $driver->licenseNumber,
                'phone_number' => $driver->phoneNumber,
                'vehicles_count' => isset($driver->vehicles) ? count($driver->vehicles) : 0,
                'created_at' => $driver->createdAt?->toIso8601String(),
                'updated_at' => $driver->updatedAt?->toIso8601String(),
            ];
        }, $this->items);
    }
}
