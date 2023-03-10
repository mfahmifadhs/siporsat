<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_master";
    protected $primaryKey   = "id_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_atk',
        'kode_ref',
        'kategori_id',
        'deskripsi_barang',
        'satuan_barang',
        'keterangan_barang'
    ];
}
