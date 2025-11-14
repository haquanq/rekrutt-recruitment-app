<?php

namespace Database\Factories;

use App\Modules\Candidate\Enums\CandidateStatus;
use App\Modules\Candidate\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;
    public function definition(): array
    {
        return [
            "first_name" => fake()->firstName(),
            "last_name" => fake()->lastName(),
            "date_of_birth" => fake()
                ->dateTimeBetween("-45years", "-21years")
                ->format("Y M d"),
            "address" => fake()->address(),
            "email" => fake()->unique()->safeEmail(),
            "phoneNumber" => fake()->unique()->numerify("##########"),
            "status" => CandidateStatus::NEW,
            "hiring_source_id" => random_int(1, 9),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
    }
}
