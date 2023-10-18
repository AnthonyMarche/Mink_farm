<?php

use Database\Seeders\AnimalTableSeeder;
use Database\Seeders\CountryVatRateSeeder;
use Database\Seeders\UserSeeder;
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
        Schema::create('country_vat_rates', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable(false);
            $table->float('vat_rate')->nullable(false);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('country_vat_rate_id')->constrained('country_vat_rates');
        });

        Artisan::call('db:seed', ['--class' => CountryVatRateSeeder::class]);
        Artisan::call('db:seed', ['--class' => UserSeeder::class]);
        Artisan::call('db:seed', ['--class' => AnimalTableSeeder::class]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('country_vat_rates_id');
        });
        Schema::dropIfExists('country_vat_rates');
    }
};
