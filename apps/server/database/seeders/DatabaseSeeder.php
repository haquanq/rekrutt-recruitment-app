<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            ContractTypeSeeder::class,
            DepartmentSeeder::class,
            EducationLevelSeeder::class,
            ExperienceLevelSeeder::class,
            InterviewTypeSeeder::class,
            RatingScaleSeeder::class,
            HiringSourceSeeder::class,
            CandidateSeeder::class,
            UserSeeder::class,
        ]);
    }
}
