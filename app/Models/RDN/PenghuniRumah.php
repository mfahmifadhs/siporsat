<?php

namespace App\Models\RDN;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghuniRumah extends Model
{
    use HasFactory;
    protected $table        = "rdn_tbl_penghuni";
    protected $primaryKey   = "id_penghuni";
    public $timestamps      = false;

    protected $fillable = [
        'id_penghuni',
        'pegawai_id',
        'rumah_dinas_id',
        'tanggal_update',
        'nomor_sip',
        'masa_berakhir_sip',
        'jenis_sertifikat',
        'status_penghuni'
    ];
}
