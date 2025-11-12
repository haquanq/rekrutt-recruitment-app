<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Database\Factories\CandidateFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateSeeder extends Seeder
{
    public function run(): void
    {
        CandidateFactory::new()->count(500)->create();
    }
}
