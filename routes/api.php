<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ApprenticeshipController;
use Illuminate\Http\Request;

// Auth
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Öffentliche Routen
Route::post('/survey', [SurveyController::class, 'store']);
Route::get('/survey/questions', [QuestionController::class, 'index']);
Route::get('/survey/sections', [QuestionController::class, 'questions']);
Route::get('/apprenticeships', [ApprenticeshipController::class, 'index']);

// Geschützte Routen
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/survey/results', [EvaluationController::class, 'index']);
});
