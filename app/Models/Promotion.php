<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable=[
        'name_promotion',
        'status',
        'sector_id',
    ];
    public function sector() {
        return $this->belongsTo(Sector::class);
    }
    
    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }
    
}
