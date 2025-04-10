<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'type',
        'options',
    ];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    protected $casts = [
        'options' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
