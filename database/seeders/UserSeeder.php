<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Supervisor account
        User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@nawatara.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'division' => 'Management',
            'phone' => '081234567890',
        ]);

        // Intern accounts
        User::create([
            'name' => 'Ahmad Faisal',
            'email' => 'ahmad@nawatara.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'division' => 'Web Development',
            'phone' => '081234567891',
        ]);

        User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@nawatara.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'division' => 'UI/UX Design',
            'phone' => '081234567892',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@nawatara.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'division' => 'Content Writing',
            'phone' => '081234567893',
        ]);
    }
}
