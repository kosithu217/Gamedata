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
        Schema::table('games', function (Blueprint $table) {
            $table->enum('game_type', ['swf', 'iframe'])->default('swf')->after('swf_file_path');
            $table->text('iframe_url')->nullable()->after('game_type');
            $table->text('iframe_code')->nullable()->after('iframe_url');
            
            // Make swf_file_path nullable since iframe games won't need it
            $table->string('swf_file_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['game_type', 'iframe_url', 'iframe_code']);
            
            // Revert swf_file_path to not nullable (if needed)
            $table->string('swf_file_path')->nullable(false)->change();
        });
    }
};
