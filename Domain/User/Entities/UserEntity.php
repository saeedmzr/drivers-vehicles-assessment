<?php

namespace Domain\User\Entities;

use DateTimeInterface;

readonly class UserEntity
{
    public function __construct(
        public string             $id,
        public string             $name,
        public string             $email,
        public ?DateTimeInterface $emailVerifiedAt = null,
        public ?DateTimeInterface $createdAt = null,
        public ?DateTimeInterface $updatedAt = null,
        public ?DateTimeInterface $deletedAt = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public function isVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
