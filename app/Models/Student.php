<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'name',
        'surname',
        'sex',
        'birthday',
        'birthplace',
    ];

    /**
     * Un élève peut avoir plusieurs enregistrements (s'il change de classe ou d'année).
     */
    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    /**
     * Classe actuelle de l'élève (la plus récente par année si besoin).
     */
    public function currentClassroom()
    {
        return $this->hasOneThrough(
            PromotionClassroom::class,
            Recording::class,
            'student_id',      // Foreign key on recordings
            'id',              // Foreign key on classrooms
            'id',              // Local key on student
            'classroom_id'     // Local key on recordings
        );
    }
}
