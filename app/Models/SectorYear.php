<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectorYear extends Model
{
    protected $fillable=[
        'year_id',
        'sector_id',
    ];
    public function sector() {
        return $this->belongsTo(Sector::class);
    }
    public function year() {
        return $this->belongsTo(Year::class);
    }
}
