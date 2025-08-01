<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Adminseeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::UpdateorCreate([
            'name' => 'Admin ',
            'email' => 'nivethitha0524@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_approved' => 1,
        ]);



    }
}
