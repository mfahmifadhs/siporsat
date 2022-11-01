<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsulanAtkLampiran extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_form_usulan_lampiran";
    protected $primaryKey   = "id_lampiran";
    public $timestamps      = false;

    protected $fillable = [
        'id_lampiran',
        'form_usulan_id',
        'nomor_kontrak',
        'nomor_kwitansi',
        'nilai_kwitansi',
        'total_biaya',
        'file_kwitansi'
    ];
}
