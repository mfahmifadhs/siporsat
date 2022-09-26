<?php

namespace App\Models\OLDAT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OLDAT\KategoriBarang;

class FormUsulanPengadaan extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_form_usulan_pengadaan";
    protected $primaryKey   = "id_form_usulan_pengadaan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_pengadaan',
        'form_usulan_id',
        'kategori_barang_id',
        'merk_barang',
        'spesifikasi_barang',
        'jumlah_barang',
        'satuan_barang',
        'foto_barang'
    ];

    public function kategori() {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }


}
