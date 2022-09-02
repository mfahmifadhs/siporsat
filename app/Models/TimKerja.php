<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    use HasFactory;
    protected $table        = "tbl_tim_kerja";
    protected $primaryKey   = "id_tim_kerja";
    public $timestamps      = false;

    protected $fillable = [
        'id_tim_kerja',
        'unit_kerja_id',
        'tim_kerja'
    ];
}
