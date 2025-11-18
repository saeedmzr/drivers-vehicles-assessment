<?php

namespace Presentation\Vehicle\Requests;

use Presentation\Base\Requests\BaseRequest;

readonly class CreateVehicleRequest extends BaseRequest
{
    public function __construct(
        public string $plateNumber,
        public string $brand,
        public string $model,
        public int $year,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'plateNumber' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getYear(): int
    {
        return $this->year;
    }
}
