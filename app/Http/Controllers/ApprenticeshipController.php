<?php

namespace App\Http\Controllers;

use App\Models\Apprenticeship;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprenticeshipController extends Controller
{
    public function index()
    {
        $apprenticeships = Apprenticeship::all();
        return response()->json($apprenticeships);
    }
}
