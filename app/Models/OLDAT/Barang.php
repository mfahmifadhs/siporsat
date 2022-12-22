<?php

namespace App\Models\OLDAT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OLDAT\RiwayatBarang;

class Barang extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_barang";
    protected $primaryKey   = "id_barang";
    public $timestamps      = false;

    protected $fillable = [
        'id_barang',
        'form_usulan_id',
        'unit_kerja_id',
        'pengguna_barang',
        'kategori_barang_id',
        'kode_barang',
        'nup_barang',
        'merk_tipe_barang',
        'spesifikasi_barang',
        'jumlah_barang',
        'satuan_barang',
        'kondisi_barang_id',
        'gambar_barang',
        'nilai_perolehan',
        'tahun_perolehan',
        'status_barang'
    ];

    public function riwayat() {
        return $this->hasMany(RiwayatBarang::class, 'barang_id','id_barang')
            ->join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id');
    }
}
