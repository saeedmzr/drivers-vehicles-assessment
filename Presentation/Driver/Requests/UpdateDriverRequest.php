<?php

namespace Presentation\Driver\Requests;


use Presentation\Base\Requests\BaseRequest;

readonly class UpdateDriverRequest extends BaseRequest
{
    public function __construct(
        public ?string $name = null,
        public ?string $licenseNumber = null,
        public ?string $phoneNumber = null,
    )
    {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'licenseNumber' => ['nullable', 'string', 'max:255'],
            'phoneNumber' => ['nullable', 'string', 'max:255'],
        ];
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

    public function getUpdateData(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->licenseNumber !== null) {
            $data['license_number'] = $this->licenseNumber;
        }

        if ($this->phoneNumber !== null) {
            $data['phone_number'] = $this->phoneNumber;
        }

        return $data;
    }
}
