<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Change this!
        ]);

        // Add more admins if needed
        Admin::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
        ]);
    }
}