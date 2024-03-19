<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTitle extends Model
{
    use HasFactory;

    protected $hidden = [
      'created_at',
        'updated_at'
    ];

    protected $guarded = ['id'];

    public function question()
    {
       return $this->hasMany(Question::class);
    }
}
