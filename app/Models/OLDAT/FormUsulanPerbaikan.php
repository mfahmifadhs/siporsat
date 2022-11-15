<?php

namespace App\Models\OLDAT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormUsulanPerbaikan extends Model
{
    use HasFactory;
    protected $table        = "oldat_tbl_form_usulan_perbaikan";
    protected $primaryKey   = "id_form_usulan_perbaikan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan_perbaikan',
        'form_usulan_id',
        'barang_id',
        'keterangan_perbaikan'
    ];

}
