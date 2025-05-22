<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // Owner
        User::updateOrCreate(
            ['email' => 'owner@gmail.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('12345678'),
                'role' => 'owner',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // User
        User::updateOrCreate(
            ['email' => 'ipanrossi2@gmail.com'],
            [
                'name' => 'User',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'status' => 1,
                'profile_picture' => null,
                'api_token' => Str::random(60),
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
