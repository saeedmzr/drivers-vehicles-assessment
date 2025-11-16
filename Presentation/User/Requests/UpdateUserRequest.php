<?php

namespace App\Application\Usecases\Core\DTO;

readonly abstract class UpdateDriverRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function rules(): array
    {
        return [
        ];
    }

}
