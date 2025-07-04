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
    protected $matriculesImportes = [];

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
        Log::info("Ligne Excel reçue : " . json_encode($row));

        // Ignorer si matricule vide
        if (empty($row[1])) {
            Log::warning("Matricule vide ignoré. Ligne Excel : " . json_encode($row));
            return null;
        }

        // Normaliser sexe (M ou F)
        $sex = strtoupper(substr(trim($row[4]), 0, 1));

        try {
            return DB::transaction(function () use ($row, $sex) {
                $matricule = trim($row[1]);

                // Vérifier si l'étudiant existe déjà
                $existingStudent = Student::where('matricule', $matricule)->first();

                if ($existingStudent) {
                    // Vérifier s'il est déjà enregistré dans cette classe + année
                    $alreadyRecorded = Recording::where([
                        'student_id'   => $existingStudent->id,
                        'classroom_id' => $this->classroom_id,
                        'year_id'      => $this->year_id,
                    ])->exists();

                    if ($alreadyRecorded) {
                        Log::info("Étudiant et enregistrement déjà existants ignorés : {$matricule}");
                        return null;
                    } else {
                        // Créer l'enregistrement car étudiant existe mais pas inscrit dans cette classe/année
                        Recording::create([
                            'student_id'   => $existingStudent->id,
                            'classroom_id' => $this->classroom_id,
                            'year_id'      => $this->year_id,
                        ]);
                        Log::info("Enregistrement ajouté à l'étudiant existant : {$matricule}");
                        $this->matriculesImportes[] = $matricule;
                        return $existingStudent;
                    }
                }

                // Convertir la date de naissance du format jj/mm/aaaa vers Y-m-d
                $birthday = $this->convertExcelDate($row[5]);

                // Créer un nouvel étudiant avec la date convertie
                $student = Student::create([
                    'matricule'   => $matricule,
                    'name'        => trim($row[2]),
                    'surname'     => trim($row[3]),
                    'sex'         => $sex,
                    'birthday'    => $birthday,
                    'birthplace'  => trim($row[6]),
                ]);

                // Créer l'enregistrement (classe + année)
                Recording::create([
                    'student_id'   => $student->id,
                    'classroom_id' => $this->classroom_id,
                    'year_id'      => $this->year_id,
                ]);

                Log::info("Nouvel étudiant importé : {$matricule}");
                $this->matriculesImportes[] = $matricule;

                return $student;
            });
        } catch (Exception $e) {
            Log::error("Erreur import : " . json_encode($row) . " — " . $e->getMessage());
            return null;
        }
    }

    /**
     * Convertir la date Excel du format jj/mm/aaaa vers Y-m-d
     */
    protected function convertExcelDate($dateValue)
    {
        try {
            // Si c'est déjà un objet Carbon (cas où Excel a auto-détecté le format)
            if ($dateValue instanceof \DateTime) {
                return Carbon::instance($dateValue)->format('Y-m-d');
            }

            // Si c'est une chaîne au format jj/mm/aaaa
            if (is_string($dateValue) && preg_match('#^\d{2}/\d{2}/\d{4}$#', $dateValue)) {
                return Carbon::createFromFormat('d/m/Y', $dateValue)->format('Y-m-d');
            }

            // Si c'est un nombre (timestamp Excel)
            if (is_numeric($dateValue)) {
                return Carbon::createFromTimestamp((int) (($dateValue - 25569) * 86400))->format('Y-m-d');
            }

            // Par défaut, retourner une date fixe ou lancer une exception
            throw new \Exception("Format de date non reconnu");
        } catch (\Exception $e) {
            Log::warning("Erreur conversion date '{$dateValue}': " . $e->getMessage() . " - Utilisation de la date par défaut");
            return '2001-01-01'; // Date par défaut en cas d'erreur
        }
    }

    /**
     * Optionnel : à appeler après l'import pour afficher un résumé
     */
    public function logSummary()
    {
        Log::info("Import terminé. Matricules importés : " . implode(', ', $this->matriculesImportes));
    }
}
