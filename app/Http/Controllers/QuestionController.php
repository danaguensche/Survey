<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\Section;

class QuestionController extends Controller
{
    public function index()
    {
        return response()->json(
            SurveyQuestion::orderBy('order_index')->get()
        );
    }

    public function questions() {
        $sections = Section::with('questions')->orderBy('order_index')->get();
    
        return $sections->flatMap(function ($section) {
            return $section->questions->map(fn($q) => [
                'id'            => $q->id,
                'section'       => $section->title,
                'question_text' => $q->question_text,
                'type'          => $q->type,
                'scale_max'     => $q->scale_max,
                'order_index'   => $q->order_index,
            ]);
        });
    }
}
