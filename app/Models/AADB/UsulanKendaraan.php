<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanKendaraan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_form_usulan_pengadaan";
    protected $primaryKey   = "id_form_usulan_pengadaan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_pengadaan',
        'form_usulan_id',
        'jenis_aadb',
        'jenis_kendaraan_id',
        'kualifikasi',
        'merk_tipe_kendaraan',
        'tipe_kendaraan',
        'tahun_kendaraan',
        'jumlah_pengajuan'
    ];
}
