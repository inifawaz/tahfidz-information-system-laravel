<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ])->assignRole('admin');

        $teacherUser = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
            'password' => '12345678'
        ])->assignRole('teacher');

        $studentUser = User::create([
            'name' => 'Student',
            'email' => 'student@gmail.com',
            'password' => '12345678'
        ])->assignRole('student');
    }
}
