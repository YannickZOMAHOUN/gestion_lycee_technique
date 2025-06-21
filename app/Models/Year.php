<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class year extends Model
{
    protected $fillable=[
        'year',
        'status',
    ];
    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }
}
