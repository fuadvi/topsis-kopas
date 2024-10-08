<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'asal_sekolah',
        'jurusan_smk_id',
        'nis',
        'class'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'role_id',
        'jurusan_smk_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(JurusanSmk::class,'jurusan_smk_id','id');
    }

    public function isAdmin()
    {
        return $this->role->nama === 'admin';
    }

    public function result()
    {
        return $this->hasMany(Result::class)
            ->select('id','user_id','jurusan','metode');
    }

    public function perhitungan()
    {
        return $this->hasMany(Perhitungan::class)
                ->orderBy('position');
    }
}
