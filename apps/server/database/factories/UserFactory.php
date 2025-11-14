<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Modules\Auth\Models\User;

class UserFactory extends Factory
{
    protected static ?string $password = "123456";

    protected $model = User::class;
    public function definition(): array
    {
        return [
            "first_name" => fake()->firstName(),
            "last_name" => fake()->lastName(),
            "email" => fake()->unique()->safeEmail(),
            "phone_number" => fake()->unique()->numerify("##########"),
            "username" => fake()->userName(),
            "password" => (static::$password ??= Hash::make("password")),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ];
    }
}
