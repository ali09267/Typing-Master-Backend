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
        Schema::create('words_stats', function (Blueprint $table) {
            $table->id();
           $table->foreignId('player_id')->constrained('players')->unique();
    $table->integer('total')->default(0);
    $table->decimal('average', 8, 2)->default(0.00);
    $table->integer('high_score')->default(0);
        $table->integer('typing_points')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('words_stats');
    }
};
