<?php

use Database\Seeders\AnimalTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('image_path')->nullable();
        });

        Artisan::call('db:seed', [
            '--class' => AnimalTableSeeder::class,
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            //
        });
    }
};