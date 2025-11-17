<?php

namespace Presentation\Driver\Requests;

use Presentation\Base\Requests\BaseRequest;

readonly class CreateDriverRequest extends BaseRequest
{
    public function __construct(
        public string $name,
        public string $licenseNumber,
        public string $phoneNumber,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:drivers,license_number'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:drivers,phone_number'],
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLicenseNumber(): string
    {
        return $this->licenseNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}
