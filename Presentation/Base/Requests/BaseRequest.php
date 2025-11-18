<?php

namespace Presentation\Base\Requests;

use Helper\ArrayHelper;
use Illuminate\Support\Facades\Validator;

readonly abstract class BaseRequest
{
    public function __construct()
    {
        $this->validate();
    }

    public static function fromArray(array $input = []): static
    {
        $parameters = ArrayHelper::convertToCamelCase($input);

        return new static(...$parameters);
    }

    public function getData(): array
    {
        return get_object_vars($this);
    }

    public static function rules(): array
    {
        return [

        ];
    }

    public function validate(): void
    {
        Validator::make($this->getData(), static::rules())->validate();
    }
}
