<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Change class_level from string to JSON to support multiple class levels
            $table->json('class_levels')->nullable()->after('class_level');
        });
        
        // Migrate existing data
        DB::statement("UPDATE users SET class_levels = JSON_ARRAY(class_level) WHERE class_level IS NOT NULL");
        
        Schema::table('users', function (Blueprint $table) {
            // Remove old single class_level column
            $table->dropColumn('class_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back the old column
            $table->string('class_level')->nullable()->after('class_levels');
        });
        
        // Migrate data back (take first class level)
        DB::statement("UPDATE users SET class_level = JSON_UNQUOTE(JSON_EXTRACT(class_levels, '$[0]')) WHERE class_levels IS NOT NULL");
        
        Schema::table('users', function (Blueprint $table) {
            // Remove JSON column
            $table->dropColumn('class_levels');
        });
    }
};
