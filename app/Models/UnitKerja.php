<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $table        = "tbl_unit_kerja";
    protected $primaryKey   = "id_unit_kerja";
    public $timestamps      = false;

    protected $fillable = [
        'id_unit_kerja',
        'unit_utama_id',
        'unit_kerja'
    ];
}
