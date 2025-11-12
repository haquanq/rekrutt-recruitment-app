<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExperienceLevelSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            File::get(database_path("/data/experience-level.json")),
            true,
        );

        SeederHelper::addTimeStamps($items);
        DB::table("experience_level")->insert($items);
    }
}
