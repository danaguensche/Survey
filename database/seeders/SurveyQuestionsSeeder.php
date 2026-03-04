<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SurveyQuestion;

class SurveyQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SurveyQuestion::insert([
            ['section' => '1', 'question_text' => 'Räume waren in ordnungsgemäßem Zustand.', 'type' => 'rating', 'scale_max' => 5, 'order_index' => 1],
            ['section' => '1', 'question_text' => 'Technische Ausstattung stand ausreichend zur Verfügung.', 'type' => 'rating', 'scale_max' => 5, 'order_index' => 2],
            ['section' => '1', 'question_text' => 'Regelmäßige Rückmeldungen zu Lern- und Leistungsstand.', 'type' => 'rating', 'scale_max' => 5, 'order_index' => 3],
            ['section' => '2', 'question_text' => 'Fachliche Kompetenz der Dozenten', 'type' => 'rating', 'scale_max' => 5, 'order_index' => 4],
            ['section' => '3', 'question_text' => 'Organisation der Maßnahme', 'type' => 'rating', 'scale_max' => 5, 'order_index' => 5],
            ['section' => '4', 'question_text' => 'Was hat Ihnen besonders gut gefallen?', 'type' => 'text', 'scale_max' => 0, 'order_index' => 6],
            ['section' => '5', 'question_text' => 'Was könnte verbessert werden?', 'type' => 'text', 'scale_max' => 0, 'order_index' => 7],
        ]);

        foreach ($fragen as $frage) {
            SurveyQuestion::firstOrCreate(
                ['order_index' => $frage['order_index']],
                $frage
            );
        }
    }
}
