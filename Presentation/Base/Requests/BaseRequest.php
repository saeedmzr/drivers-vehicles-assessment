<?php

namespace Presentation\Base\Requests;

use Helper\ArrayHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

readonly abstract class BaseRequest
{
    public function __construct()
    {
        $this->validate();
    }

    /**
     * Create instance from array data
     */
    public static function fromArray(array $input = []): static
    {
        $parameters = ArrayHelper::convertToCamelCase($input);
        return new static(...$parameters);
    }

    /**
     * Create instance from Laravel Request (includes query parameters)
     */
    public static function fromRequest(Request $request): static
    {
        $allData = array_merge(
            $request->query->all(),
            $request->request->all(),
            $request->route()->parameters()
        );

        return static::fromArray($allData);
    }

    /**
     * Get all data as array
     */
    public function getData(): array
    {
        return get_object_vars($this);
    }

    /**
     * Get validation rules
     */
    public static function rules(): array
    {
        return [];
    }

    /**
     * Validate the request data
     */
    public function validate(): void
    {
        $data = ArrayHelper::convertToSnakeCase($this->getData());
        Validator::make($data, static::rules())->validate();
    }
}
