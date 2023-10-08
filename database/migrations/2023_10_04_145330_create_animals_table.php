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
        Schema::create('animals', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name')->nullable(false);
            $table->integer('age')->nullable(false);
            $table->text('description')->nullable();
            $table->decimal('price_ht', 10)->nullable(false);
            $table->boolean('sale_status')->default(false);
            $table->timestamps();
            $table->foreignId('type_id')->constrained('types')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
