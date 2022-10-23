<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_kelompok_sub_kategori";
    protected $primaryKey   = "id_kategori_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_kategori_atk',
        'jenis_atk_id',
        'kategori_atk',
        'total_kategori_atk'
    ];

    public function JenisATK() {
        return $this->belongsTo(JenisATK::class, 'jenis_atk_id');
    }
}
