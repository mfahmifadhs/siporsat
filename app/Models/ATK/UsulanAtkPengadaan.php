<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtkPengadaan extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan_pengadaan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_pengadaan',
        'form_usulan_id',
        'nama_barang',
        'spesifikasi',
        'jumlah',
        'satuan',
        'tanggal',
        'status',
        'keterangan'
    ];
}
