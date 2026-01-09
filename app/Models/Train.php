<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
        protected $table = 'train';

          protected $fillable = [
        'player_id',
        'level_reached',
        'avg_time',
        'typing_points',
    ];

    public $timestamps = false; // table has no created_at / updated_at

}
