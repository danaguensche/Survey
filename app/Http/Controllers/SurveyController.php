<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveySubmission;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'answers'               => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.rating_value' => 'nullable|integer|min:1|max:6',
            'answers.*.text_value'  => 'nullable|string|max:2000',
            'consent'                => 'required|accepted',
        ]);

        $submission = SurveySubmission::create([
            'ausbildungsberuf' => $request->ausbildungsberuf,
            'consent'          => $request->consent,
        ]);

        foreach ($request->answers as $answer) {
            $submission->answers()->create($answer);
        }

        return response()->json(['message' => 'Umfrage gespeichert'], 201);
    }
}
