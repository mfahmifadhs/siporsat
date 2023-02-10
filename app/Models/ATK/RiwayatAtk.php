<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_riwayat";
    protected $primaryKey   = "id_riwayat";
    public $timestamps      = false;

    protected $fillable = [
        'id_riwayat',
        'unit_kerja_id',
        'atk_id',
        'jumlah',
        'status_riwayat',
        'tanggal_riwayat'
    ];
}
