<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apprenticeship extends Model
{
    //Nur für die API-Ausgabe, nicht zum Speichern oder Ändern von Daten
    public $timestamps = false;

    public function save(array $options = [])
    {
        return false;
    }

    public function delete()
    {
        return false;
    }
}