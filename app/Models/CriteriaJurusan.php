<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaJurusan extends Model
{
    use HasFactory;

    protected $table = 'criteria_jurusan_pnl';

    protected $guarded = ['id'];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class, 'criteria_id', 'id');
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
