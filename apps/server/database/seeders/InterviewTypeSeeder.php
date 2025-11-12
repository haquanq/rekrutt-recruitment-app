<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InterviewTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            File::get(database_path("/data/interview-type.json")),
            true,
        );

        SeederHelper::addTimeStamps($items);
        DB::table("interview_type")->insert($items);
    }
}
