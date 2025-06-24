<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ratio extends Model
{
    protected $fillable = [
        'year_id', 'promotion_sector_id', 'classroom_id', 'subject_id', 'coefficient'
    ];
}
