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
use App\Http\Controllers\PromotionClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\HomeController;

// Page d'accueil
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('/', [HomeController::class, 'index'])->name('home');

// Ressources principales (CRUD)

Route::resources([
    'year' => YearController::class,
    'promotion' => PromotionController::class,
    'ratio' => RatioController::class,
    'sector' => SectorController::class,
    'classroom' => ClassroomController::class,
    'subject' => SubjectController::class,
    'sectorbyyear' => SectorYearController::class,
    'promotionbysector' => PromotionSectorController::class,
    'student' => StudentController::class,
]);

// Activation / Désactivation
Route::get('disable/{year}', [YearController::class, 'disableyear'])->name('disable_year');
Route::get('activate/{year}', [YearController::class, 'activateyear'])->name('activate_year');
Route::get('disable/{sector}', [SectorController::class, 'disablesector'])->name('disable_sector');
Route::get('activate/{sector}', [SectorController::class, 'activatesector'])->name('activate_sector');

// Requêtes dynamiques / API partagées
Route::get('/sectors/by-year/{year}', [SectorYearController::class, 'getSectorsByYear'])->name('sectors.byYear');
Route::get('/sectors-by-year/{year}', [SectorController::class, 'getSectorsByYear']);
Route::get('/promotions-by-sector/{sector}/year/{year}', [SectorController::class, 'getPromotionsBySectorAndYear']);
Route::get('/promotion-sectors/{year}', [PromotionSectorController::class, 'getSectorsByYear'])->name('promotion-sectors.byYear');

// GESTION DES CLASSES
Route::get('/classes/create', [PromotionClassroomController::class, 'create'])->name('promotion-classrooms.create');
Route::post('/classes/store', [PromotionClassroomController::class, 'store'])->name('promotion-classrooms.store');
Route::get('/api/classroom-sectors-by-year/{yearId}', [PromotionClassroomController::class, 'getSectorsByYear']);
Route::get('/api/classroom-promotions/{yearId}/{sectorId}', [PromotionClassroomController::class, 'getPromotions']);

// GESTION DES MATIÈRES
Route::get('/api/subject-sectors-by-year/{year}', [SubjectController::class, 'getSectorsByYear']);
Route::get('/api/subject-promotions/{yearId}/{sectorId}', [SubjectController::class, 'getPromotionsByYearSector']);
Route::get('/api/promotion-classrooms/{year}/{sector}', [SubjectController::class, 'getClassroomsByYearSector']);
Route::get('/api/old-subjects/{oldYear}/{sector}', [SubjectController::class, 'getOldSubjects']);
Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subject.create');
Route::post('/subjects/store', [SubjectController::class, 'store'])->name('subject.store');

// API Ratios
Route::get('/api/ratios/sectors/{yearId}', [RatioController::class, 'getSectorsByYear']);
Route::get('/api/ratios/promotions/{yearId}/{sectorId}', [RatioController::class, 'getPromotionsByYearAndSector']);
Route::get('/api/ratios/data/{promotionId}/{yearId}', [RatioController::class, 'getSubjectsAndClasses']);
Route::post('/import', [\App\Http\Controllers\StudentController::class, 'import'])->name('import');
Route::get('/students/{year}/{sector}/{promotion}/{classroom}', [StudentController::class, 'getByFilter']);



// Routes API pour chargement dynamique
Route::get('/api/sectors-by-year/{yearId}', [StudentController::class, 'getSectorsByYear']);
Route::get('/api/promotions-by-year-sector/{yearId}/{sectorId}', [StudentController::class, 'getPromotionsByYearSector']);
Route::get('/api/classes-by-promotion/{promotionId}', [StudentController::class, 'getClassesByPromotion']);
