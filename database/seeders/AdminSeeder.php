<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'phone' => '0123456789',
                'password' => 'password123', // ปล่อยให้ model hash อัตโนมัติ
                'role' => 'admin',
            ]
        );

        // สร้าง User ทั่วไป (optional)
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'phone' => '0987654321',
                'password' => 'password123',
                'role' => 'user',
            ]
        );
    }
}
