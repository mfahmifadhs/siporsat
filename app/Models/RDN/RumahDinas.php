<?php

namespace App\Models\RDN;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RumahDinas extends Model
{
    use HasFactory;
    protected $table        = "rdn_tbl_rumah_dinas";
    protected $primaryKey   = "id_rumah_dinas";
    public $timestamps      = false;

    protected $fillable = [
        'id_rumah_dinas',
        'golongan_rumah',
        'alamat_rumah',
        'lokasi_kota',
        'luas_bangunan',
        'luas_tanah',
        'kondisi_rumah_id',
        'foto_rumah',
        'penghuni_id'
    ];

    public function PenghuniRumah() {
        return $this->hasMany(PenghuniRumah::class, 'rumah_dinas_id','id_rumah_dinas')
            ->join('tbl_pegawai','id_pegawai','pegawai_id');
    }
}
