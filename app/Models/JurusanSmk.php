<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurusanSmk extends Model
{
    use HasFactory;

    protected $table = 'jurusan_smk';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
