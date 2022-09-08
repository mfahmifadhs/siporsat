<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pegawai extends Model
{
    use HasFactory;
    protected $table        = "tbl_pegawai";
    protected $primaryKey   = "id_pegawai";
    public $timestamps      = false;

    protected $fillable = [
        'id_pegawai',
        'nip_pegawai',
        'nama_pegawai',
        'nohp_pegawai',
        'jabatan_id',
        'tim_kerja_id',
        'unit_kerja_id',
        'keterangan_pegawai'
    ];

    public function users() {
        return $this->HasMany(User::class);
    }

    public function jabatan() {
        return $this->belongsTo(PegawaiJabatan::class, 'jabatan_id');
    }

    public function timKerja() {
        return $this->belongsTo(TimKerja::class, 'tim_kerja_id');
    }
}
