<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use Illuminate\Http\Request;
use App\Models\Section;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $beruf = $request->query('beruf');
        $jahr  = $request->query('jahr');

        $submissions = SurveySubmission::query()
            ->when($beruf, fn($q) => $q->where('ausbildungsberuf', $beruf))
            ->when($jahr,  fn($q) => $q->where('ausbildungsjahr', $jahr))
            ->with('answers')
            ->get();

        $total      = $submissions->count();
        $allAnswers = $submissions->flatMap->answers;

        // Rating-Sections
        $sections = Section::orderBy('order_index')
            ->with(['questions' => fn($q) => $q->where('type', 'rating')->orderBy('order_index')])
            ->get()
            ->map(function ($section) use ($allAnswers) {
                return [
                    'title'     => $section->title,
                    'questions' => $section->questions->map(function ($q) use ($allAnswers) {
                        $vals = $allAnswers
                            ->where('question_id', $q->id)
                            ->whereNotNull('rating_value')
                            ->pluck('rating_value')
                            ->map(fn($v) => (int) $v);

                        $count = $vals->count();
                        $dist  = array_fill(0, $q->scale_max, 0);
                        foreach ($vals as $v) {
                            if ($v >= 1 && $v <= $q->scale_max) $dist[$v - 1]++;
                        }

                        return [
                            'id'            => $q->id,
                            'question_text' => $q->question_text,
                            'type'          => 'rating',
                            'scale_max'     => $q->scale_max,
                            'average'       => $count > 0 ? round($vals->avg(), 2) : 0,
                            'count'         => $count,
                            'distribution'  => array_values($dist),
                        ];
                    })->values(),
                ];
            });


        // Freitextantworten
        $textAnswers = SurveyQuestion::where('type', 'text')
            ->orderBy('order_index')
            ->get()
            ->map(fn($q) => [
                'question_text' => $q->question_text,
                'answers'       => $allAnswers
                    ->where('question_id', $q->id)
                    ->whereNotNull('text_value')
                    ->pluck('text_value')
                    ->filter()
                    ->values(),
            ]);

        // Meta (ungefiltert)
        $all = SurveySubmission::all();

        return response()->json([
            'sections'     => $sections,
            'text_answers' => $textAnswers->values(),
            'meta'         => [
                'total_responses' => $total,
                'by_beruf'        => $all->whereNotNull('ausbildungsberuf')->groupBy('ausbildungsberuf')->map->count(),
                'by_jahr'         => $all->whereNotNull('ausbildungsjahr')->groupBy('ausbildungsjahr')->map->count()->sortKeys(),
            ],
        ]);
    }
}
