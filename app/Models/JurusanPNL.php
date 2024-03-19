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

    public function criteria()
    {
        return $this->hasMany(CriteriaJurusan::class,'jurusan_pnl_id', 'id');
    }

    public function subject()
    {
        return $this->hasMany(BobotSubject::class,'jurusan_pnl_id', 'id');
    }
    public function result()
    {
        return $this->hasMany(Result::class,'jurusan_pnl_id', 'id');
    }
}
