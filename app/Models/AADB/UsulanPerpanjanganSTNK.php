<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanPerpanjanganSTNK extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_form_usulan_perpanjangan_stnk";
    protected $primaryKey   = "id_form_usulan_perpanjangan_stnk";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_perpanjangan_stnk',
        'form_usulan_id',
        'kendaraan_id',
        'mb_stnk_lama',
        'mb_stnk_baru',
        'jatuh_tempo_servis',
        'biaya_perpanjangan',
        'bukti_pembayaran'
    ];
}
