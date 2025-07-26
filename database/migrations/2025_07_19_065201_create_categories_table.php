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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_mm')->nullable(); // Myanmar translation
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_mm')->nullable(); // Myanmar translation
            $table->string('class_level'); // Grade/Class level (e.g., 'Grade 1', 'Grade 2', etc.)
            $table->string('color', 7)->default('#007bff'); // Hex color for UI
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
