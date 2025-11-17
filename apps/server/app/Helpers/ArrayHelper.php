<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class ArrayHelper
{
    public static function convertKeys(array $data, callable $converter): array
    {
        $newArray = [];
        foreach ($data as $key => $value) {
            $newValue = $value;

            if (\is_array($value)) {
                $newValue = static::convertKeys($value, $converter);
            }

            $newArray[$converter(\strval($key))] = $newValue;
        }
        Log::info(json_encode($newArray));
        return $newArray;
    }
}
