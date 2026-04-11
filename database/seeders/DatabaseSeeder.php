<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,  // 1. Companies first (users has FK → companies)
            UserSeeder::class,     // 2. Users (needs companies)
            OrderSeeder::class,    // 3. Orders + Invoices (needs users + companies)
        ]);
    }
}
