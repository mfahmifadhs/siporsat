<?php

namespace App\Models\Ukt;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UKT\UsulanUktDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanUkt extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "ukt_tbl_form_usulan";
    protected $primaryKey   = "id_form_usulan";
    public $timestamps      = false;

    protected $fillable = [
        'id_form_usulan',
        'user_id',
        'pegawai_id',
        'jenis_form',
        'total_pengajuan',
        'no_surat_usulan',
        'tanggal_usulan',
        'no_surat_bast',
        'tanggal_bast',
        'keterangan',
        'status_pengajuan_id',
        'status_proses_id',
        'otp_usulan_pengusul',
        'otp_usulan_kabag',
    ];

    public function detailUsulanUkt() {
        return $this->hasMany(UsulanUktDetail::class, 'form_usulan_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
