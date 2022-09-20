<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanSewa extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_kendaraan_sewa";
    protected $primaryKey   = "id_kendaraan_sewa";
    public $timestamps      = false;

    protected $fillable = [
        'id_kendaraan_sewa',
        'kendaraan_id',
        'mulai_sewa'
    ];
}
