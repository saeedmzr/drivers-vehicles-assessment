<?php

namespace Presentation\Base\Responses;
use Helper\ArrayHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

readonly abstract class BaseResponse implements Arrayable, Jsonable
{

    public function toArray(): array
    {
        return ArrayHelper::convertToSnakeCase(get_object_vars($this));
    }

    public function toJson($options = 0): array
    {
        return $this->toArray();
    }
}
