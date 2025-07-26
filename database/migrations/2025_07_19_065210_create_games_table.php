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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_mm')->nullable(); // Myanmar translation
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('description_mm')->nullable(); // Myanmar translation
            $table->string('swf_file_path'); // Path to uploaded SWF file
            $table->string('thumbnail')->nullable(); // Game thumbnail image
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('width')->default(800); // Game width
            $table->integer('height')->default(600); // Game height
            $table->integer('plays_count')->default(0);
            $table->boolean('is_featured')->default(false);
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
        Schema::dropIfExists('games');
    }
};
