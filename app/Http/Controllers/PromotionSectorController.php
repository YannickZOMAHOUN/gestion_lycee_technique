<?php
namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Sector;
use App\Models\SectorYear;
use App\Models\PromotionSector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PromotionSectorController extends Controller
{
    public function create()
    {
        $years = Year::where('status', true)->get();
        return view('dashboard.promotions.create', compact('years'));
    }

    public function getSectorsByYear($year)
    {
        try {
            
            Log::info("Année sélectionnée : {$year}");
            $sectors = SectorYear::where('year_id', $year)
                ->with('sector') // Relation vers `sectors`
                ->get()
                ->map(function ($sy) {
                    return [
                        'id' => $sy->id,
                        'name_sector' => $sy->sector->name_sector,
                    ];
                });

            $registered = PromotionSector::whereHas('sectorYear', fn($q) => $q->where('year_id', $year))
                ->get()
                ->pluck('promotion_sector')
                ->toArray();

            return response()->json([
                'sectors' => $sectors,
                'registeredPromotions' => $registered
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Erreur serveur.'], 500);
        }
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|exists:years,id',
            'promotions' => 'nullable|array',
        ]);
    
        try {
            // Supprimer les promotions déjà enregistrées pour cette année
            PromotionSector::where('year_id', $request->year)->delete();
    
            $data = [];
    
            if (!empty($request->promotions)) {
                $data = collect($request->promotions)->map(function ($value) use ($request) {
                    return [
                        'year_id' => $request->year,
                        'promotion_sector' => $value, 
                        
                    ];
                })->toArray();
    
                PromotionSector::insert($data);
            }
    
            // Log d'information
            Log::info("Enregistrement des promotions pour l'année ID {$request->year}", [
                'promotions' => $request->promotions ?? [],
                'total' => count($data),
            ]);
    
            return back()->with('success', 'Promotions enregistrées avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur à l'enregistrement des promotions : " . $e->getMessage());
            return back()->with('error', 'Erreur lors de l’enregistrement.');
        }
    }
    
    
}
