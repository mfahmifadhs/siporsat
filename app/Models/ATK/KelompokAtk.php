<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_kelompok";
    protected $primaryKey   = "id_kelompok_atk";
    public $timestamps      = false;

    protected $fillable = [
        'id_kelompok_atk',
        'kelompok_atk',
        'total_kelompok_atk'
    ];
}
