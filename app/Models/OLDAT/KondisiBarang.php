<?php

namespace App\Models\OLDAT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiBarang extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_kondisi_barang";
    protected $primaryKey   = "id_kondisi_barang";
    public $timestamps      = false;

    protected $fillable = [
        'kondisi_barang'
    ];
}
