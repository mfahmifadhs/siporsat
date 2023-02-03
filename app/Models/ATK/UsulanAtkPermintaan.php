<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtkPermintaan extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan_permintaan";
    protected $primaryKey   = "id_permintaan";
    public $timestamps      = false;

    protected $fillable = [
        'id_permintaan',
        'form_usulan_id',
        'pengadaan_id',
        'jumlah',
        'jumlah_disetujui',
        'tanggal_penyerahan'
    ];
}
