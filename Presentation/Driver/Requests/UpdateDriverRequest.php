<?php

namespace Presentation\Driver\Requests;

use Presentation\Base\Requests\BaseRequest;

readonly class UpdateDriverRequest extends BaseRequest
{
    public function __construct(
        public ?string $name = null,
        public ?string $licenseNumber = null,
        public ?string $phoneNumber = null,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'license_number' => ['sometimes', 'string', 'max:255'],
            'phone_number' => ['sometimes', 'string', 'max:255'],
        ];
    }

    public function getData(): array
    {
        return array_filter(parent::getData(), fn($value) => $value !== null);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLicenseNumber(): ?string
    {
        return $this->licenseNumber;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function hasLicenseNumber(): bool
    {
        return $this->licenseNumber !== null;
    }

    public function hasPhoneNumber(): bool
    {
        return $this->phoneNumber !== null;
    }

    public function getUpdateData(): array
    {
        return array_filter([
            'name' => $this->getName(),
            'license_number' => $this->getLicenseNumber(),
            'phone_number' => $this->getPhoneNumber(),
        ], fn($value) => $value !== null);
    }
}
