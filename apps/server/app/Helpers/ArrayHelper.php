<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ArrayHelper
{
    public static function convertKeys(array $data, callable $converter): array
    {
        $newArray = [];
        foreach ($data as $key => $value) {
            $newValue = $value;

            if (\is_array($newValue) || \is_object($newValue)) {
                $newValue = static::convertKeys((array) $newValue, $converter);
            }

            $newArray[$converter(\strval($key))] = $newValue;
        }
        return $newArray;
    }

    public static function convertKeysToCamelCase(array $data): array
    {
        return static::convertKeys($data, fn($value) => Str::camel($value));
    }

    public static function convertKeysToSnakeCase(array $data): array
    {
        return static::convertKeys($data, fn($value) => Str::snake($value));
    }
}
