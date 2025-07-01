<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\SectorYear;
use App\Models\PromotionSector;
use App\Models\PromotionClassroom;
use App\Models\Student;
use App\Models\Recording;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    // Affiche la page avec formulaires
    public function create()
    {
        try {
            $years = Year::where('status', true)->get();
            return view('dashboard.students.create', compact('years'));
        } catch (\Exception $e) {
            Log::error("Erreur accès création élèves : " . $e->getMessage());
            abort(404);
        }
    }

    // ✅ Importation des élèves via Laravel Excel
    public function import(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'sector_id' => 'required|exists:sectors,id',
            'promotion_id' => 'required|exists:promotion_sectors,id',
            'classroom_id' => 'required|exists:promotion_classrooms,id',
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new StudentsImport(
                $request->classroom_id,
                $request->year_id
            ), $request->file('file'));

            return back()->with('success', 'Liste des élèves importée avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur import Excel élèves : " . $e->getMessage());
            return back()->withErrors('Erreur lors de l\'importation du fichier.');
        }
    }

    // Ajout manuel d’un élève
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

    // API : Récupérer les filières pour une année donnée
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

    // API : Promotions pour une année + filière
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

    // API : Classes d'une promotion
    public function getClassesByPromotion($promotionId)
    {
        $classes = PromotionClassroom::where('promotion_sector_id', $promotionId)
            ->get(['id', 'name']);

        return response()->json($classes);
    }
}
