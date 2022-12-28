<?php

namespace App\Models\atk;

use App\Models\ATK\UsulanAtkPengadaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan";
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
        'rencana_pengguna',
        'keterangan',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_usulan_pengusul'
    ];

    public function detailUsulanAtk() {
        return $this->hasMany(UsulanAtkDetail::class, 'form_usulan_id')
            ->leftjoin('atk_tbl','id_atk','atk_id')
            ->leftjoin('atk_tbl_kelompok_sub_kategori','id_kategori_atk','kategori_atk_id');
    }

    public function pengadaanAtk() {
        return $this->hasMany(UsulanAtkPengadaan::class, 'form_usulan_id');
    }

    public function lampiranAtk() {
        return $this->hasMany(UsulanAtkLampiran::class, 'form_usulan_id')
            ->join('atk_tbl_form_usulan','id_form_usulan','form_usulan_id');
    }
}
