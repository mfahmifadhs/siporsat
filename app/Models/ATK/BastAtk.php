<?php

namespace App\Models\ATK;

use App\Models\ATK\UsulanAtkPermintaan;
use App\Models\ATK\BastAtkDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BastAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_bast";
    protected $primaryKey   = "id_bast";
    public $timestamps      = false;

    protected $fillable = [
        'id_bast',
        'usulan_id',
        'tanggal_bast',
        'nomor_bast',
        'otp_bast_ppk',
        'otp_bast_pengusul',
        'otp_bast_kabag'
    ];

    public function detailAtk() {
        return $this->hasMany(UsulanAtkPermintaan::class, 'bast_id')
            ->join('atk_tbl_form_usulan_pengadaan', 'id_form_usulan_pengadaan','pengadaan_id')
            ->select('jenis_barang','nama_barang','spesifikasi','atk_tbl_form_usulan_permintaan.jumlah_disetujui',
                     'jumlah_penyerahan','satuan','status_penyerahan')
            ->orderBy('nama_barang', 'ASC');
    }

    public function detailBast() {
        return $this->hasMany(BastAtkDetail::class, 'bast_id')
            ->join('atk_tbl_form_usulan_permintaan', 'id_permintaan','permintaan_id')
            ->join('atk_tbl_form_usulan_pengadaan', 'id_form_usulan_pengadaan','pengadaan_id')
            ->select('jenis_barang','nama_barang','spesifikasi','atk_tbl_form_usulan_permintaan.jumlah_disetujui',
                     'jumlah_penyerahan','jumlah_bast_detail','satuan','status_penyerahan')
            ->orderBy('nama_barang', 'ASC');
    }

    public function detailBast2() {
        return $this->hasMany(BastAtkDetail::class, 'bast_id')
            ->join('atk_tbl_form_usulan_permintaan', 'id_permintaan','permintaan_id')
            ->join('atk_tbl_master', 'id_atk','atk_id')
            ->orderBy('deskripsi_barang', 'ASC');
    }

    public function usulan() {
        return $this->belongsTo(UsulanAtk::class, 'usulan_id');
    }
}
