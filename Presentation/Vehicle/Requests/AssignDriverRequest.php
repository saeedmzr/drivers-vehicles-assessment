<?php

namespace Presentation\Vehicle\Requests;

use Presentation\Base\Requests\BaseRequest;

readonly class AssignDriverRequest extends BaseRequest
{
    public function __construct(
        public string $driverId,
        public ?string $notes = null,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'driverId' => ['required', 'string', 'exists:drivers,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
