<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil UserSeeder DULUAN agar user sudah ada sebelum Product dibuat
        $this->call([
            UserSeeder::class,     
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}