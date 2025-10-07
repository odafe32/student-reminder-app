<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@nsuk.edu.ng',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+234-803-123-4567',
            'department' => 'Information Technology',
            'email_verified_at' => now(),
        ]);

        // Create a test student user
        User::create([
            'name' => 'John Doe',
            'email' => 'student@nsuk.edu.ng',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'active',
            'phone' => '+234-805-987-6543',
            'department' => 'Computer Science',
            'level' => '300',
            'email_verified_at' => now(),
        ]);

        // Create additional sample students
        $sampleStudents = [
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@student.nsuk.edu.ng',
                'department' => 'Mathematics',
                'level' => '200',
                'phone' => '+234-806-111-2222',
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael.johnson@student.nsuk.edu.ng',
                'department' => 'Physics',
                'level' => '400',
                'phone' => '+234-807-333-4444',
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah.williams@student.nsuk.edu.ng',
                'department' => 'Chemistry',
                'level' => '100',
                'phone' => '+234-808-555-6666',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@student.nsuk.edu.ng',
                'department' => 'Biology',
                'level' => '300',
                'phone' => '+234-809-777-8888',
            ],
        ];

        foreach ($sampleStudents as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password123'),
                'role' => 'student',
                'status' => 'active',
                'phone' => $student['phone'],
                'department' => $student['department'],
                'level' => $student['level'],
                'email_verified_at' => now(),
            ]);
        }

        // Create additional admin users
        User::create([
            'name' => 'Dr. Mary Johnson',
            'email' => 'mary.johnson@nsuk.edu.ng',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '+234-810-999-0000',
            'department' => 'Academic Affairs',
            'email_verified_at' => now(),
        ]);

        // Create some inactive users for testing
        User::create([
            'name' => 'Inactive Student',
            'email' => 'inactive@student.nsuk.edu.ng',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'status' => 'inactive',
            'phone' => '+234-811-123-4567',
            'department' => 'Computer Science',
            'level' => '200',
            'email_verified_at' => now(),
        ]);
    }
}
