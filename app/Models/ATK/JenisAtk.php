<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ATK\SubKelompokAtk;

class JenisAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_kelompok_sub_jenis";
    protected $primaryKey   = "id_jenis_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_jenis_atk',
        'subkelompok_atk_id',
        'jenis_atk',
        'total_jenis_atk'
    ];

    public function SubKelompokATK() {
        return $this->belongsTo(SubKelompokATK::class, 'subkelompok_atk_id');
    }
}
