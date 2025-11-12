<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = json_decode(
            File::get(database_path("/data/department.json")),
            true,
        );

        SeederHelper::addTimeStamps($departments);
        foreach ($departments as $department) {
            $positions = $department["positions"];
            unset($department["positions"]);
            $departmentId = DB::table("department")->insertGetId($department);

            foreach ($positions as &$position) {
                $position["department_id"] = $departmentId;
            }
            SeederHelper::addTimeStamps($positions);
            DB::table("position")->insert($positions);
        }
    }
}
