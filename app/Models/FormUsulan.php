<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormUsulan extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_form_usulan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan',
        'pegawai_id',
        'kode_form',
        'jenis_form',
        'total_pengajuan',
        'tanggal_usulan',
        'jenis_form',
        'rencana_pengguna'

    ];
}
