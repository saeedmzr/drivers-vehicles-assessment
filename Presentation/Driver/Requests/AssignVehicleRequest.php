<?php

namespace Presentation\Driver\Requests;

use Presentation\Base\Requests\BaseRequest;

readonly class AssignVehicleRequest extends BaseRequest
{
    public function __construct(
        public string $vehicleId,
        public ?string $notes = null,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'vehicleId' => ['required', 'string', 'exists:vehicles,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function getVehicleId(): string
    {
        return $this->vehicleId;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
