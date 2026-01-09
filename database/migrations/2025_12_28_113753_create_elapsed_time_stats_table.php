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
        Schema::create('elapsed_time_stats', function (Blueprint $table) {
                $table->id();
           $table->foreignId('player_id')->constrained('players')->unique();
    $table->decimal('average_seconds', 8, 2)->default(0.00);
    $table->decimal('least_seconds')->default(0);
        $table->decimal('typing_points')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elapsed_time_stats');
    }
};
