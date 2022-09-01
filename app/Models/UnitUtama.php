<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitUtama extends Model
{
    use HasFactory;
    protected $table        = "tbl_unit_utama";
    protected $primaryKey   = "id_unit_utama";
    public $timestamps      = false;

    protected $fillable = [
        'id_unit_utama',
        'unit_utama'
    ];
}
