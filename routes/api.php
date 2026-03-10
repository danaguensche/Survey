<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EvaluationController;

// routes/api.php
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Auswertungs-Route schützen
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/survey/results', [SurveyController::class, 'results']);
});

Route::post('/survey', [SurveyController::class, 'store']);
Route::get('/survey/questions', [QuestionController::class, 'index']);
Route::get('/survey/sections', [QuestionController::class, 'questions']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/survey/results', [EvaluationController::class, 'index']);
});
