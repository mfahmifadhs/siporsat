<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserAkses extends Model
{
    use HasFactory;
    protected $table        = "tbl_users_akses";
    protected $primaryKey   = "id_user_akses";
    public $timestamps      = false;

    protected $fillable = [
        'id_user_akses',
        'user_id',
        'is_oldat',
        'is_aadb',
        'is_atk',
        'is_mtc'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
