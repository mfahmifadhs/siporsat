<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelSub extends Model
{
    use HasFactory;
    protected $table        = "tbl_level_sub";
    protected $primaryKey   = "id_sub_level";
    public $timestamps      = false;

    protected $fillable = [
        'id_sub_level',
        'aplikasi_id',
        'level_id',
        'tim_kerja_id'
    ];
}
