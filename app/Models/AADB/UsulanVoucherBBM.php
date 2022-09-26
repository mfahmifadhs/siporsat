<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanVoucherBBM extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_form_usulan_voucher_bbm";
    protected $primaryKey   = "id_form_usulan_voucher_bbm";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_voucher_bbm',
        'form_usulan_id',
        'kendaraan_id',
        'harga_perliter',
        'jumlah_kebutuhan',
        'jenis_bbm',
        'total_biaya',
        'bulan_pengadaan'
    ];
}
