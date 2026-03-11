<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    protected $fillable = ['ausbildungsberuf', 'consent'];

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'submission_id');
    }
}

