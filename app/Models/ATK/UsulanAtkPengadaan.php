<?php

namespace App\Models\Atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanAtkPengadaan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table        = "atk_tbl_form_usulan_pengadaan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_pengadaan',
        'form_usulan_id',
        'jenis_barang',
        'nama_barang',
        'spesifikasi',
        'jumlah',
        'jumlah_disetujui',
        'jumlah_pemakaian',
        'satuan',
        'tanggal',
        'status',
        'keterangan'
    ];
}
