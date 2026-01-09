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
        Schema::create('free_fall__i_i', function (Blueprint $table) {
            $table->id();
             $table->foreignId('player_id')  // this is the FK column in matches table
          ->constrained('players')->unique();
          $table->integer('score');
            $table->index('player_id'); // speeds up aggregation queries
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_fall__i_i');
    }
};
