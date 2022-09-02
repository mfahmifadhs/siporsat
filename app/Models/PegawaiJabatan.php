<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiJabatan extends Model
{
    use HasFactory;
    protected $table        = "tbl_pegawai_jabatan";
    protected $primaryKey   = "id_jabatan";
    public $timestamps      = false;

    protected $fillable = [
        'id_jabatan',
        'jabatan'
    ];
}
