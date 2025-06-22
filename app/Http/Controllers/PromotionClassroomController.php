<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Sector;
use App\Models\SectorYear;
use App\Models\PromotionSector;
use App\Models\PromotionClassroom;

class PromotionClassroomController extends Controller
{
    public function create()
    {
        $years = Year::all();
        return view('dashboard.classrooms.create', compact('years'));
    }

    public function getSectorsByYear($yearId)
    {
        $sectorYears = SectorYear::with('sector')->where('year_id', $yearId)->get();

        $sectors = $sectorYears->map(function ($sy) {
            return [
                'id' => $sy->sector->id,
                'name' => $sy->sector->name_sector
            ];
        })->unique('id')->values();

        return response()->json($sectors);
    }

    public function getPromotions($yearId, $sectorId)
    {
        $sectorYear = SectorYear::where('year_id', $yearId)
            ->where('sector_id', $sectorId)
            ->first();

        if (!$sectorYear) return response()->json([]);

        $promotions = PromotionSector::where('sector_year_id', $sectorYear->id)
            ->pluck('promotion_sector');

        return response()->json($promotions);
    }

  public function store(Request $request)
{
    $request->validate([
        'year_id' => 'required|exists:years,id',
        'sector_id' => 'required|exists:sectors,id',
        'promotions' => 'required|array',
        'counts' => 'required|array',
    ]);

    // Supprimer les anciennes classes pour cette année et cette filière
    PromotionClassroom::where('year_id', $request->year_id)
        ->where('sector_id', $request->sector_id)
        ->delete();

    // Recréer les classes
    foreach ($request->promotions as $index => $promotion) {
        $count = intval($request->counts[$index]);

        if ($count === 1) {
            PromotionClassroom::create([
                'year_id' => $request->year_id,
                'sector_id' => $request->sector_id,
                'name' => $promotion, // pas de suffixe
            ]);
        } elseif ($count > 1) {
            for ($i = 0; $i < $count; $i++) {
                PromotionClassroom::create([
                    'year_id' => $request->year_id,
                    'sector_id' => $request->sector_id,
                    'name' => $promotion . '-' . chr(65 + $i), // A, B, C...
                ]);
            }
        }
    }

    return back()->with('success', 'Classes enregistrées avec succès.');
}


}
