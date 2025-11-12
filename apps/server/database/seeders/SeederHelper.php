<?php

namespace Database\Seeders;

use Carbon\Carbon;

class SeederHelper
{
    public static function addTimeStamps(object|array &$data)
    {
        if (is_array($data)) {
            foreach ($data as &$item) {
                $item["created_at"] = Carbon::now();
                $item["updated_at"] = Carbon::now();
            }
        } elseif (is_object($data)) {
            $data->created_at = Carbon::now();
            $data->updated_at = Carbon::now();
        }
    }
}
