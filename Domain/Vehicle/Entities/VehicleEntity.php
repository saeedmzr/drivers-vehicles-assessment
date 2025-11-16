<?php

namespace Domain\Vehicle\Entities;

use DateTimeInterface;

class VehicleEntity
{
    public function __construct(
        public readonly string             $id,
        public readonly string             $plateNumber,
        public readonly string             $brand,
        public readonly string             $model,
        public readonly int                $year,
        public readonly ?DateTimeInterface $createdAt = null,
        public readonly ?DateTimeInterface $updatedAt = null,
        public readonly ?DateTimeInterface $deletedAt = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'plate_number' => $this->plateNumber,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function getFullName(): string
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getAge(): int
    {
        return date('Y') - $this->year;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function isActive(): bool
    {
        return $this->deletedAt === null;
    }
}
