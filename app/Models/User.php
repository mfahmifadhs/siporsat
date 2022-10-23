<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Pegawai;
use App\Models\UserAkses;
use App\Models\UnitKerja;

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
        'password',
        'secret_key'
    ];

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function unitKerja() {
        return $this->HasMany(UnitKerja::class);
    }

    public function akses() {
        return $this->HasMany(UserAkses::class,'user_id','id');
    }

    public function setGoogle2faSecretAttribute($value)
    {
         $this->attributes['secret_key'] = encrypt($value);
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }
}
