<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::post('/survey', [SurveyController::class, 'store']);
Route::get('/survey/questions', [QuestionController::class, 'index']);
Route::get('/survey/sections', [QuestionController::class, 'questions']);
