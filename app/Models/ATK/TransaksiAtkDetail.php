<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiAtkDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_transaksi_detail";
    protected $primaryKey   = "id_detail_transaksi";
    public $timestamps      = false;

    protected $fillable = [
        'id_detail_transaksi',
        'transaksi_id',
        'atk_id',
        'volume_transaksi',
        'harga_satuan',
        'jumlah_biaya'
    ];
}
