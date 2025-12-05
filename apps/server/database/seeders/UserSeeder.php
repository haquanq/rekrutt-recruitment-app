<?php

namespace Database\Seeders;

use App\Modules\Auth\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        $recruiterPositionIds = [41, 41, 41, 41, 43, 43, 43];

        UserFactory::new()->create([
            "email" => "recruiter@gmail.com",
            "position_id" => 41,
            "role" => UserRole::RECRUITER,
        ]);

        foreach ($recruiterPositionIds as $id) {
            UserFactory::new()->create([
                "position_id" => $id,
                "role" => UserRole::RECRUITER,
            ]);
        }

        $managerPositionIds = [10, 11, 12, 25, 29, 31, 34, 37, 39, 42, 44];

        UserFactory::new()->create([
            "email" => "manager@gmail.com",
            "role" => UserRole::MANAGER,
            "position_id" => 8,
        ]);

        UserFactory::new()->create([
            "email" => "hiring.manager@gmail.com",
            "role" => UserRole::HIRING_MANAGER,
            "position_id" => 41,
        ]);

        foreach ($managerPositionIds as $id) {
            UserFactory::new()->create([
                "position_id" => $id,
                "role" => UserRole::MANAGER,
            ]);
        }

        $executivePositionIds = [14, 20, 28, 30, 36, 45, 50];

        UserFactory::new()->create([
            "email" => "executive@gmail.com",
            "role" => UserRole::EXECUTIVE,
            "position_id" => 9,
        ]);

        foreach ($executivePositionIds as $id) {
            UserFactory::new()->create([
                "position_id" => $id,
                "role" => UserRole::EXECUTIVE,
            ]);
        }

        UserFactory::new()->create([
            "first_name" => "Ha",
            "last_name" => "Quang",
            "email" => "admin@gmail.com",
            "username" => "haquanq",
            "role" => UserRole::ADMIN,
            "position_id" => 44,
        ]);
    }
}
