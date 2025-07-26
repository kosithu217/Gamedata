<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gameworld.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create sample student user
        User::create([
            'name' => 'Student Demo',
            'email' => 'student@gameworld.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'class_level' => 'Grade 5',
            'date_of_birth' => '2015-01-01',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
