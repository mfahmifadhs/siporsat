<?php

namespace App\Models\Ukt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanUktDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "ukt_tbl_form_usulan_detail";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_detail',
        'form_usulan_id',
        'bid_kerusakan_id',
        'lokasi_bangunan',
        'lokasi_spesifik',
        'keterangan'
    ];
}
