<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
        protected $table = 'words_stats';

          protected $fillable = [
        'player_id',
        'total',
        'average',
        'high_score',
                'typing_points',
    ];

    public $timestamps = false; // table has no created_at / updated_at

}
