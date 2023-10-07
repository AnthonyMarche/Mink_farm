<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert([
            ['name' => 'Chien', 'created_at' => new DateTime()],
            ['name' => 'Cheval', 'created_at' => new DateTime()],
            ['name' => 'Brebis', 'created_at' => new DateTime()],
            ['name' => 'Cochon', 'created_at' => new DateTime()],
        ]);
    }
}
