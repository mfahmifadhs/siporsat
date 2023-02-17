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
        'google2fa_secret',
        'sess_modul',
        'sess_form_id',
        'sess_bast_id',
        'status_google2fa'
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
         $this->attributes['google2fa_secret'] = encrypt($value);
    }

    public function getGoogle2faSecretAttribute($value)
    {
        return decrypt($value);
    }
}
