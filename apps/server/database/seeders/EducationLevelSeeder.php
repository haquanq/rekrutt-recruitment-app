<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EducationLevelSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            File::get(database_path("/data/education-level.json")),
            true,
        );

        SeederHelper::addTimeStamps($items);
        DB::table("education_level")->insert($items);
    }
}
