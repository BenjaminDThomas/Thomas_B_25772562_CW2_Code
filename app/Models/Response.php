<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['questionnaire_id', 'user_id', 'guest_id'];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

