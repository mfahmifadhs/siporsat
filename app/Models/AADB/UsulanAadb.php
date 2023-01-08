<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAadb extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_form_usulan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan',
        'pegawai_id',
        'kode_form',
        'jenis_form',
        'total_pengajuan',
        'tanggal_usulan',
        'rencana_pengguna',
        'keterangan',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_usulan_pengusul',
        'otp_usulan_kabag',
        'otp_bast_ppk',
        'otp_bast_pengusul',
        'otp_bast_pengusul',
        'konfirmasi_pengajuan'
    ];

    public function kendaraan() {
        return $this->hasMany(Kendaraan::class, 'form_usulan_id')
            ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id');
    }

    public function usulanKendaraan() {
        return $this->hasMany(UsulanKendaraan::class, 'form_usulan_id','id_form_usulan')
            ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id');
    }

    public function usulanServis() {
        return $this->hasMany(UsulanServis::class, 'form_usulan_id','id_form_usulan')
            ->join('aadb_tbl_kendaraan','id_kendaraan','kendaraan_id');
    }

    public function usulanSTNK() {
        return $this->hasMany(UsulanPerpanjanganSTNK::class, 'form_usulan_id','id_form_usulan')
            ->join('aadb_tbl_kendaraan','id_kendaraan','kendaraan_id');
    }

    public function usulanVoucher() {
        return $this->hasMany(UsulanVoucherBBM::class, 'form_usulan_id','id_form_usulan');
    }
}
