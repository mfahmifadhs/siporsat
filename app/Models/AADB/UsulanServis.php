<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanServis extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_form_usulan_servis";
    protected $primaryKey   = "id_form_usulan_servis";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_servis',
        'form_usulan_id',
        'kendaraan_id',
        'kilometer_terakhir',
        'tgl_servis_terakhir',
        'jatuh_tempo_servis',
        'tgl_ganti_oli_terakhir',
        'jatuh_tempo_ganti_oli',
        'keterangan_servis'
    ];
}
