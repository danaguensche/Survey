<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = ['section', 'question_text', 'type', 'scale_max', 'order_index'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
