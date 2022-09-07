<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBarang extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_riwayat_barang";
    protected $primaryKey   = "id_riwayat_barang";
    public $timestamps      = false;

    protected $fillable = [
        'id_riwayat_barang',
        'barang_id',
        'pegawai_id',
        'kondisi_barang_id',
        'keperluan_penggunaan'
    ];

}
