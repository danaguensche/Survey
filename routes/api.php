<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::post('/survey', [SurveyController::class, 'store']);
Route::get('/survey/questions', [SurveyController::class, 'questions']);
