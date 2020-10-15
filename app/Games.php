<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    protected $table = 'game';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id' ,'mdate', 'stadium', 'team1', 'team2',
    ];
}
