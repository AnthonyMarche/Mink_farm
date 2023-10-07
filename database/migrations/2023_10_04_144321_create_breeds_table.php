<?php

use Database\Seeders\BreedsTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name')->nullable(false);
            $table->timestamps();

            $table->foreignId('type_id')->constrained('types');
        });

        Artisan::call('db:seed', [
            '--class' => BreedsTableSeeder::class,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breed');
    }
};
