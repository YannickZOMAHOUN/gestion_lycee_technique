<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionClassroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'year_id',
        'sector_id',
        'name',
    ];
}
