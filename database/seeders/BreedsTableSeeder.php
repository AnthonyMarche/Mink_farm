<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BreedsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('breeds')->insert([
            ['name' => 'Labrador', 'type_id' => 1, 'created_at' => new DateTime()],
            ['name' => 'Frison', 'type_id' => 2, 'created_at' => new DateTime()],
            ['name' => 'Pottok', 'type_id' => 2, 'created_at' => new DateTime()],
            ['name' => 'Irish Cob', 'type_id' => 2, 'created_at' => new DateTime()],
            ['name' => 'Mérinos', 'type_id' => 3, 'created_at' => new DateTime()],
            ['name' => 'Solognotes', 'type_id' => 3, 'created_at' => new DateTime()],
        ]);
    }
}
