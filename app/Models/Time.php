<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
        protected $table = 'elapsed_time_stats';

          protected $fillable = [
        'player_id',
        'average_seconds',
        'least_score',
        'typing_points',
    ];

    public $timestamps = false; // table has no created_at / updated_at

}
