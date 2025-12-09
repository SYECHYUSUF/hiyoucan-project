<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun ADMIN
        User::firstOrCreate(
            ['email' => 'admin@hiyoucan.com'], 
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), 
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun SELLER (Sudah Approved)
        User::firstOrCreate(
            ['email' => 'seller@hiyoucan.com'],
            [
                'name' => 'Official Seller',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(), // Langsung verified agar bisa login dashboard
            ]
        );

        // 3. Akun SELLER (Pending / Belum Approved)
        User::firstOrCreate(
            ['email' => 'newseller@hiyoucan.com'],
            [
                'name' => 'New Seller (Pending)',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => null, // Null artinya belum diapprove admin
            ]
        );

        // 4. Akun BUYER (Customer)
        User::firstOrCreate(    
            ['email' => 'buyer@hiyoucan.com'],
            [
                'name' => 'Lovely Customer',
                'password' => Hash::make('password'),
                'role' => 'user', // Sesuai logika AdminController ('user' = Buyer)
                'email_verified_at' => now(),
            ]
        );
    }
}