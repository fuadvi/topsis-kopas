<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function choices()
    {
        return $this->hasMany(Choice::class);
    }

    public function answer()
    {
        return $this->hasMany(Answer::class);
    }

    public function title()
    {
        return $this->belongsTo(QuestionTitle::class,'question_title_id','id');
    }
}
