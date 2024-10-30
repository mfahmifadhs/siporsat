<?php

namespace App\Models\gdn;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GDN\UsulanGdnDetail;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanGdn extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table        = "gdn_tbl_form_usulan";
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
        'otp_usulan_kabag'
    ];

    public function detailUsulanGdn() {
        return $this->hasMany(UsulanGdnDetail::class, 'form_usulan_id')
            ->join('gdn_tbl_bid_kerusakan','id_bid_kerusakan','bid_kerusakan_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pegawai() {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
