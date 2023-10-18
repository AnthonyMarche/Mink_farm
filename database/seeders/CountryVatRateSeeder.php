<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryVatRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('country_vat_rates')->insert([
            ['country' => 'France', 'vat_rate' => 1.20],
            ['country' => 'Spain', 'vat_rate' => 1.21],
            ['country' => 'Germany', 'vat_rate' => 1.19],
            ['country' => 'Belgium', 'vat_rate' => 1.21],
            ['country' => 'Italy', 'vat_rate' => 1.22],
        ]);
    }
}
