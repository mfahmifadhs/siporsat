<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'pegawai_id',
        'jenis_form',
        'total_pengajuan',
        'no_surat_usulan',
        'tanggal_usulan',
        'no_surat_bast',
        'tanggal_bast',
        'rencana_pengguna',
        'status_pengajuan_id',
        'status_proses_id',
    ];
}
