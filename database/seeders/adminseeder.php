<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_approved' => 1,
        ]);

        // Regular approved user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_approved' => 1,
        ]);

        // Unapproved user
        User::create([
            'name' => 'Pending User',
            'email' => 'pending@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_approved' => 0,
        ]);
    }
}
