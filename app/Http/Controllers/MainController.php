<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function home(): View
    {
        return view("home");
    }

    /**
     * Gera os exercícios matemáticos e armazena na sessão
     */
    public function generateExercises(Request $request): View
    {
        // Validação do formulário
        $request->validate([
            "check_sum"            => "required_without_all:check_subtraction,check_multiplication,check_division",
            "check_subtraction"    => "required_without_all:check_sum,check_multiplication,check_division",
            "check_multiplication" => "required_without_all:check_sum,check_subtraction,check_division",
            "check_division"       => "required_without_all:check_sum,check_subtraction,check_multiplication",
            "number_one"           => "required|integer|min:1|max:999|lt:number_two",
            "number_two"           => "required|integer|min:1|max:999",
            "number_exercises"     => "required|integer|min:5|max:50",
        ]);

        // Operações selecionadas
        $operations = [];
        if ($request->check_sum)            $operations[] = "sum";
        if ($request->check_subtraction)    $operations[] = "subtraction";
        if ($request->check_multiplication) $operations[] = "multiplication";
        if ($request->check_division)       $operations[] = "division";

        // Números mínimo e máximo
        $min = $request->number_one;
        $max = $request->number_two;

        // Quantidade de exercícios
        $numberExercises = $request->number_exercises;

        // Geração dos exercícios
        $exercises = [];
        for ($i = 1; $i <= $numberExercises; $i++) {
            $exercises[] = $this->generateExercise($i, $operations, $min, $max);
        }

        // Armazena os exercícios na sessão
        session(["exercises" => $exercises]);

        return view("operations", compact("exercises"));
    }

    /**
     * Exibe os exercícios em modo de impressão
     */
    public function printExercises()
    {
        // Verifica se há exercícios salvos na sessão
        if (!session()->has("exercises")) {
            return redirect()->route("home");
        }

        $exercises = session("exercises");

        echo "<pre>";
        echo "<h1>Exercícios de Matemática (" . env("APP_NAME") . ")</h1>";
        echo "<hr>";

        // Exibição dos exercícios
        foreach ($exercises as $exercise) {
            echo "<h2><small>" . $exercise["exercise_number"] . " | </small>" . $exercise["exercise"] . "</h2>";
        }

        // Exibição das soluções
        echo "<hr>";
        echo "<small><strong>Soluções</strong></small><br>";
        foreach ($exercises as $exercise) {
            echo "<small>" . $exercise["exercise_number"] . " | " . $exercise["solution"] . "</small><br>";
        }

        echo "</pre>";
    }

    /**
     * Exporta os exercícios (em construção)
     */
    public function exportExercises()
    {
        // Verifica se há exercícios salvos na sessão
        if (!session()->has("exercises")) {
            return redirect()->route("home");
        }

        // Exportação dos exercícios
        $exercises = session("exercises");
        $filename = "exercicios_" . env("APP_NAME") . "_" . date("YmdHis") . ".txt";

        $content = "Exercícios de Matemática (" . env("APP_NAME") . ")\n";
        $content .= str_repeat("=", 40) . "\n\n";

        foreach ($exercises as $exercise) {
            $content .= $exercise["exercise_number"] . " | " . $exercise["exercise"] . "\n";
        }

        $content .= "\nSoluções\n" . str_repeat("-", 20) . "\n";

        foreach ($exercises as $exercise) {
            $content .= $exercise["exercise_number"] . " | " . $exercise["solution"] . "\n";
        }

        return response($content)
            ->header("Content-Type", "text/plain")
            ->header("Content-Disposition", "attachment; filename=\"{$filename}\"");
    }


    /**
     * Gera um exercício com operação aleatória
     */
    private function generateExercise(int $index, array $operations, int $min, int $max): array
    {
        $operation = $operations[array_rand($operations)];
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        // Evita divisão por zero
        if ($operation === "division" && $number2 === 0) {
            $number2 = rand(1, $max);
        }

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
                $solution = $number2 !== 0 ? round($number1 / $number2, 2) : "indefinido";
                break;

            default:
                $exercise = "";
                $solution = "";
        }

        return [
            "operation"        => $operation,
            "exercise_number"  => $index,
            "exercise"         => $exercise,
            "solution"         => "$exercise $solution"
        ];
    }
}
