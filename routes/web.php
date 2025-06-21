<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\YearController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RatioController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectorYearController;

// Page d'accueil
Route::get('/', function () {
    return view('accueil');
})->name('dashboard');

// Ressources
Route::resource('year', YearController::class);
Route::resource('promotion', PromotionController::class);
Route::resource('ratio', RatioController::class);
Route::resource('sector', SectorController::class);
Route::resource('classroom', ClassroomController::class);
Route::resource('subject', SubjectController::class);
Route::resource('sectorbyyear', SectorYearController::class);

// Activation/Désactivation année scolaire
Route::get('disable/{year}', [YearController::class, 'disableyear'])->name('disable_year');
Route::get('activate/{year}', [YearController::class, 'activateyear'])->name('activate_year');
Route::get('/sectors/by-year/{year}', [SectorController::class, 'getSectorsByYear'])->name('sectors.byYear');

