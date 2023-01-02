<?php

namespace App\Models\Ukt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UKT\UsulanUktDetail;

class UsulanUkt extends Model
{
    use HasFactory;
    protected $table        = "ukt_tbl_form_usulan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan',
        'pegawai_id',
        'jenis_form',
        'total_pengajuan',
        'no_surat_usulan',
        'tanggal_usulan',
        'no_surat_bast',
        'tanggal_bast',
        'keterangan',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_usulan_pengusul',
        'otp_usulan_kabag',
    ];

    public function detailUsulanUkt() {
        return $this->hasMany(UsulanUktDetail::class, 'form_usulan_id');
    }
}
