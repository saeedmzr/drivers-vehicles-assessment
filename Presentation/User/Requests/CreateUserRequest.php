<?php

namespace App\Application\Usecases\Core\DTO;

use Helper\ArrayHelper;
use Illuminate\Support\Facades\Validator;

readonly abstract class CreateDriverRequest extends BaseRequest
{
    public function __construct(

    ) {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
        ];
    }

}
