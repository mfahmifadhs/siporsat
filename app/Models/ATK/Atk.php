<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl";
    protected $primaryKey   = "id_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_atk',
        'kategori_atk_id',
        'merk_atk',
        'spesifikasi_atk',
        'keterangan_atk',
        'total_atk'
    ];

    public function KategoriATK() {
        return $this->belongsTo(KategoriATK::class, 'kategori_atk_id');
    }
}
