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


        $fragen = [
            ['section_id' => '1', 'question_text' => 'Die für meine Ausbildung genutzten Räume und Werkstätten waren in ordnungsgemäßen Zustand.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 1],
            ['section_id' => '1', 'question_text' => 'Die notwendige technische Ausstattung und die Ausbildungsmittel standen mir ausreichend zur Verfügung.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 2],
            ['section_id' => '2', 'question_text' => 'Die Ausbilder:innen und Lehrkräfte vermittelten die Ausbildungsinhalte verständlich und Praxisnah.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 3],
            ['section_id' => '2', 'question_text' => 'Ich erhielt bei fachlichen, persönlichen oder ausbildungsbezogenen Schwierigkeiten angemessene Unterstützung.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 4],
            ['section_id' => '2', 'question_text' => 'Ich bekam regelmäßig Rückmeldung zu meinem Lern- und Leistungsstand.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 5],
            ['section_id' => '3', 'question_text' => 'Zu Beginn des Ausbildungsjahres war ich ausreichend über Inhalte, Ziele und Ablauf der Maßnahme informiert.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 6],
            ['section_id' => '3', 'question_text' => 'Die Inhalte des Ausbildungsjahres waren sinnvoll aufgebaut und entsprachen meinem Ausbildungsstand.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 7],
            ['section_id' => '3', 'question_text' => 'Der organisatorische Ablauf (z.B. Stundenpläne, Praxisphasen, Vertretungen) funktionierte zuverlässig.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 8],
            ['section_id' => '3', 'question_text' => 'Ich hatte ausreichend Zeit und Möglichkeiten, das Gelernte zu üben und zu festigen.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 9],
            ['section_id' => '4', 'question_text' => 'Ich war auf die betriebliche Phase gut vorbereitet und begleitet.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 10],
            ['section_id' => '4', 'question_text' => 'In der betrieblichen Phase konnte ich die im Berufsbildungswerk elernten Inhalte anwenden.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 11],
            ['section_id' => '4', 'question_text' => 'Im Ausbildungsjahr fand keine betriebliche Phase statt.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 12],
            ['section_id' => '5', 'question_text' => 'Das Aubsildungsjahr hat mich fachlich und persönlich weitergebracht.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 13],
            ['section_id' => '5', 'question_text' => 'Ich fühle mich durch die Maßnahme gut auf die nächsten Schritte meiner beruflichen Entwicklung vorbereitet.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 14],
            ['section_id' => '5', 'question_text' => 'Insgesamt bin ich mit der Maßnahme im aktuellen Ausbildungsjahr zufrieden.', 'type' => 'rating', 'scale_max' => 6, 'order_index' => 15],
            ['section_id' => '6', 'question_text' => 'Was lief im Ausbildungsjahr besonders gut?', 'type' => 'text', 'scale_max' => 0, 'order_index' => 16],
            ['section_id' => '6', 'question_text' => 'Was sollte verbessert werden?', 'type' => 'text', 'scale_max' => 0, 'order_index' => 17],
        ];

        foreach ($fragen as $frage) {
            SurveyQuestion::firstOrCreate(
                ['order_index' => $frage['order_index']],
                $frage
            );
        }
    }
}
