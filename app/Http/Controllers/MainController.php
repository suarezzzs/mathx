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
        // Form validation
        $request->validate([
            "check_sum" => "required_without_all:check_subtraction,check_multiplication,check_division",
            "check_subtraction" => "required_without_all:check_sum,check_multiplication,check_division",
            "check_multiplication" => "required_without_all:check_sum,check_subtraction,check_division",
            "check_division" => "required_without_all:check_sum,check_subtraction,check_multiplication",
            "number_one" => "required|integer|min:1|max:999",
            "number_two" => "required|integer|min:1|max:999",
            "number_exercises" => "required|integer|min:5|max:50",
        ]);

        dd($request->all());
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
