<?php

namespace App\Models\OLDAT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OLDAT\FormUsulanPengadaan;
use App\Models\OLDAT\FormUsulanPerbaikan;
use App\Models\OLDAT\Barang;

class FormUsulan extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan',
        'pegawai_id',
        'kode_form',
        'jenis_form',
        'total_pengajuan',
        'tanggal_usulan',
        'jenis_form',
        'rencana_pengguna',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_usulan_pengusul',
        'otp_usulan_kabag',
        'otp_bast_ppk',
        'otp_bast_pengusul',
        'otp_bast_kabag',
        'konfirmas_pengajuan'
    ];

    public function barang() {
        return $this->hasMany(Barang::class, 'form_usulan_id','id_form_usulan')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id');
    }

    public function detailPengadaan() {
        return $this->hasMany(FormUsulanPengadaan::class, 'form_usulan_id','id_form_usulan')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id');
    }

    public function detailPerbaikan() {
        return $this->hasMany(FormUsulanPerbaikan::class, 'form_usulan_id', 'id_form_usulan')
            ->join('oldat_tbl_barang','id_barang','barang_id')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id');
    }
}
