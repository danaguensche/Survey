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


        //Eloquent-Query Builder mit Where-Bedingung nur wenn Parameter nicht null ist
        //with ('answers') um die Antworten direkt mitzuladen (Eager Loading)
        //gibt Collection von SurveySubmission zurück, jede mit zugehörigen Antworten
        $submissions = SurveySubmission::query()
            ->when($beruf, fn($q) => $q->where('ausbildungsberuf', $beruf))
            ->when($jahr,  fn($q) => $q->where('ausbildungsjahr', $jahr))
            ->with('answers')
            ->get();

        //FlatMap um alle Antworten aus den Submissions in eine einzige Collection zu packen
        $total      = $submissions->count();
        $allAnswers = $submissions->flatMap->answers;

        // Rating-Sections

        //Sections mit zugehörigen Fragen laden, dabei nur Rating-Fragen berücksichtigen
        $sections = Section::orderBy('order_index')
            ->with(['questions' => fn($q) => $q->where('type', 'rating')->orderBy('order_index')])
            ->get()
            ->map(function ($section) use ($allAnswers) {
                return [
                    'title'     => $section->title,
                    'questions' => $section->questions->map(function ($q) use ($allAnswers) {

                        //Alle Antworten zu dieser Frage filtern, nur die mit rating_value, dann die Werte extrahieren und in Integer umwandeln
                        $vals = $allAnswers
                            ->where('question_id', $q->id)
                            ->whereNotNull('rating_value')
                            ->pluck('rating_value')
                            ->map(fn($v) => (int) $v);

                        $count = $vals->count();

                        //array_fill erstellt ein Array der Länge scale_max, gefüllt mit 0. Dann wird über alle Werte iteriert und die entsprechenden Indizes im Dist-Array hochgezählt
                        $dist  = array_fill(0, $q->scale_max, 0);

                        //zählen wie oft jede Bewertung abgegeben wurde, dabei nur Werte zwischen 1 und scale_max berücksichtigen
                        foreach ($vals as $v) {
                            if ($v >= 1 && $v <= $q->scale_max) $dist[$v - 1]++;
                        }

                        return [
                            'id'            => $q->id, 
                            'question_text' => $q->question_text,
                            'type'          => 'rating', 
                            'scale_max'     => $q->scale_max,
                            'average'       => $count > 0 ? round($vals->avg(), 1) : 0, //Durchschnitt der Bewertung
                            'count'         => $count, //Anzahl der abgegebenen Bewertungen
                            'distribution'  => array_values($dist), //Verteilung der Bewertungen, z.B. [0, 2, 5, 10, 3] für scale_max=5
                        ];
                    })->values(),
                ];
            });


        // Freitextantworten
        //Alle Fragen vom Typ text laden
        $textAnswers = SurveyQuestion::where('type', 'text')
            ->orderBy('order_index')
            ->get()
            ->map(fn($q) => [
                'question_text' => $q->question_text,
                'answers'       => $allAnswers
                    ->where('question_id', $q->id)
                    ->whereNotNull('text_value')
                    ->pluck('text_value')
                    ->filter() //leere Antworten entfernen
                    ->values(),
            ]);

        // ALLE Daten um globale Statisiken zu berechnen, z.B. pro Beruf oder Lehrjahr
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
