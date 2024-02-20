<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurusanPNL extends Model
{
    use HasFactory;

    protected $table = 'jurusan_pnl';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $guarded = ['id'];
}
