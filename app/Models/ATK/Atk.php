<?php

namespace App\Models\atk;

use App\Models\ATK\RiwayatAtk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Atk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_master";
    protected $primaryKey   = "id_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_atk',
        'kode_ref',
        'kategori_id',
        'deskripsi_barang',
        'satuan_barang',
        'keterangan_barang',
        'status_id'
    ];

    public function riwayatTransaksi() {
        return $this->hasMany(RiwayatAtk::class, 'atk_id')
            ->select(DB::raw('sum(jumlah) as stok'), 'status_riwayat')
            ->groupBy('status_riwayat');
    }

    public function riwayat() {
        return $this->hasMany(RiwayatAtk::class, 'atk_id');
    }
}
