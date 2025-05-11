<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
        return view("home");
    }

    public function generateExercises(Request $request): View
    {
        // Form validation
        $request->validate([
            "check_sum" => "required_without_all:check_subtraction,check_multiplication,check_division",
            "check_subtraction" => "required_without_all:check_sum,check_multiplication,check_division",
            "check_multiplication" => "required_without_all:check_sum,check_subtraction,check_division",
            "check_division" => "required_without_all:check_sum,check_subtraction,check_multiplication",
            "number_one" => "required|integer|min:1|max:999|lt:number_two",
            "number_two" => "required|integer|min:1|max:999",
            "number_exercises" => "required|integer|min:5|max:50",
        ]);

        // Get selected operations
        $operations = [];

        if ($request->check_sum) { $operations[] = "sum"; }
        if ($request->check_subtraction) {$operations[] = "subtraction";}
        if ($request->check_multiplication) {$operations[] = "multiplication";}
        if ($request->check_division) {$operations[] = "division";}

        // Get numbers (min and max)
        $min = $request->number_one;
        $max = $request->number_two;

        // Get number of exercises
        $numberExercises = $request->number_exercises;

        // Generate exercises
        $exercises = [];

        for ($index = 1; $index <= $numberExercises; $index++) {
            $operation = $operations[array_rand($operations)];
            $number1 = rand($min, $max);
            $number2 = rand($min, $max);

            // Evita divisão por zero
            if ($operation === "division" && $number2 === 0) {
                $number2 = rand(1, $max);
            }

            $exercise = "";
            $solution = "";

            switch ($operation) {
                case "sum":
                    $exercise = "$number1 + $number2 =";
                    $solution = $number1 + $number2;
                    break;
                case "subtraction":
                    $exercise = "$number1 - $number2 =";
                    $solution = $number1 - $number2;
                    break;
                case "multiplication":
                    $exercise = "$number1 X $number2 =";
                    $solution = $number1 * $number2;
                    break;
                case "division":
                    $exercise = "$number1 : $number2 =";
                    $solution = $number2 !== 0 ? round($number1 / $number2, 2) : "undefined";
                    break;
            }

            $exercises[] = [
                "$operation" => $operation,
                "exercise_number" => $index,
                "exercise" => $exercise,
                "solution" => "$exercise $solution"
            ];
        }

        return view("operations", ["exercises" => $exercises]);
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
