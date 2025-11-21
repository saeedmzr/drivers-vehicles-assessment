<?php

namespace Presentation\Driver\Responses;

use Presentation\Base\Responses\BaseResponse;

readonly class SingleDriverResponse extends BaseResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $licenseNumber,
        public string $phoneNumber,
        public array $vehicles,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'license_number' => $this->licenseNumber,
            'phone_number' => $this->phoneNumber,
            'vehicles' => $this->vehicles,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
