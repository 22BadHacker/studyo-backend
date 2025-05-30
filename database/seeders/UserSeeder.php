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
        // // Create 10 artists
        // User::factory()->count(10)->state(['role' => 'artist'])->create();

        // // Create 20 regular users
        // User::factory()->count(20)->state(['role' => 'user'])->create();

        User::factory(30)->create();

        // Optional: 1 admin
        User::create([
            'public_id' => 'admin1',
            'username' => 'AdminMaster',
            'email' => 'user@example.com',
            'password' => bcrypt('adminpass'),
            'role' => 'admin',
            'profile_image' => 'https://via.placeholder.com/150',
            'bio' => 'Site admin.',
            'email_verified_at' => now(),
        ]);
    }
}
