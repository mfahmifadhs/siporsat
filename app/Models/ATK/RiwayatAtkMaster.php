<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatAtkMaster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_riwayat_atk";
    protected $primaryKey   = "id_riwayat_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_riwayat_atk',
        'unit_kerja_id',
        'deskripsi_barang',
        'is_approve',
        'created_at'
    ];
}
