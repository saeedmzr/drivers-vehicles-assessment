<?php

namespace Presentation\Base\Requests;

use Helper\ArrayHelper;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validated data with camelCase keys
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (is_array($validated)) {
            return ArrayHelper::convertToCamelCase($validated);
        }

        return $validated;
    }

    /**
     * Get all input with snake_case keys for validation
     */
    public function validationData(): array
    {
        return ArrayHelper::convertToSnakeCase($this->all());
    }
}
