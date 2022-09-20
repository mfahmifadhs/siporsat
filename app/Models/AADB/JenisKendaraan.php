<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKendaraan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_jenis_kendaraan";
    protected $primaryKey   = "id_jenis_kendaraan";
    public $timestamps      = false;

    protected $fillable = [
        'id_jenis_kendaraan',
        'jenis_kendaraan'
    ];
}
