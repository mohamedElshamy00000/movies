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
        Schema::table('movies', function (Blueprint $table) {
            $table->string('backdrop_path')->nullable();
            $table->text('overview')->nullable();
            $table->decimal('popularity', 8, 3)->nullable();
            $table->string('poster_path')->nullable();
            $table->string('status')->nullable();
            $table->string('tagline')->nullable();
            $table->decimal('vote_average', 5, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            //
        });
    }
};
