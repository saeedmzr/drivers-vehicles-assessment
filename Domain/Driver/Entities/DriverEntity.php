<?php

namespace Domain\Driver\Entities;

use DateTimeInterface;

class DriverEntity
{
    public function __construct(
        public readonly string             $id,
        public readonly string             $name,
        public readonly string             $licenseNumber,
        public readonly string             $phoneNumber,
        public readonly array $vehicles = [],
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
            'name' => $this->name,
            'license_number' => $this->licenseNumber,
            'phone_number' => $this->phoneNumber,
            'vehicles' => $this->vehicles,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function getFormattedPhone(): string
    {
        return preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $this->phoneNumber);
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
