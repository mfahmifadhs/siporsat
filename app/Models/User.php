<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\pegawai;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table        = "users";
    protected $primaryKey   = "id";
    public $timestamps      = false;

    protected $fillable = [
        'id',
        'level_id',
        'pegawai_id',
        'username',
        'password'
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
