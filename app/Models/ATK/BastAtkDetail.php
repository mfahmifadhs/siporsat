<?php

namespace App\Models\atk;

use App\Models\ATK\BastAtk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BastAtkDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_bast_detail";
    protected $primaryKey   = "id_bast_detail";
    public $timestamps      = false;

    protected $fillable = [
        'id_bast_detail',
        'bast_id',
        'permintaan_id',
        'jumlah_bast_detail'
    ];
}
