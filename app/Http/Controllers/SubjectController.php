<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\SectorYear;
use App\Models\Subject;
use App\Models\PromotionSector;
use App\Models\PromotionSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    public function create()
    {
        $years = Year::orderByDesc('year')->get();
        $allSubjects = Subject::orderBy('name')->get();
        return view('dashboard.subjects.create', compact('years', 'allSubjects'));
    }

    public function getSectorsByYear($yearId)
    {
        $sectors = SectorYear::with('sector')
            ->where('year_id', $yearId)
            ->get()
            ->map(function ($sy) {
                return [
                    'id' => $sy->sector->id,
                    'name' => $sy->sector->name_sector
                ];
            });

        return response()->json($sectors);
    }

    public function getPromotionsByYearSector($yearId, $sectorId)
    {
        $sectorYear = SectorYear::where('year_id', $yearId)
            ->where('sector_id', $sectorId)
            ->first();

        if (!$sectorYear) return response()->json([]);

        $promotions = PromotionSector::where('sector_year_id', $sectorYear->id)
            ->get(['id', 'promotion_sector']);

        return response()->json($promotions);
    }

    public function getOldSubjects($oldYearId, $sectorId)
    {
        $sectorYear = SectorYear::where('year_id', $oldYearId)
            ->where('sector_id', $sectorId)->first();

        if (!$sectorYear) return response()->json([]);

        $promotions = PromotionSector::where('sector_year_id', $sectorYear->id)->pluck('id');

        $subjects = PromotionSubject::whereIn('promotion_sector_id', $promotions)
            ->with('subject', 'promotion')
            ->get()
            ->groupBy('promotion_sector_id')
            ->map(function ($items) {
                return [
                    'promotion' => $items[0]->promotion->promotion_sector,
                    'subjects' => $items->pluck('subject.name')->toArray()
                ];
            });

        return response()->json($subjects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'sector_id' => 'required|exists:sectors,id',
            'promotion_ids' => 'required|array',
            'subjects_by_promotion' => 'required|array',
        ]);

        foreach ($request->promotion_ids as $index => $promotionId) {
            $subjectNames = array_map('trim', explode(';', $request->subjects_by_promotion[$index]));

            foreach ($subjectNames as $name) {
                $subject = Subject::firstOrCreate(['name' => $name]);

                PromotionSubject::firstOrCreate([
                    'promotion_sector_id' => $promotionId,
                    'subject_id' => $subject->id,
                ]);
            }
        }

        return back()->with('success', 'Matières enregistrées pour les promotions.');
    }
}
