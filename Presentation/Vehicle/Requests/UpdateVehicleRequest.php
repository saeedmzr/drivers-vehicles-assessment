<?php

namespace Presentation\Vehicle\Requests;

use Helper\ArrayHelper;
use Presentation\Base\Requests\BaseRequest;

readonly class UpdateVehicleRequest extends BaseRequest
{
    public function __construct(
        public ?string $plateNumber = null,
        public ?string $brand = null,
        public ?string $model = null,
        public ?int $year = null,
    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
            'plate_number' => ['nullable', 'string', 'max:255', 'unique:vehicles,plate_number'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ];
    }

    public function getUpdateData(): array
    {
        return array_filter(
            ArrayHelper::convertToSnakeCase(get_object_vars($this)),
            fn($value) => $value !== null
        );
    }
}
