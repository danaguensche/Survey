<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['title' => 'Rahmenbedingungen und Ausstattung', 'order_index' => 1],
            ['title' => 'Reha-Team und Unterstützung', 'order_index' => 2],
            ['title' => 'Organisation und Inhalte des Ausbildungsjahres', 'order_index' => 3],
            ['title' => 'Betriebliche Phasen (Praktikum)', 'order_index' => 4],
            ['title' => 'Gesamtbewertung und Nutzen', 'order_index' => 5],
            ['title' => 'Hinweise und Verbesserungen', 'order_index' => 6],
         ];

         foreach ($sections as $s) {
            Section::create($s);
        }
    }
}
