<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_kendaraan";
    protected $primaryKey   = "id_kendaraan";
    public $timestamps      = false;

    protected $fillable = [
        'id_kendaraan',
        'form_usulan_id',
        'unit_kerja_id',
        'jenis_aadb',
        'kode_barang',
        'jenis_kendaraan_id',
        'merk_kendaraan',
        'tipe_kendaraan',
        'no_plat_kendaraan',
        'mb_stnk_plat_kendaraan',
        'no_plat_rhs',
        'mb_stnk_plat_rhs',
        'no_bpkb',
        'no_rangka',
        'no_mesin',
        'tahun_kendaraan',
        'kondisi_kendaraan_id',
        'pengguna',
        'jabatan',
        'pengemudi'
    ];

    public function kendaraanSewa() {
        return $this->hasMany(KendaraanSewa::class, 'kendaraan_id','id_kendaraan');
    }
}
