<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionSector extends Model
{
    use HasFactory;

    protected $fillable = ['sector_year_id', 'promotion'];

    public function sectorYear()
    {
        return $this->belongsTo(SectorYear::class);
    }

    // Facilité d’accès à l’année et à la filière directement :
    public function year()
    {
        return $this->sectorYear->year();
    }

    public function sector()
    {
        return $this->sectorYear->sector();
    }
}

