<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Section.php
class Section extends Model
{
    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)->orderBy('order_index');
    }
}
