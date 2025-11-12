<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HiringSourceSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            File::get(database_path("/data/hiring-source.json")),
            true,
        );

        SeederHelper::addTimeStamps($items);
        DB::table("hiring_source")->insert((array) $items);
    }
}
