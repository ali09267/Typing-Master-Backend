<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score2 extends Model
{
        protected $table = 'free_fall__i_i_stats';

          protected $fillable = [
        'player_id',
        'total',
        'average',
        'high_score',
        'typing_points',
    ];

    public $timestamps = false; // table has no created_at / updated_at

}
