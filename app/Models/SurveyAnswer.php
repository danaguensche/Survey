<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = ['submission_id', 'question_id', 'rating_value', 'text_value'];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function submission()
    {
        return $this->belongsTo(SurveySubmission::class);
    }
}
