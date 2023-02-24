<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanAtkPermintaan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table        = "atk_tbl_form_usulan_permintaan";
    protected $primaryKey   = "id_permintaan";
    public $timestamps      = false;

    protected $fillable = [
        'id_permintaan',
        'form_usulan_id',
        'bast_id',
        'pengadaan_id',
        'jumlah',
        'jumlah_disetujui',
        'jumlah_penyerahan',
        'status_penyerahan',
        'tanggal_penyerahan'
    ];
}
