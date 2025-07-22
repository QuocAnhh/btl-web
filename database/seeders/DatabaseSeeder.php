<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true
            ]
        );
        
        // Student User
        \App\Models\User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Test Student User',
                'password' => bcrypt('password'),
                'is_admin' => false
            ]
        );

        $this->call([
            MajorSeeder::class,
        ]);
    }
}
