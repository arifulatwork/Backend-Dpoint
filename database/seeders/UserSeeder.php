<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // Add this import

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $adminData = [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'age' => 30,
            'interests' => ['Cities', 'Food'],
            'remember_token' => Str::random(10),
        ];

        // Only add email_verified_at if column exists
        if (Schema::hasColumn('users', 'email_verified_at')) {
            $adminData['email_verified_at'] = now();
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            $adminData
        );

        // Create regular users
        User::factory(10)->create();

        $this->command->info('Users seeded successfully!');
    }
}