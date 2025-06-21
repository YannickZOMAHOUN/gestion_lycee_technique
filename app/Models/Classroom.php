<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable=[
        'name_classroom',
        'status',
        'promotion_id',
        'year_id',
    ];
   public function promotion() {
        return $this->belongsTo(Promotion::class);
    }
    
    public function year() {
        return $this->belongsTo(Year::class);
    }
}

