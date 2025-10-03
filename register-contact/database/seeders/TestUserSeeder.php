<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user active
        User::create([
            'name' => 'Active User',
            'email' => 'active@test.com',
            'password' => Hash::make('password'),
            'isActive' => true,
        ]);

        // Tạo user inactive
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@test.com',
            'password' => Hash::make('password'),
            'isActive' => false,
        ]);
    }
}
