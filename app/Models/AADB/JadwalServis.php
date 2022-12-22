<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalServis extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_jadwal_servis";
    protected $primaryKey   = "id_jadwal_servis";
    public $timestamps      = false;

    protected $fillable = [
        'id_jadwal_servis',
        'kendaraan_id',
        'km_terakhir',
        'km_servis',
        'km_ganti_oli',
        'jatuh_tempo_servis',
        'jatuh_tempo_ganti_oli',
        'tgl_terakhir_servis',
        'tgl_terakhir_ganti_oli'
    ];
}
