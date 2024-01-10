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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name');
            $table->string('commercial_name');
            $table->string('manufacture_company');
            $table->double('available_quantity');
            $table->string('expiration_date');
            $table->double('price');
            $table->string('photo');
           // $table->boolean('favorite')->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
