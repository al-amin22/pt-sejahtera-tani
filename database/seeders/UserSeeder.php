<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@ptbarona.com',
            'password' => Hash::make('passwordbarona12'),
            'role' => 'admin',
        ]);

        // Staff
        User::create([
            'name' => 'Staff Satu',
            'email' => 'staff@ptbarona.com',
            'password' => Hash::make('passwordbarona'),
            'role' => 'staff',
        ]);

        // Finance
        User::create([
            'name' => 'Finance Satu',
            'email' => 'finance@ptbarona.com',
            'password' => Hash::make('passwordbarona'),
            'role' => 'finance',
        ]);
    }
}
