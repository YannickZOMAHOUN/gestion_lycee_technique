<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Recording;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Exception;

class StudentsImport implements ToModel, WithStartRow
{
    protected $classroom_id;
    protected $year_id;

    public function __construct($classroom_id, $year_id)
    {
        $this->classroom_id = $classroom_id;
        $this->year_id = $year_id;
    }

    /**
     * Commencer à la ligne 3 (ignorer les 2 premières lignes)
     */
    public function startRow(): int
    {
        return 3;
    }

    /**
     * Transformer chaque ligne Excel en enregistrement
     */
    public function model(array $row)
    {
        Log::info("Ligne Excel importée : " . json_encode($row));
        // Ignorer si matricule vide
        if (empty($row[1])) {
            Log::warning("Matricule vide ignoré. Ligne Excel : " . json_encode($row));
            return null;
        }

        try {
            // Conversion de la date au bon format
            $birthday = Carbon::createFromFormat('d/m/Y', trim($row[5]))->format('Y-m-d');
        } catch (Exception $e) {
            Log::error("Date invalide : matricule {$row[1]} — {$row[5]}. Erreur : " . $e->getMessage());
            return null;
        }

        try {
            return DB::transaction(function () use ($row, $birthday) {
                // Vérifier si l'étudiant existe déjà par matricule
                $existing = Student::where('matricule', $row[1])->first();

                if ($existing) {
                    Log::info("Étudiant existant ignoré : {$row[1]}");
                    return null;
                }

                // Création de l'étudiant
                $student = Student::create([
                    'matricule'   => trim($row[1]),
                    'name'        => trim($row[2]),
                    'surname'     => trim($row[3]),
                    'sex'         => strtoupper(trim($row[4])),
                    'birthday'    => $birthday,
                    'birthplace'  => trim($row[6]),
                ]);

                // Création de l'enregistrement (classe + année)
                Recording::firstOrCreate([
                    'student_id'    => $student->id,
                    'classroom_id'  => $this->classroom_id,
                    'year_id'       => $this->year_id,
                ]);

                return $student;
            });
        } catch (Exception $e) {
            Log::error("Erreur import : " . json_encode($row) . " — " . $e->getMessage());
            return null;
        }
    }
}
