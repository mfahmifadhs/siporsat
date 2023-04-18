<?php

namespace App\Models\atk;

use App\Models\UnitKerja;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiwayatAtk extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "atk_tbl_riwayat";
    protected $primaryKey   = "id_riwayat";
    public $timestamps      = false;

    protected $fillable = [
        'id_riwayat',
        'usulan_id',
        'unit_kerja_id',
        'atk_id',
        'jumlah',
        'status_riwayat',
        'tanggal_riwayat'
    ];

    public function unitKerja() {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    public function bast() {
        return $this->belongsTo(BastAtk::class, 'usulan_id');
    }

    public function transaksi() {
        return $this->belongsTo(TransaksiAtk::class, 'usulan_id');
    }
}
