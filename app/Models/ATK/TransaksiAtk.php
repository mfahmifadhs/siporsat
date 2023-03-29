<?php

namespace App\Models\atk;

use App\Models\ATK\TransaksiAtkDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_transaksi";
    protected $primaryKey   = "id_transaksi";
    public $timestamps      = false;

    protected $fillable = [
        'id_transaksi',
        'tanggal_transaksi',
        'nomor_kwitansi',
        'nama_vendor',
        'alamat_vendor',
        'keterangan_transaksi',
        'file_kwitansi',
        'total_barang',
        'total_biaya'
    ];

    public function detailTransaksi() {
        return $this->hasMany(TransaksiAtkDetail::class, 'transaksi_id')
            ->join('atk_tbl_master', 'id_atk', 'atk_id')
            ->orderBy('deskripsi_barang', 'ASC');
    }
}
