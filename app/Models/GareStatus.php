<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GareStatus extends Model
{

    protected $fillable = [ 
        'parcours_id',
        'gare_id',
        'is_active',
    ];

    public $timestamps = false;
}
