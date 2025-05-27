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
            // Remove the old 'genre' string column
            $table->dropColumn('genre');

            // Add the new 'genre_id' foreign key
            $table->unsignedBigInteger('genre_id')->nullable()->after('user_id');

            $table->foreign('genre_id')
                ->references('id')->on('genres')
                ->onDelete('set null'); // Optional: you can use cascade or restrict as needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
            $table->string('genre')->nullable();
        });
    }
};
