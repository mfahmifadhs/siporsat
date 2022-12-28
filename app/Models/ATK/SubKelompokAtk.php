<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ATK\KelompokATK;

class SubKelompokAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_kelompok_sub";
    protected $primaryKey   = "id_subkelompok_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_subkelompok_atk',
        'kelompok_atk_id',
        'subkelompok_atk',
        'total_subkelompok_atk'
    ];

    public function KelompokATK() {
        return $this->belongsTo(KelompokATK::class, 'kelompok_atk_id');
    }
}
