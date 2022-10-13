<?php

namespace App\Models\AADB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUsulan extends Model
{
    use HasFactory;
    protected $table        = "aadb_tbl_jenis_form_usulan";
    protected $primaryKey   = "id_jenis_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_jenis_form_usulan',
        'jenis_form_usulan'
    ];
}
