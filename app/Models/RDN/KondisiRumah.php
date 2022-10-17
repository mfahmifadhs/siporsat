<?php

namespace App\Models\RDN;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiRumah extends Model
{
    use HasFactory;
    protected $table        = "rdn_tbl_kondisi_rumah";
    protected $primaryKey   = "id_kondisi_rumah";
    public $timestamps      = false;

    protected $fillable = [
        'id_kondisi_rumah',
        'kondisi_rumah'
    ];
}
