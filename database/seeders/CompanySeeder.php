<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Named companies used by UserSeeder
        Company::create([
            'name'    => 'PT Abadi Coal Resources',
            'email'   => 'purchasing@ptabc.com',
            'phone'   => '02112345678',
            'address' => 'Jl. Sudirman No.1, Jakarta Pusat, DKI Jakarta',
            'npwp'    => '01.234.567.8-001.000',
        ]);

        Company::create([
            'name'    => 'PT Maju Bersama Logistics',
            'email'   => 'procurement@mbl.co.id',
            'phone'   => '02198765432',
            'address' => 'Jl. Gatot Subroto Kav.15, Jakarta Selatan',
            'npwp'    => '02.345.678.9-002.000',
        ]);

        Company::create([
            'name'    => 'PT Energi Nusantara Prima',
            'email'   => 'finance@enprima.com',
            'phone'   => '02155551234',
            'address' => 'Kawasan Industri MM2100 Blok A-5, Bekasi',
            'npwp'    => '03.456.789.0-003.000',
        ]);

        // Extra random companies for richer data
        Company::factory(4)->create();
    }
}
