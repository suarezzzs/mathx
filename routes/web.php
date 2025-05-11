<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get("/", [MainController::class, "home"])->name("home");
Route::post("/generate-exercises", [MainController::class, "generateExercises"])->name("generateExercises");
Route::get("/print-exercises", [MainController::class, "printExercises"])->name("printExercises");
Route::get("/export-exercises", [MainController::class, "exportExercises"])->name("exportExercises");
