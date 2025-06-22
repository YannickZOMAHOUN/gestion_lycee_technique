<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RatioController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectorYearController;
use App\Http\Controllers\PromotionSectorController;

/*
|--------------------------------------------------------------------------
| Routes Web
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', fn () => view('accueil'))->name('dashboard');

// 📁 Ressources principales
Route::resources([
    'year' => YearController::class,
    'promotion' => PromotionController::class,
    'ratio' => RatioController::class,
    'sector' => SectorController::class,
    'classroom' => ClassroomController::class,
    'subject' => SubjectController::class,
    'sectorbyyear' => SectorYearController::class,
    'promotionbysector' => PromotionSectorController::class,
]);


// 🔁 Activation / Désactivation
Route::get('disable/{year}', [YearController::class, 'disableyear'])->name('disable_year');
Route::get('activate/{year}', [YearController::class, 'activateyear'])->name('activate_year');

Route::get('disable/{sector}', [SectorController::class, 'disablesector'])->name('disable_sector');
Route::get('activate/{sector}', [SectorController::class, 'activatesector'])->name('activate_sector');


// 🔎 Requêtes dynamiques / API
Route::get('/sectors/by-year/{year}', [SectorYearController::class, 'getSectorsByYear'])->name('sectors.byYear');
Route::get('/sectors-by-year/{year}', [SectorController::class, 'getSectorsByYear']);
Route::get('/promotions-by-sector/{sector}/year/{year}', [SectorController::class, 'getPromotionsBySectorAndYear']);
Route::get('/promotion-sectors/{year}', [PromotionSectorController::class, 'getSectorsByYear'])->name('promotion-sectors.byYear');


use App\Http\Controllers\PromotionClassroomController;

// Affichage du formulaire
Route::get('/classes/create', [PromotionClassroomController::class, 'create'])->name('promotion-classrooms.create');

// Récupération des filières par année (AJAX)
Route::get('/api/sectors-by-year/{yearId}', [PromotionClassroomController::class, 'getSectorsByYear']);

// Récupération des promotions (AJAX)
Route::get('/api/promotions/{yearId}/{sectorId}', [PromotionClassroomController::class, 'getPromotions']);

// Enregistrement des classes
Route::post('/classes/store', [PromotionClassroomController::class, 'store'])->name('promotion-classrooms.store');

Route::get('/api/sectors-by-year/{year}', [SubjectController::class, 'getSectorsByYear']);
Route::get('/api/promotion-classrooms/{year}/{sector}', [SubjectController::class, 'getClassroomsByYearSector']);
Route::get('/api/old-subjects/{oldYear}/{sector}', [SubjectController::class, 'getOldSubjects']);
