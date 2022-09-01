<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    use HasFactory;
    protected $table        = "tbl_aplikasi";
    protected $primaryKey   = "id_aplikasi";
    public $timestamps      = false;

    protected $fillable = [
        '0d_aplikasi',
        'aplikasi',
        'deskripsi_aplikasi'
    ];
}
