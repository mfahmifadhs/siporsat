<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtkDetail extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan_detail";
    protected $primaryKey   = "id_form_usulan_detail";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_detail',
        'form_usulan_id ',
        'atk_id ',
        'barang_lain ',
        'jumlah_pengajuan',
        'satuan_detail',
        'harga',
        'keterangan'
    ];
}
