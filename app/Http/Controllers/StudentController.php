<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\SectorYear;
use App\Models\PromotionSector;
use App\Models\PromotionClassroom;
use App\Models\Student;
use App\Models\Recording; // table pour inscrire élève/année/classe
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // Affiche la page avec formulaires
    public function create()
    {
        try {
            $years = Year::where('status', true)->get();
            // On ne charge pas encore secteurs, promotions, classes, ce sera via AJAX
            return view('dashboard.students.create', compact('years'));
        } catch (\Exception $e) {
            Log::error("Erreur accès création élèves : " . $e->getMessage());
            abort(404);
        }
    }

    // Importation des élèves via fichier
    public function import(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'sector_id' => 'required|exists:sectors,id',
            'promotion_id' => 'required|exists:promotion_sectors,id',
            'classroom_id' => 'required|exists:promotion_classrooms,id',
            'file' => 'required|file|mimes:csv,xlsx,xls',
        ]);

        // Traitement fichier (exemple simplifié)
        $path = $request->file('file')->getRealPath();

        // Lecture fichier, exemple CSV - adapter selon format
        $data = array_map('str_getcsv', file($path));
        // Supposons que la première ligne est l'entête
        $header = array_map('strtolower', $data[0]);
        unset($data[0]);

        DB::beginTransaction();

        try {
            foreach ($data as $row) {
                $row = array_combine($header, $row);

                // Création ou récupération élève par matricule
                $student = Student::firstOrCreate(
                    ['matricule' => $row['matricule']],
                    [
                        'name' => $row['name'],
                        'surname' => $row['surname'],
                        'sex' => $row['sex'],
                        'birthday' => $row['birthday'],
                        'birthplace' => $row['birthplace'],
                    ]
                );

                // Inscription dans la classe + année
                Recording::updateOrCreate([
                    'student_id' => $student->id,
                    'year_id' => $request->year_id,
                    'classroom_id' => $request->classroom_id,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Liste des élèves importée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur import élèves : " . $e->getMessage());
            return back()->withErrors('Erreur lors de l\'importation du fichier.');
        }
    }

    // Ajout manuel d'un élève
    public function store(Request $request)
    {
        $rules = [
            'year_id' => 'required|exists:years,id',
            'classroom_id' => 'required|exists:promotion_classrooms,id',
            'matricule' => 'required|unique:students,matricule',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'required|in:M,F',
            'birthday' => 'required|date',
            'birthplace' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $student = Student::create([
                'matricule' => $request->matricule,
                'name' => $request->name,
                'surname' => $request->surname,
                'sex' => $request->sex,
                'birthday' => $request->birthday,
                'birthplace' => $request->birthplace,
            ]);

            Recording::create([
                'student_id' => $student->id,
                'year_id' => $request->year_id,
                'classroom_id' => $request->classroom_id,
            ]);

            DB::commit();
            return back()->with('success', 'Élève ajouté avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur ajout élève : " . $e->getMessage());
            return back()->withErrors('Erreur lors de l\'ajout de l\'élève.');
        }
    }

    // API : Récupérer les filières (sectors) pour une année donnée
    public function getSectorsByYear($yearId)
    {
        $sectorYears = SectorYear::with('sector')
            ->where('year_id', $yearId)
            ->get();

        $sectors = $sectorYears->map(fn($sy) => [
            'id' => $sy->sector->id,
            'name' => $sy->sector->name_sector,
        ]);

        return response()->json($sectors);
    }

    // API : Récupérer les promotions pour une année + filière
    public function getPromotionsByYearSector($yearId, $sectorId)
    {
        $sectorYear = SectorYear::where('year_id', $yearId)
            ->where('sector_id', $sectorId)
            ->first();

        if (!$sectorYear) return response()->json([]);

        $promotions = PromotionSector::where('sector_year_id', $sectorYear->id)
            ->get(['id', 'promotion_sector as name']);

        return response()->json($promotions);
    }

    // API : Récupérer les classes pour une promotion
    public function getClassesByPromotion($promotionId)
    {
        $classes = PromotionClassroom::where('promotion_sector_id', $promotionId)
            ->get(['id', 'classroom as name']);

        return response()->json($classes);
    }
}
