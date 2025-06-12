<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $adminData = [
            'first_name'     => 'Admin',
            'last_name'      => 'User',
            'email'          => 'admin@example.com',
            'password'       => Hash::make('password123'),
            'age'            => 30,
            'interests'      => ['Cities', 'Food'],
            'avatar_url'     => 'https://ui-avatars.com/api/?name=Admin+User',
            'location'       => 'Paris',
            'remember_token' => Str::random(10),
        ];

        // Optional email_verified_at
        if (Schema::hasColumn('users', 'email_verified_at')) {
            $adminData['email_verified_at'] = now();
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            $adminData
        );

        // Create 10 regular users using factory
        User::factory(10)->create();

        $this->command->info('Users seeded successfully!');
    }
}
