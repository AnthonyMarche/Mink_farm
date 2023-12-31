<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('type_id');
            $table->foreignId('breed_id')->constrained('breeds')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('breed_id');
            $table->foreignId('type_id')->constrained('types');
        });
    }
};
