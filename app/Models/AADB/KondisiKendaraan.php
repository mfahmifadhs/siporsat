<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiKendaraan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_kondisi_kendaraan";
    protected $primaryKey   = "id_kondisi_kendaraan";
    public $timestamps      = false;

    protected $fillable = [
        'id_kondisi_kendaraan',
        'kondisi_kendaraan'
    ];
}
