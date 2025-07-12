<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_promotion',
        'status',
        'sector_id',
    ];

    // 🔁 Relation avec la filière
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    // 🔁 Classes associées à cette promotion (si gérées directement)
    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    // 🔁 Relation vers les associations PromotionSector (par année et secteur)
    public function promotionSectors()
    {
        return $this->hasMany(PromotionSector::class);
    }
}
