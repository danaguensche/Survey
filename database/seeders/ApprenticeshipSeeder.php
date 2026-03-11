<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Apprenticeship;

class ApprenticeshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apprenticeships = [
            ['title' => 'Ausbaufacharbeiter:in'],
            ['title' => 'Fachinformatiker:in Anwendungsentwicklung'],
            ['title' => 'Fachinformatiker:in Systemintegration'],
            ['title' => 'Fachlagerist:in'],
            ['title' => 'Fachmann/-frau für Restaurants und Veranstaltungsgastronomie'],
            ['title' => 'Fachpraktiker:in Ausbaufachwerk'],
            ['title' => 'Fachpraktiker:in für Bürokommunikation'],
            ['title' => 'Fachpraktiker:in Hauswirtschaft'],
            ['title' => 'Fachpraktiker:in für Informationstechnologie Systemintegration'],
            ['title' => 'Fachpraktiker:in für Kraftfahrzeugmechatronik'],
            ['title' => 'Fachpraktiker:in Lager'],
            ['title' => 'Fachpraktiker:in für Holzverarbeitung'],
            ['title' => 'Fachpraktiker:in für Maler und Lackierer nach § 42 HwO'],
            ['title' => 'Fachpraktiker:in für Metallbau'],
            ['title' => 'Fachpraktiker:in für Zerspanungsmechanik'],
            ['title' => 'Fachpraktiker:in im Gastgewerbe'],
            ['title' => 'Fachkraft für Gastronomie'],
            ['title' => 'Fachkraft für Metalltechnik'],
            ['title' => 'Fachpraktiker:in Küche'],
            ['title' => 'Fachpraktiker:in Verkauf'],
            ['title' => 'Fahrzeugpfleger:in'],
            ['title' => 'Gärtner:in Garten- und Landschaftsbau'],
            ['title' => 'Gärtner:in Zierpflanzenbau'],
            ['title' => 'Hochbaufacharbeiter:in'],
            ['title' => 'Kaufmann/Kauffrau für Büromanagement'],
            ['title' => 'Kaufmann/Kauffrau im Einzelhandel'],
            ['title' => 'Kaufmann/Kauffrau für Digitalisierungsmanagement'],
            ['title' => 'Kaufmann/Kauffrau für IT-System-Management'],
            ['title' => 'Koch/Köchin'],
            ['title' => 'Konstruktionsmechaniker:in EG Ausrüstungstechnik'],
            ['title' => 'Kraftfahrzeugmechatroniker:in Personenkraftwagentechnik'],
            ['title' => 'Maler:in und Lackierer:in'],
            ['title' => 'Maurer:in'],
            ['title' => 'Fachhelfer:in im Bereich der Pflege'],
            ['title' => 'Tischler:in'],
            ['title' => 'Verkäufer:in'],
            ['title' => 'Werker:in im Gartenbau Fachrichtung Garten- und Landschaftsbau'],
            ['title' => 'Werker:in im Gartenbau Fachrichtung Zierpflanzenbau'],
            ['title' => 'Zerspannungsmechaniker:in EG Dreh- & Fräsmaschinensystem'],
            ['title' => 'Zimmerer:in']
        ];

        foreach ($apprenticeships as $s) {
            Apprenticeship::create($s);
        }
    }
}
