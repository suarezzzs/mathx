<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
        return view("home");
    }
    public function generateExercises(Request $request)
    {
        echo "Welcome to the Generate Exercises page!";
    }
    public function printExercises()
    {
        echo "Welcome to the Print Exercises page!";
    }
    public function exportExercises()
    {
        echo "Welcome to the Export Exercises page!";
    }
}
