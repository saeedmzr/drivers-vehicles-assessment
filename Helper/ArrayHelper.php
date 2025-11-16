<?php

namespace Helper;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;

class ArrayHelper
{
    public static function convertToCamelCase($array): array
    {
        $finalArray = array();

        foreach ($array as $key => $value) :
            if (strpos($key, "_")) {
                $key = Str::camel($key);
            }

            if (!is_array($value)) {
                $finalArray[$key] = $value;
            } else {
                $finalArray[$key] = ArrayHelper::convertToCamelCase($value);
            }
        endforeach;

        return $finalArray;
    }

    public static function convertToSnakeCase($array): array
    {
        $finalArray = array();

        foreach ($array as $key => $value) :
            $key = Str::snake($key);
            if ($value instanceof Jsonable) {
                $value = $value->toJson();
            }

            if (!is_array($value)) {
                $finalArray[$key] = $value;
            } else {
                $finalArray[$key] = ArrayHelper::convertToSnakeCase($value);
            }
        endforeach;

        return $finalArray;
    }
}
