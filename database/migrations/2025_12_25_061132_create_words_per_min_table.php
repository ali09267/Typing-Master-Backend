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
        Schema::create('words_per_min', function (Blueprint $table) {
            $table->id();
             $table->foreignId('player_id')  // this is the FK column in matches table
          ->constrained('players')->unique();
            $table->string('total_words');
            $table->index('player_id'); // speeds up aggregation queries

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words_per_min');
    }
};
