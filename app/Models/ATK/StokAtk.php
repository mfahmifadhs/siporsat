<?php

namespace App\Models\atk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokAtk extends Model
{
    use HasFactory;
    protected $table        = "atk_tbl_stok";
    protected $primaryKey   = "id_stok";
    public $timestamps      = false;

    protected $fillable = [
        'id_stok',
        'tanggal_stok',
        'form_usulan_id',
        'atk_id',
        'stok_atk',
        'satuan'
    ];

    public function Atk() {
        return $this->belongsTo(Atk::class, 'atk_id');
    }
}
