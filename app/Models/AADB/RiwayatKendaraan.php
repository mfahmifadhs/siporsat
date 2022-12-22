<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKendaraan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_riwayat_kendaraan";
    protected $primaryKey   = "id_riwayat_kendaraan";
    public $timestamps      = false;

    protected $fillable = [
        'id_riwayat_kendaraan',
        'kendaraan_id',
        'tanggal_pengguna',
        'pengguna',
        'jabatan',
        'pengemudi',
        'status_pengguna',
        'status_pengemudi'
    ];
}
