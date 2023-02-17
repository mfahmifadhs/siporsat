<?php

namespace App\Models\atk;

use App\Models\ATK\UsulanAtkPermintaan;
use App\Models\ATK\UsulanAtkPengadaan;
use App\Models\ATK\UsulanAtkLampiran;
use App\Models\ATK\BastAtk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function pengadaanAtk() {
        return $this->hasMany(UsulanAtkPengadaan::class, 'form_usulan_id');
    }

    public function permintaanAtk() {
        return $this->hasMany(UsulanAtkPermintaan::class, 'form_usulan_id')
            ->join('atk_tbl_form_usulan_pengadaan','id_form_usulan_pengadaan','pengadaan_id')
            ->select('atk_tbl_form_usulan_permintaan.*','atk_tbl_form_usulan_pengadaan.*',
                     'atk_tbl_form_usulan_permintaan.jumlah as jumlah',
                     'atk_tbl_form_usulan_permintaan.jumlah_disetujui as jumlah_disetujui',
                     'atk_tbl_form_usulan_permintaan.status as status',
                     'atk_tbl_form_usulan_permintaan.keterangan as keterangan',
                     'atk_tbl_form_usulan_permintaan.form_usulan_id')
            ->orderBy('nama_barang', 'ASC');
    }

    public function lampiranAtk() {
        return $this->hasMany(UsulanAtkLampiran::class, 'form_usulan_id')
            ->join('atk_tbl_form_usulan','id_form_usulan','form_usulan_id');
    }

    public function bastAtk() {
        return $this->hasMany(BastAtk::class, 'usulan_id');
    }
}
