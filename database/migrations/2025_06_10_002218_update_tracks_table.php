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
        Schema::table('tracks', function (Blueprint $table) {
              // Remove columns
            $table->dropColumn(['artist', 'album']);

            // Add new columns
            $table->foreignId('album_id')->nullable()->constrained()->onDelete('set null');
            $table->string('track_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
             $table->dropForeign(['album_id']);
            $table->dropColumn(['album_id', 'track_number']);

            // Re-add old columns
            $table->string('artist');
            $table->string('album')->nullable();
        });
    }
};
