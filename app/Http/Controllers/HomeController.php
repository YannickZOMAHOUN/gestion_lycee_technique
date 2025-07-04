<?php

namespace App\Http\Controllers;

use App\Models\Year;
use App\Models\Sector;
use App\Models\Student;
use App\Models\PromotionClassroom;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $activeYear = Year::where('status', true)->first();
        $totalStudents = Student::count();
        $totalSectors = Sector::count();
        $totalClasses = PromotionClassroom::count();
        $currentUser = Auth::user();
        return view('accueil', [
            'activeYear' => $activeYear,
            'totalStudents' => $totalStudents,
            'totalSectors' => $totalSectors,
            'totalClasses' => $totalClasses,
            'currentUser' => $currentUser,
        ]);
    }
}
