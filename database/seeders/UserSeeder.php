<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $ptAbc = Company::where('email', 'purchasing@ptabc.com')->first();
        $ptMbl = Company::where('email', 'procurement@mbl.co.id')->first();
        $ptEnp = Company::where('email', 'finance@enprima.com')->first();

        // ── Admin Penjualan (Depo — no company) ─────────────────────────────
        // US 2.1 Main Actor: this user issues invoices
        User::create([
            'company_id'        => null,
            'name'              => 'Budi Santoso',
            'email'             => 'admin@fuelapp.com',
            'role'              => 'admin_penjualan',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Manajer Pembeli — PT ABC ─────────────────────────────────────────
        // Approves orders; must approve before admin can issue invoice
        User::create([
            'company_id'        => $ptAbc->id,
            'name'              => 'Dewi Rahayu',
            'email'             => 'manajer@ptabc.com',
            'role'              => 'manajer_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Staf Pembeli — PT ABC ────────────────────────────────────────────
        // Creates orders
        User::create([
            'company_id'        => $ptAbc->id,
            'name'              => 'Ahmad Fauzi',
            'email'             => 'staf@ptabc.com',
            'role'              => 'staf_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Manajer Pembeli — PT MBL ─────────────────────────────────────────
        User::create([
            'company_id'        => $ptMbl->id,
            'name'              => 'Siti Nurhaliza',
            'email'             => 'manajer@mbl.co.id',
            'role'              => 'manajer_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Staf Pembeli — PT MBL ────────────────────────────────────────────
        User::create([
            'company_id'        => $ptMbl->id,
            'name'              => 'Rizky Pratama',
            'email'             => 'staf@mbl.co.id',
            'role'              => 'staf_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Manajer Pembeli — PT ENP ─────────────────────────────────────────
        User::create([
            'company_id'        => $ptEnp->id,
            'name'              => 'Hendra Gunawan',
            'email'             => 'manajer@enprima.com',
            'role'              => 'manajer_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // ── Staf Pembeli — PT ENP ────────────────────────────────────────────
        User::create([
            'company_id'        => $ptEnp->id,
            'name'              => 'Lestari Wahyu',
            'email'             => 'staf@enprima.com',
            'role'              => 'staf_pembeli',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
