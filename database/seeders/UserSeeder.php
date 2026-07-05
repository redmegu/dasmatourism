<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrator
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@dasmarinas.gov.ph',
            'password' => Hash::make('password'),
            'role' => 'administrator',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'phone' => '0919-123-4567',
            'address' => 'City Hall, Dasmariñas, Cavite',
        ]);

        // Business Owner 1
        $businessOwner1 = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@business.com',
            'password' => Hash::make('password'),
            'role' => 'business_owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $businessOwner1->id,
            'phone' => '0919-234-5678',
            'address' => 'Dasmariñas, Cavite',
        ]);

        // Business Owner 2
        $businessOwner2 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@business.com',
            'password' => Hash::make('password'),
            'role' => 'business_owner',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $businessOwner2->id,
            'phone' => '0919-345-6789',
            'address' => 'Dasmariñas, Cavite',
        ]);

        // Regular Users
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '0919-' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'address' => 'Dasmariñas, Cavite',
            ]);
        }
    }
}
