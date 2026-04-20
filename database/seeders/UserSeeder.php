<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin default
        User::create([
            'name'       => 'Super Admin',
            'email'      => 'admin@gmail.com',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'department' => 'IT',
            'is_active'  => 1,
        ]);

        // Mekanik default
        User::create([
            'name'       => 'Mekanik 1',
            'email'      => 'mekanik@gmail.com',
            'password'   => Hash::make('mekanik123'),
            'role'       => 'mekanik',
            'department' => 'Maintenance',
            'is_active'  => 1, // 1 = aktif
        ]);
        User::create([
            'name' => 'Maintenance Planning',
            'email' => 'mp@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'maintenance-planning',
            'department' => 'Maintenance', // optional
            'is_active' => 1,
        ]);

    }
}
