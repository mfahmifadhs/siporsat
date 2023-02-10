<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_kategori";
    protected $primaryKey   = "id_kategori_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_kategori_atk',
        'deskripsi_kategori',
        'satuan_kategori',
        'keterangan_kategori'
    ];
}
