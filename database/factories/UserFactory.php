<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // Add this import

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $definition = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'age' => $this->faker->numberBetween(18, 70),
            'interests' => $this->faker->randomElements(
                ['Cities', 'Nature', 'Culture', 'Food', 'Adventure'],
                $this->faker->numberBetween(1, 3)
            ),
        ];

        if (Schema::hasColumn('users', 'email_verified_at')) {
            $definition['email_verified_at'] = now();
        }

        return $definition;
    }
}