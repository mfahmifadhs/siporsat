<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_barang";
    protected $primaryKey   = "id_barang";
    public $timestamps      = false;

    protected $fillable = [
        'id_barang',
        'kategori_barang_id',
        'kode_barang',
        'nup_barang',
        'spesifikasi_barang',
        'jumlah_barang',
        'satuan_barang',
        'kondisi_barang_id',
        'gambar_barang',
        'nilai_perolehan',
        'tahun_perolehan'
    ];
}
