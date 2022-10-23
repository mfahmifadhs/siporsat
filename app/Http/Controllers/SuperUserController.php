<?php

namespace App\Http\Controllers;

use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\JenisUsulan;
use App\Models\AADB\UsulanAadb;
use App\Models\AADB\UsulanKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\KendaraanSewa;
use App\Models\AADB\RiwayatKendaraan;
use App\Models\AADB\UsulanServis;
use App\Models\AADB\UsulanPerpanjanganSTNK;
use App\Models\AADB\UsulanVoucherBBM;
use App\Models\atk\Atk;
use App\Models\atk\JenisAtk;
use App\Models\atk\KategoriAtk;
use App\Models\atk\KelompokAtk;
use App\Models\atk\StokAtk;
use App\Models\atk\SubKelompokAtk;
use App\Models\atk\UsulanAtk;
use App\Models\atk\UsulanAtkDetail;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\FormUsulan;
use App\Models\OLDAT\FormUsulanPengadaan;
use App\Models\OLDAT\FormUsulanPerbaikan;
use App\Models\OLDAT\RiwayatBarang;
use App\Models\RDN\RumahDinas;
use App\Models\UnitKerja;
use App\Models\TimKerja;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use DB;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Carbon\Carbon;

class SuperUserController extends Controller
{

    public function Index()
    {
        if(Auth::user()->pegawai->jabatan_id == 2) {
            $usulanOldat    = FormUsulan::where('status_pengajuan_id', null)->count();
            $usulanAadb     = UsulanAadb::where('status_pengajuan_id', null)->count();

        } elseif(Auth::user()->pegawai->jabatan_id == 5) {
            $usulanOldat    = FormUsulan::where('status_pengajuan_id', 1)->where('status_proses_id', 2)->count();
            $usulanAadb     = UsulanAadb::where('status_pengajuan_id', 1)->where('status_proses_id', 2)->count();
        } else {
            $usulanOldat    = FormUsulan::where('status_proses_id', 1)->count();;
            $usulanAadb     = UsulanAadb::where('status_proses_id', 1)->count();;
        }

        return view('v_super_user.index', compact('usulanOldat','usulanAadb'));
    }

    public function Profile(Request $request,$aksi, $id)
    {
        if ($aksi == 'user') {
            $google2fa  = app('pragmarx.google2fa');
            $secretkey  = $google2fa->generateSecretKey();

            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $registration_data = Auth::user()->pegawai->nama_pegawai,
                $registration_data = $secretkey
            );

            return view('v_super_user.profil', compact('QR_Image','secretkey'));
        } else {
            User::where('id',$id)->first();
                User::where('id',$id)->update([
                    'google2fa_secret' => encrypt($request->secretkey)
                ]);

            return redirect('super-user/dashboard');
        }



    }

    public function Verification(Request $request, $aksi, $id)
    {
        if ($id == 'cek')
        {
            if ($request->modul == 'atk')
            {
                $usulan = UsulanAtk::where('id_form_usulan', $request->form_id)->first();
                if ($usulan->status_proses_id == null) {
                    UsulanAtk::where('id_form_usulan', $request->form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);

                    return redirect('super-user/atk/surat/surat-usulan/'. $request->form_id);
                } else {

                }


            }


        } else {
            $sessUser = User::find(Auth::user()->id);
            $sessUser->sess_modul   = 'atk';
            $sessUser->sess_form_id = $id;
            $sessUser->save();

            return view('google2fa.index');
        }
    }

    public function ReportMain()
    {
        // Report Oldat
        $oldatUsulan    = FormUsulan::get();
        $oldatJenisForm = FormUsulan::select('jenis_form')->groupBy('jenis_form')->get();

        foreach ($oldatJenisForm as $data) {
            $dataArray['usulan'] = $data->jenis_form;
            $dataArray['ditolak'] = $oldatUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['proses'] = $oldatUsulan->where('status_proses_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['selesai'] = $oldatUsulan->where('status_proses_id',5)->where('jenis_form', $data->jenis_form)->count();
            $reportOldat[] = $dataArray;
            unset($dataArray);
        }
        // Report AADB
        $aadbUsulan     = UsulanAadb::get();
        $aadbJenisForm  = JenisUsulan::get();
        foreach ($aadbJenisForm as $data) {
            $dataArray['usulan'] = $data->jenis_form_usulan;
            $dataArray['ditolak'] = $aadbUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['proses'] = $aadbUsulan->where('status_proses_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['selesai'] = $aadbUsulan->where('status_proses_id',5)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $reportAadb[] = $dataArray;
            unset($dataArray);
        }

        return view('v_super_user.laporan_siporsat', compact('reportOldat','reportAadb'));
    }

    public function SendOTPWhatsApp(Request $request){
        $user           = Auth::user();
        $pegawai        = $user->pegawai;
        $version        = getenv("WHATSAPP_API_VERSION");
        $token          = getenv("WHATSAPP_API_TOKEN");
        $phoneNumberId  = getenv("WHATSAPP_API_PHONE_NUMBER_ID");
        $penerima       = '6285772652563';
        $otp            = rand(1000,9999);

        $client = new GuzzleHttpClient();
        $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer '.$token
        ];

        $body = '{
        "messaging_product": "whatsapp",
        "to": '.$penerima.',
        "type": "template",
        "template": {
            "name": "siporsat_testing",
            "language": {
            "code": "id"
            },
            "components": [
            {
                "type": "body",
                "parameters": [
                    {
                        "type": "text",
                        "text": '.$otp.'
                    },
                    {
                        "type": "text",
                        "text": "'.$pegawai->nama_pegawai.'"
                    },
                    {
                        "type": "text",
                        "text": "Konfirmasi '.$request->tujuan.'"
                    }
                ]
            }
            ]
        }
        }';

        $request = new Psr7Request('POST', 'https://graph.facebook.com/'.$version.'/'.$phoneNumberId.'/messages', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        return $otp;
        // return $res->getBody();
        //   return view('v_super_user.apk_oldat.tes');
    }

    // ===============================================
    //             RUMAH DINAS NEGARA (RDN)
    // ===============================================

    public function Rdn(Request $request)
    {
        $lokasiKota = RumahDinas::select('lokasi_kota')->groupBy('lokasi_kota')->get();
        $kendaraan  = Kendaraan::orderBy('jenis_aadb','ASC')
            ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->get();
        $googleChartData = $this->ChartDataRDN();
        return view('v_super_user.apk_rdn.index', compact('lokasiKota','googleChartData','kendaraan'));
    }

    public function OfficialResidence(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $rumah = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')->get();

            return view('v_super_user.apk_rdn.daftar_rumah', compact('rumah'));
        } elseif ($aksi == 'detail') {
            $rumah = RumahDinas::where('id_rumah', $id)
                ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')
                ->first();

            return view('v_super_user.apk_rdn.detail_rumah', compact('rumah'));
        }
    }

    public function ChartDataRdn()
    {
        $dataRumah = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')->get();
        $lokasi    = RumahDinas::select('lokasi_kota')->groupBy('lokasi_kota')->get();

        foreach ($lokasi as $data) {
            $dataArray[] = $data->lokasi_kota;
            $dataArray[] = $dataRumah->where('lokasi_kota', $data->lokasi_kota)->count();
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['rumah'] = $dataRumah;
        $chart = json_encode($dataChart);
        return $chart;
    }

    public function SearchChartDataRdn(Request $request)
    {
        $dataRumah = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id');

        $lokasi    = RumahDinas::select('lokasi_kota')->groupBy('lokasi_kota')->get();

        if($request->hasAny(['golongan_rumah', 'lokasi_kota','kondisi_rumah'])){
            if($request->golongan_rumah){
                $dataSearch = $dataRumah->where('golongan_rumah',$request->golongan_rumah);
            }
            if($request->lokasi_kota){
                $dataSearch = $dataRumah->where('lokasi_kota',$request->lokasi_kota);
            }
            if($request->kondisi_rumah){
                $dataSearch = $dataRumah->where('kondisi_rumah_id',$request->kondisi_rumah);
            }

            $dataSearch = $dataSearch->get();

        }else {
            $dataSearch = $dataRumah->get();
        }

        // dd($dataSearch);
        foreach ($lokasi as $data) {
            $dataArray[]          = $data->lokasi_kota;
            $dataArray[]          = $dataSearch->where('lokasi_kota', $data->lokasi_kota)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table']= $dataSearch;
        $chart = json_encode($dataChart);

        if(count($dataSearch) > 0){
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'success',
                'data'      => $chart
            ], 200);
        }else {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'not found'
            ], 200);
        }
    }


    // ===============================================
    //             ALAT TULIS KANTOR (ATK)
    // ===============================================

    public function Atk(Request $request, $aksi)
    {
        $googleChartData = $this->ChartDataAtk();
        if ($aksi == 'pengadaan') {
            $usulan = UsulanAtk::get();
            return view('v_super_user.apk_atk.index_pengadaan', compact('usulan'));
        } else {
            return view('v_super_user.apk_atk.index_distribusi', compact('googleChartData'));
        }
    }

    public function OfficeStationery(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $atk = ATK::with('KategoriATK')->get();
            return view('v_super_user.apk_atk.daftar_atk', compact('atk'));
        }
    }

    public function SubmissionAtk(Request $request, $aksi, $id)
    {
        if($aksi == 'daftar') {
            $usulan = UsulanAtk::join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->leftjoin('tbl_status_pengajuan','id_status_pengajuan','status_pengajuan_id')
            ->join('tbl_status_proses','id_status_proses','status_proses_id')
            ->orderBy('tanggal_usulan','DESC')
            ->get();

            return view('v_super_user.apk_atk.daftar_pengajuan', compact('usulan'));
        } elseif ($aksi == 'proses') {
            $idFormUsulan = Carbon::now()->format('dmy').$request->id_usulan;
            $usulan = new UsulanAtk();
            $usulan->id_form_usulan     = $idFormUsulan;
            $usulan->pegawai_id         = Auth::user()->pegawai_id;
            $usulan->jenis_form         = $id;
            $usulan->total_pengajuan    = $request->total_pengajuan;
            $usulan->no_surat_usulan    = $request->no_surat_usulan;
            $usulan->tanggal_usulan     = $request->tanggal_usulan;
            $usulan->rencana_pengguna   = $request->rencana_pengguna;
            $usulan->save();

            $atk   = $request->atk_id;
            $idAtk = UsulanAtkDetail::count();
            foreach ($atk as $i => $atk_id) {
                $detail = new UsulanAtkDetail();
                $detail->id_form_usulan_detail = $idAtk + 1;
                $detail->form_usulan_id        = $idFormUsulan;
                $detail->atk_id                = $atk_id;
                $detail->jumlah_pengajuan      = $request->jumlah[$i];
                $detail->satuan                = $request->satuan[$i];
                $detail->keterangan            = $request->keterangan[$i];
                $detail->save();
            }
            return redirect('super-user/verif/usulan-atk/'. $idFormUsulan);

        } elseif ($aksi == 'proses-diterima') {

        } elseif ($aksi == 'proses-ditolak') {

        } else {
            $totalUsulan    = UsulanAtk::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $kelompokAtk    = KelompokAtk::get();
            return view('v_super_user.apk_atk.usulan', compact('idUsulan','aksi','kelompokAtk'));
        }
    }

    public function LetterAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'surat-usulan') {

            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();

            return view('v_super_user/apk_atk/surat_usulan', compact('pimpinan','usulan'));

        } elseif ($aksi == 'surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }
            $form      = UsulanAadb::where('otp_bast_ppk', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('otp_bast_ppk', $id)
                ->first();

            $kendaraan = Kendaraan::with('kendaraanSewa')
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->where('form_usulan_id', $form)
                ->get();

            return view('v_super_user/apk_aadb/surat_bast', compact('pimpinan','jenisAadb','bast','kendaraan','id'));

        } elseif ($aksi == 'print-surat-usulan') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan = UsulanAtk::where('otp_usulan_pengusul', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();

            return view('v_super_user/apk_atk/print_surat_usulan', compact('pimpinan','usulan'));


            $cekForm = UsulanAadb::where('id_form_usulan', $id)->first();
            if ($cekForm->jenis_form == 1) {
                $kodeBast = UsulanAadb::where('id_form_usulan', $id)->update([
                    'kode_otp_bast'        => $request->kode_otp_bast,
                    'konfirmasi_pengajuan' => $request->konfirmasi
                ]);

                $pengadaanBaru  = new Kendaraan();
                $pengadaanBaru->id_kendaraan            = $request->id_kendaraan;
                $pengadaanBaru->jenis_aadb              = $request->jenis_aadb;
                $pengadaanBaru->form_usulan_id          = $id;
                $pengadaanBaru->unit_kerja_id           = Auth::user()->pegawai->unit_kerja_id;
                $pengadaanBaru->kode_barang             = $request->kode_barang;
                $pengadaanBaru->jenis_kendaraan_id      = $request->jenis_kendaraan_id ;
                $pengadaanBaru->merk_kendaraan          = $request->merk_kendaraan;
                $pengadaanBaru->tipe_kendaraan          = $request->tipe_kendaraan;
                $pengadaanBaru->no_plat_kendaraan       = $request->no_plat_kendaraan;
                $pengadaanBaru->mb_stnk_plat_kendaraan  = $request->mb_stnk_plat_kendaraan;
                $pengadaanBaru->no_plat_rhs             = $request->no_plat_rhs;
                $pengadaanBaru->mb_stnk_plat_rhs        = $request->mb_stnk_plat_rhs;
                $pengadaanBaru->no_bpkb                 = $request->no_bpkb;
                $pengadaanBaru->no_rangka               = $request->no_rangka;
                $pengadaanBaru->no_mesin                = $request->no_mesin;
                $pengadaanBaru->tahun_kendaraan         = $request->tahun_kendaraan;
                $pengadaanBaru->kondisi_kendaraan_id    = $request->kondisi_kendaraan_id;
                $pengadaanBaru->save();

                if($request->jenis_aadb == 'sewa') {
                    $cekPengadaanSewa = KendaraanSewa::count();
                    $pengadaanSewa  = new KendaraanSewa();
                    $pengadaanSewa->id_kendaraan_sewa = $cekPengadaanSewa + 1;
                    $pengadaanSewa->kendaraan_id = $request->id_kendaraan;
                    $pengadaanSewa->mulai_sewa   = $request->mulai_sewa;
                    $pengadaanSewa->penyedia     = $request->penyedia;
                    $pengadaanSewa->save();
                }

                UsulanAadb::where('id_form_usulan', $id)->update([ 'status_proses' => 'selesai' ]);

            } elseif ($cekForm->jenis_form == 2) {

            } elseif ($cekForm->jenis_form == 3) {

            } else {

            }

            return redirect('super-user/aadb/usulan/bast/'. $id);

        } elseif ($aksi == 'print-surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $id)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->first();

            $kendaraan = Kendaraan::with('kendaraanSewa')
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->where('form_usulan_id', $id)
                ->where('jenis_aadb','sewa')->get();

            return view('v_super_user/apk_aadb/print_surat_bast', compact('jenisAadb','pimpinan','bast','kendaraan','id'));

        }
    }

    public function Select2Atk(Request $request, $aksi, $id)
    {

        $search = $request->search;
        if ($aksi == 1) {
            if ($search == '') {
                $atk  = SubKelompokAtk::select('id_subkelompok_atk as id', 'subkelompok_atk as nama')
                    ->orderby('id_subkelompok_atk', 'asc')
                    ->get();
            } else {
                $atk  = SubKelompokAtk::select('id_subkelompok_atk', 'subkelompok_atk')
                    ->orderby('id_subkelompok_atk', 'asc')
                    ->where('id_subkelompok_atk', 'like', '%' . $search . '%')
                    ->orWhere('subkelompok_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 2) {
            if ($search == '') {
                $atk  = JenisAtk::select('id_jenis_atk as id','subkelompok_atk_id', 'jenis_atk as nama')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('subkelompok_atk_id', $id)
                    ->get();
            } else {
                $atk  = JenisAtk::select('id_jenis_atk','subkelompok_atk_id','jenis_atk')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('subkelompok_atk_id', $id)
                    ->where('id_jenis_atk', 'like', '%' . $search . '%')
                    ->orWhere('jenis_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 3) {
            if ($search == '') {
                $atk  = KategoriAtk::select('id_kategori_atk as id','jenis_atk_id', 'kategori_atk as nama')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('jenis_atk_id', $id)
                    ->get();
            } else {
                $atk  = KategoriAtk::select('id_kategori_atk','jenis_atk_id','kategori_atk')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('jenis_atk_id', $id)
                    ->where('id_kategori_atk', 'like', '%' . $search . '%')
                    ->orWhere('kategori_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 4) {
            if ($search == '') {
                $atk  = Atk::select('id_atk as id','kategori_atk_id', 'merk_atk as nama')
                    ->orderby('id_atk', 'asc')
                    ->where('kategori_atk_id', $id)
                    ->get();
            } else {
                $atk  = Atk::select('id_atk','kategori_atk_id','merk_atk')
                    ->orderby('id_atk', 'asc')
                    ->where('kategori_atk_id', $id)
                    ->where('id_atk', 'like', '%' . $search . '%')
                    ->orWhere('merk_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 5) {
            $atk  = StokAtk::select('id_stok as id','atk_id','stok_atk as stok', 'satuan')
                        ->orderby('id_stok', 'asc')
                        ->where('atk_id', $id)
                        ->get();
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id.' - '.$data->nama,
                "stok"   =>  $data->stok,
                "satuan" =>  $data->satuan
            );
        }

        return response()->json($response);
    }

    public function Select2AtkDashboard(Request $request, $aksi, $id)
    {
        $search = $request->search;
        if ($aksi == 1) {
            if ($search == '') {
                $atk  = SubKelompokAtk::select('id_subkelompok_atk as id', 'subkelompok_atk as nama')
                    ->orderby('id_subkelompok_atk', 'asc')
                    ->get();
            } else {
                $atk  = SubKelompokAtk::select('id_subkelompok_atk', 'subkelompok_atk')
                    ->orderby('id_subkelompok_atk', 'asc')
                    ->orWhere('subkelompok_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 2) {
            if ($search == '') {
                $atk  = JenisAtk::select('id_jenis_atk as id','subkelompok_atk_id', 'jenis_atk as nama')
                    ->orderby('id_jenis_atk', 'asc')
                    ->get();
            } else {
                $atk  = JenisAtk::select('id_jenis_atk','subkelompok_atk_id','jenis_atk')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('id_jenis_atk', 'like', '%' . $search . '%')
                    ->orWhere('jenis_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 3) {
            if ($search == '') {
                $atk  = KategoriAtk::select('id_kategori_atk as id','jenis_atk_id', 'kategori_atk as nama')
                    ->orderby('id_kategori_atk', 'asc')
                    ->get();
            } else {
                $atk  = KategoriAtk::select('id_kategori_atk','jenis_atk_id','kategori_atk')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('id_kategori_atk', 'like', '%' . $search . '%')
                    ->orWhere('kategori_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 4) {
            if ($search == '') {
                $atk  = Atk::select('id_atk as id','kategori_atk_id', 'merk_atk as nama')
                    ->orderby('id_atk', 'asc')
                    ->get();
            } else {
                $atk  = Atk::select('id_atk','kategori_atk_id','merk_atk')
                    ->orderby('id_atk', 'asc')
                    ->orWhere('merk_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 5) {
            $atk  = StokAtk::select('id_stok as id','atk_id','stok_atk as stok', 'satuan')
                        ->orderby('id_stok', 'asc')
                        ->get();
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id.' - '.$data->nama,
                "stok"   =>  $data->stok,
                "satuan" =>  $data->satuan
            );
        }

        return response()->json($response);
    }

    public function ChartDataAtk()
    {
        $dataAtk = SubKelompokAtk::join('atk_tbl_kelompok_sub_jenis', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->join('atk_tbl_kelompok_sub_kategori', 'id_jenis_atk','jenis_atk_id')
            ->join('atk_tbl', 'id_kategori_atk','kategori_atk_id');
        
        $dataChart['atk'] = $dataAtk->get();
        $stok = $dataAtk->select(DB::raw('sum(total_atk) as stok'))->groupBy('total_atk');
        $dataJenisAtk = KategoriAtk::get();
        foreach ($dataJenisAtk as $data) {
            $dataArray[] = $data->kategori_atk;
            $totalStok =  $stok->where('id_kategori_atk', $data->id_kategori_atk)->get();
            $dataArray[] = $totalStok[0]->stok;
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        
        $chart = json_encode($dataChart);
        // dd($chart);
        return $chart;
    }

    public function SearchChartDataAtk(Request $request)
    {
        // $jenisForm     = JenisUsulan::where('jenis_form_usulan','like','%'. $request->form .'%')->first();

        $dataAtk = SubKelompokAtk::join('atk_tbl_kelompok_sub_jenis', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->join('atk_tbl_kelompok_sub_kategori', 'id_jenis_atk','jenis_atk_id')
            ->join('atk_tbl', 'id_kategori_atk','kategori_atk_id');
        
        // dd($data->get());
        
        // $totalPengajuan = UsulanAadb::select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
        //     ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
        //     ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
        //     ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
        //     ->groupBy('jenis_form','bulan')
        //     ->where('jenis_form', $jenisForm->id_jenis_form_usulan);

        if($request->hasAny(['kategori', 'jenis','nama','merk'])){
            if($request->kategori){
                $dataSearchAtk = $dataAtk->where('id_subkelompok_atk', $request->kategori);
            }
            if($request->jenis){
                $dataSearchAtk = $dataAtk->where('id_jenis_atk', $request->jenis);
            }
            if($request->nama){
                $dataSearchAtk = $dataAtk->where('id_kategori_atk', $request->nama);
            }
            if($request->merk){
                $dataSearchAtk = $dataAtk->where('id_atk', $request->merk);
            }

            $resultSearchAtk = $dataSearchAtk->get();
            // dd($resultSearchAtk);
        }else {
            $resultSearchAtk = $dataAtk->get();
        }

        foreach ($resultSearchAtk as $data) {
            $stok = $dataAtk->select(DB::raw('sum(total_atk) as stok'))->groupBy('total_atk');
            $totalStok =  $stok->where('id_kategori_atk', $data->id_kategori_atk)->get();
            $dataArray[] = $data->kategori_atk;
            $dataArray[] = $totalStok[0]->stok;
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table'] = $resultSearchAtk;
        $chart = json_encode($dataChart);
        // dd($chart);
        if(count($resultSearchAtk)>0){
            return response([
                'status' => true,
                'total' => count($resultSearchAtk),
                'message' => 'success',
                'data' => $chart
            ], 200);
        }else {
            return response([
                'status' => true,
                'total' => count($resultSearchAtk),
                'message' => 'not found'
            ], 200);
        }
    }

    // ===============================================
    //                   AADB
    // ===============================================
    public function Aadb(Request $request)
    {
        $unitKerja       = UnitKerja::get();
        $jenisKendaraan  = JenisKendaraan::get();
        $merk            = Kendaraan::select('merk_kendaraan')->groupBy('merk_kendaraan')->get();
        $tahun           = Kendaraan::select('tahun_kendaraan')->groupBy('tahun_kendaraan')->get();
        $pengguna        = Kendaraan::select('pengguna')->groupBy('pengguna')->get();
        $kendaraan       = Kendaraan::orderBy('jenis_aadb','ASC')
            ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->get();
        $googleChartData = $this->ChartDataAADB();

        if(Auth::user()->pegawai->jabatan_id == 2 || Auth::user()->pegawai->jabatan_id == 5) {
            $pengajuan  = UsulanAadb::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->where('status_proses_id','!=','5')
                ->orderBy('tanggal_usulan', 'DESC')->limit(5)
                ->orderBy('status_proses_id', 'ASC')
                ->paginate(5);
        } else {
            $pengajuan  = UsulanAadb::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->where('status_proses_id','!=','5')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->orderBy('tanggal_usulan', 'DESC')->limit(5)
                ->orderBy('status_proses_id', 'ASC')
                ->paginate(5);
        }

        return view('v_super_user.apk_aadb.index', compact('unitKerja','jenisKendaraan','merk','tahun','pengguna', 'googleChartData','kendaraan','pengajuan'));
    }

    public function SubmissionAadb(Request $request, $aksi, $id)
    {
        if($aksi == 'daftar') {
            $pengajuan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->orderBy('tanggal_usulan','DESC')
                ->get();

            return view('v_super_user.apk_aadb.daftar_pengajuan', compact('pengajuan'));

        } elseif ($aksi == 'proses') {
            $idFormUsulan = Carbon::now()->format('dmy').$request->id_usulan;
            $usulan = new UsulanAadb();
            $usulan->id_form_usulan      = $idFormUsulan;
            $usulan->pegawai_id          = Auth::user()->pegawai_id;
            $usulan->kode_form           = 'AADB_001';
            $usulan->jenis_form          = $request->jenis_form;
            $usulan->total_pengajuan     = $request->total_pengajuan;
            $usulan->tanggal_usulan      = $request->tanggal_usulan;
            $usulan->rencana_pengguna    = $request->rencana_pengguna;
            $usulan->status_proses_id    = 1;
            $usulan->otp_usulan_pengusul = $request->kode_otp_usulan;
            $usulan->no_surat_usulan     = $request->no_surat_usulan;
            $usulan->save();

            if ($id == 'pengadaan') {
                $totalUsulan    = UsulanKendaraan::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $usulanPengadaan = new UsulanKendaraan();
                $usulanPengadaan->id_form_usulan_pengadaan  = $idUsulan;
                $usulanPengadaan->form_usulan_id            = $idFormUsulan;
                $usulanPengadaan->jenis_aadb                = $request->jenis_aadb;
                $usulanPengadaan->jenis_kendaraan_id        = $request->jenis_kendaraan;
                $usulanPengadaan->merk_kendaraan            = $request->merk_kendaraan;
                $usulanPengadaan->tipe_kendaraan            = $request->tipe_kendaraan;
                $usulanPengadaan->tahun_kendaraan           = $request->tahun_kendaraan;
                $usulanPengadaan->save();
            } elseif ($id == 'servis') {
                $totalUsulan    = UsulanServis::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan = $request->kendaraan_id;
                foreach($kendaraan as $i => $kendaraan_id){
                    $usulanServis   = new UsulanServis();
                    $usulanServis->id_form_usulan_servis    = $idUsulan + $i;
                    $usulanServis->form_usulan_id           = $idFormUsulan;
                    $usulanServis->kendaraan_id             = $kendaraan_id;
                    $usulanServis->kilometer_terakhir       = $request->kilometer_terakhir[$i];
                    $usulanServis->tgl_servis_terakhir      = $request->tgl_servis_terakhir[$i];
                    $usulanServis->jatuh_tempo_servis       = $request->jatuh_tempo_servis[$i];
                    $usulanServis->tgl_ganti_oli_terakhir   = $request->tgl_ganti_oli_terakhir[$i];
                    $usulanServis->jatuh_tempo_ganti_oli    = $request->jatuh_tempo_ganti_oli[$i];
                    $usulanServis->save();
                }

            } elseif ($id == 'perpanjangan-stnk') {
                $totalUsulan    = UsulanPerpanjanganSTNK::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan = $request->kendaraan_id;
                foreach($kendaraan as $i => $kendaraan_id){
                    $usulanPerpanjangan   = new UsulanPerpanjanganSTNK();
                    $usulanPerpanjangan->id_form_usulan_perpanjangan_stnk  = $idUsulan + $i;
                    $usulanPerpanjangan->form_usulan_id                    = $idFormUsulan;
                    $usulanPerpanjangan->kendaraan_id                      = $kendaraan_id;
                    $usulanPerpanjangan->mb_stnk_lama                      = $request->mb_stnk[$i];
                    $usulanPerpanjangan->save();
                }

            } elseif ($id == 'voucher-bbm') {
                $totalUsulan    = UsulanVoucherBBM::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan = $request->kendaraan_id;
                foreach($kendaraan as $i => $kendaraan_id){
                    $totalVoucher = ($request->voucher_25[$i]*25000) + ($request->voucher_50[$i]*50000) + ($request->voucher_100[$i]*100000);
                    $usulanVoucherBBM   = new UsulanVoucherBBM();
                    $usulanVoucherBBM->id_form_usulan_voucher_bbm   = $idUsulan + $i;
                    $usulanVoucherBBM->form_usulan_id               = $idFormUsulan;
                    $usulanVoucherBBM->kendaraan_id                 = $kendaraan_id;
                    $usulanVoucherBBM->voucher_25                   = $request->voucher_25[$i];
                    $usulanVoucherBBM->voucher_50                   = $request->voucher_50[$i];
                    $usulanVoucherBBM->voucher_100                  = $request->voucher_100[$i];
                    $usulanVoucherBBM->total_biaya                  = $totalVoucher;
                    $usulanVoucherBBM->bulan_pengadaan              = $request->bulan_pengadaan;
                    $total[$i] =+ $totalVoucher;
                    $usulanVoucherBBM->save();
                }

                UsulanAadb::where('id_form_usulan')->update([ 'total_biaya' => array_sum($total) ]);

            }

            return redirect('super-user/aadb/surat/surat-usulan/'. $idFormUsulan);

        } elseif ($aksi == 'proses-diterima') {
            if ($request->status_usulan == 4) {
                UsulanAadb::where('id_form_usulan', $id)->update([
                    'status_proses_id'     => 4,
                    'konfirmasi_pengajuan' => $request->konfirmasi,
                    'otp_bast_pengusul'    => $request->kode_otp
                ]);
            } elseif ($request->status_usulan == 5) {
                UsulanAadb::where('id_form_usulan', $id)->update([
                    'status_proses_id'     => 5,
                    'otp_bast_kabag'    => $request->kode_otp
                ]);
            } else {
                $cekUsulan = UsulanAadb::where('id_form_usulan',$id)->first();
                if ($cekUsulan->jenis_form == 4) {
                    $form = $request->detail_usulan_id;
                    foreach($form as $i => $detail_usulan_id){
                        $totalVoucher = ($request->voucher_25[$i]*25000) + ($request->voucher_50[$i]*50000) + ($request->voucher_100[$i]*100000);
                        UsulanVoucherBBM::where('id_form_usulan_voucher_bbm', $detail_usulan_id)->update([
                            'voucher_25'    => $request->voucher_25[$i],
                            'voucher_50'    => $request->voucher_25[$i],
                            'voucher_100'   => $request->voucher_25[$i],
                            'total_biaya'   => $totalVoucher,
                        ]);
                        $total[$i] =+ $totalVoucher;
                    }
                    UsulanAadb::where('id_form_usulan', $id)->update([
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2,
                        'otp_usulan_kabag'    => $request->kode_otp,
                        'total_biaya'         => array_sum($total)
                    ]);

                } else {
                    UsulanAadb::where('id_form_usulan', $id)->update([
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2,
                        'otp_usulan_kabag'    => $request->kode_otp
                    ]);
                }
            }

            return redirect('super-user/aadb/dashboard')->with('success','Berhasil melakukan konfirmasi usulan');

        } elseif ($aksi == 'proses-ditolak') {
            UsulanAadb::where('id_form_usulan', $id)->update([ 'status_pengajuan_id' => 2, 'status_proses_id' => null ]);
            return redirect('super-user/aadb/dashboard')->with('failed','Usulan Pengajuan Ditolak');

        } else {
            $totalUsulan    = UsulanAadb::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $jenisKendaraan = JenisKendaraan::get();
            $kendaraan      = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->orderBy('jenis_kendaraan', 'ASC')
                ->get();
            return view('v_super_user.apk_aadb.usulan', compact('idUsulan','aksi','jenisKendaraan','kendaraan'));
        }
    }

    public function LetterAadb(Request $request, $aksi, $id)
    {
        if ($aksi == 'surat-usulan') {

            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->get();

            return view('v_super_user/apk_aadb/surat_usulan', compact('pimpinan','usulan'));

        } elseif ($aksi == 'surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }
            $form      = UsulanAadb::where('otp_bast_ppk', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('otp_bast_ppk', $id)
                ->first();

            $kendaraan = Kendaraan::with('kendaraanSewa')
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->where('form_usulan_id', $form)
                ->get();

            return view('v_super_user/apk_aadb/surat_bast', compact('pimpinan','jenisAadb','bast','kendaraan','id'));

        } elseif ($aksi == 'print-surat-usulan') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan = UsulanAadb::join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('otp_usulan_pengusul', $id)
                ->get();

            return view('v_super_user/apk_aadb/print_surat_usulan', compact('pimpinan','usulan'));


            $cekForm = UsulanAadb::where('id_form_usulan', $id)->first();
            if ($cekForm->jenis_form == 1) {
                $kodeBast = UsulanAadb::where('id_form_usulan', $id)->update([
                    'kode_otp_bast'        => $request->kode_otp_bast,
                    'konfirmasi_pengajuan' => $request->konfirmasi
                ]);

                $pengadaanBaru  = new Kendaraan();
                $pengadaanBaru->id_kendaraan            = $request->id_kendaraan;
                $pengadaanBaru->jenis_aadb              = $request->jenis_aadb;
                $pengadaanBaru->form_usulan_id          = $id;
                $pengadaanBaru->unit_kerja_id           = Auth::user()->pegawai->unit_kerja_id;
                $pengadaanBaru->kode_barang             = $request->kode_barang;
                $pengadaanBaru->jenis_kendaraan_id      = $request->jenis_kendaraan_id ;
                $pengadaanBaru->merk_kendaraan          = $request->merk_kendaraan;
                $pengadaanBaru->tipe_kendaraan          = $request->tipe_kendaraan;
                $pengadaanBaru->no_plat_kendaraan       = $request->no_plat_kendaraan;
                $pengadaanBaru->mb_stnk_plat_kendaraan  = $request->mb_stnk_plat_kendaraan;
                $pengadaanBaru->no_plat_rhs             = $request->no_plat_rhs;
                $pengadaanBaru->mb_stnk_plat_rhs        = $request->mb_stnk_plat_rhs;
                $pengadaanBaru->no_bpkb                 = $request->no_bpkb;
                $pengadaanBaru->no_rangka               = $request->no_rangka;
                $pengadaanBaru->no_mesin                = $request->no_mesin;
                $pengadaanBaru->tahun_kendaraan         = $request->tahun_kendaraan;
                $pengadaanBaru->kondisi_kendaraan_id    = $request->kondisi_kendaraan_id;
                $pengadaanBaru->save();

                if($request->jenis_aadb == 'sewa') {
                    $cekPengadaanSewa = KendaraanSewa::count();
                    $pengadaanSewa  = new KendaraanSewa();
                    $pengadaanSewa->id_kendaraan_sewa = $cekPengadaanSewa + 1;
                    $pengadaanSewa->kendaraan_id = $request->id_kendaraan;
                    $pengadaanSewa->mulai_sewa   = $request->mulai_sewa;
                    $pengadaanSewa->penyedia     = $request->penyedia;
                    $pengadaanSewa->save();
                }

                UsulanAadb::where('id_form_usulan', $id)->update([ 'status_proses' => 'selesai' ]);

            } elseif ($cekForm->jenis_form == 2) {

            } elseif ($cekForm->jenis_form == 3) {

            } else {

            }

            return redirect('super-user/aadb/usulan/bast/'. $id);

        } elseif ($aksi == 'print-surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $id)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->first();

            $kendaraan = Kendaraan::with('kendaraanSewa')
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->where('form_usulan_id', $id)
                ->where('jenis_aadb','sewa')->get();

            return view('v_super_user/apk_aadb/print_surat_bast', compact('jenisAadb','pimpinan','bast','kendaraan','id'));

        }
    }

    public function Vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kendaraan = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->join('aadb_tbl_kondisi_kendaraan','id_kondisi_kendaraan','kondisi_kendaraan_id')
                ->orderBy('jenis_aadb','ASC')
                ->get();

            return view('v_super_user.apk_aadb.daftar_kendaraan', compact('kendaraan'));

        } elseif ($aksi == 'detail') {
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->first();
            $pengguna = RiwayatKendaraan::where('kendaraan_id', $id)->get();

            return view('v_super_user.apk_aadb.detail_kendaraan', compact('kendaraan','pengguna'));

        } elseif ($aksi == 'detail-json') {
            $result = Kendaraan::where('id_kendaraan', $request->kendaraanId)
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->get();

            return response()->json($result);

        } elseif ($aksi == 'select2') {
            $search = $request->search;

            if($search == '') {
                $kendaraan  = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                    ->orderBy('merk_kendaraan','ASC')
                    ->get();
            } else {
                $kendaraan  = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                    ->where('merk_kendaraan', 'like', '%' .$search . '%')
                    ->orWhere('tipe_kendaraan', 'like', '%' .$search . '%')
                    ->orWhere('tahun_kendaraan', 'like', '%' .$search . '%')
                    ->orderBy('merk_kendaraan','ASC')
                    ->get();
            }

            $response = array();
            foreach($kendaraan as $data){
                $response[] = array(
                    "id"    =>  $data->id_kendaraan,
                    "text"  =>  $data->merk_kendaraan.' '.$data->tipe_kendaraan.' tahun '.$data->tahun_kendaraan
                );
            }

            return response()->json($response);

        }
    }

    public function RecapAadb(Request $request)
    {
        $unitKerja      = UnitKerja::get();
        $jenisKendaraan = JenisKendaraan::get();
        $dataKendaraan  = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
             ->get();

        foreach ($unitKerja as $unker) {
            foreach($jenisKendaraan as $jenis) {
                $rekapUnker[$unker->unit_kerja][$jenis->jenis_kendaraan] =
                    $dataKendaraan->where('unit_kerja', $unker->unit_kerja)->where('jenis_kendaraan', $jenis->jenis_kendaraan)->count();
            }
        }

        return view('v_super_user.apk_aadb.daftar_rekap', compact('unitKerja','jenisKendaraan','rekapUnker'));
    }

    public function ReportAadb(Request $request, $aksi, $id)
    {
        if ($id == 'daftar') {
            $jenisForm      = JenisUsulan::where('jenis_form_usulan','like','%'. $aksi .'%')->first();
            $pengajuan      = UsulanAadb::get();
            $kategoriBarang = KategoriBarang::get();
            $chartPengajuan = $this->ChartReportAADB($jenisForm->id_jenis_form_usulan);
            $unitKerja      = UnitKerja::get();

            return view('v_super_user.apk_aadb.laporan', compact('aksi','kategoriBarang','pengajuan','chartPengajuan','unitKerja'));
        }
    }

    public function ChartReportAadb($pengajuan)
    {
        $dataPengajuan = UsulanAadb::where('jenis_form', $pengajuan)
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
            ->join('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
            ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
            ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
            ->orderBy('tanggal_usulan','DESC')
            ->get();

        $totalPengajuan = UsulanAadb::where('jenis_form', $pengajuan)
            ->select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
            ->groupBy('jenis_form','bulan')
            ->get();

        foreach ($totalPengajuan as $data) {
            $dataArray[] = Carbon::parse($data->bulan)->isoFormat('MMMM');
            $dataArray[] = $data->total_pengajuan;
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        // Report AADB
        $aadbUsulan     = UsulanAadb::where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%m-%Y'))"), Carbon::now()->isoFormat('M-Y'))->get();
        $aadbJenisForm  = JenisUsulan::get();
        foreach ($aadbJenisForm as $data) {
            $dataArray['usulan']  = $data->jenis_form_usulan;
            $dataArray['ditolak'] = $aadbUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['proses']  = $aadbUsulan->where('status_proses_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['selesai'] = $aadbUsulan->where('status_proses_id',5)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['bulan']   = Carbon::now()->isoFormat('MMMM Y');
            $reportAadb[] = $dataArray;
            unset($dataArray);
        }

        $dataChart['laporan']   = $reportAadb;
        $dataChart['pengajuan'] = $dataPengajuan;
        $chart = json_encode($dataChart);
        return $chart;
    }

    public function SearchChartReportAadb(Request $request)
    {
        $jenisForm     = JenisUsulan::where('jenis_form_usulan','like','%'. $request->form .'%')->first();

        $dataPengajuan = UsulanAadb::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->join('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
            ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
            ->orderBy('tanggal_usulan','DESC')
            ->where('jenis_form', $jenisForm->id_jenis_form_usulan);

        $totalPengajuan = UsulanAadb::select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->groupBy('jenis_form','bulan')
            ->where('jenis_form', $jenisForm->id_jenis_form_usulan);

        if($request->hasAny(['bulan', 'unit_kerja','status_pengajuan','status_proses'])){
            if($request->bulan){
                $dataSearchPengajuan = $dataPengajuan->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan);
                $dataTotalPengajuan  = $totalPengajuan->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan);
            }
            if($request->unit_kerja){
                $dataSearchPengajuan = $dataPengajuan->where('tbl_pegawai.unit_kerja_id',$request->unit_kerja);
                $dataTotalPengajuan  = $totalPengajuan->where('tbl_pegawai.unit_kerja_id',$request->unit_kerja);
            }
            if($request->status_pengajuan){
                $dataSearchPengajuan = $dataPengajuan->where('status_pengajuan_id', $request->status_pengajuan);
                $dataTotalPengajuan  = $totalPengajuan->where('status_pengajuan_id', $request->status_pengajuan);
            }
            if($request->status_proses){
                $dataSearchPengajuan = $dataPengajuan->where('status_proses_id', $request->status_proses);
                $dataTotalPengajuan  = $totalPengajuan->where('status_proses_id', $request->status_proses);
            }

            $dataSearchPengajuan = $dataSearchPengajuan->get();
            $dataTotalPengajuan  = $dataTotalPengajuan->get();
        }else {
            $dataSearchPengajuan = $dataPengajuan->get();
            $dataTotalPengajuan  = $totalPengajuan->get();
        }

        foreach ($dataTotalPengajuan as $data) {
            $dataArray[] = Carbon::parse($data->bulan)->isoFormat('MMMM');
            $dataArray[] = $data->total_pengajuan;
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        // Report AADB
        $aadbUsulan     = UsulanAadb::where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan)->get();
        $aadbJenisForm  = JenisUsulan::get();
        foreach ($aadbJenisForm as $data) {
            $dataArray['usulan']  = $data->jenis_form_usulan;
            $dataArray['ditolak'] = $aadbUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['proses']  = $aadbUsulan->where('status_proses_id',2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['selesai'] = $aadbUsulan->where('status_proses_id',5)->where('jenis_form', $data->id_jenis_form_usulan)->count();
            $dataArray['bulan']   = Carbon::parse($request->bulan)->isoFormat('MMMM Y');
            $reportAadb[] = $dataArray;
            unset($dataArray);
        }

        $dataChart['searchLaporan'] = $reportAadb;
        $dataChart['table'] = $dataSearchPengajuan;
        $chart = json_encode($dataChart);
        if(count($dataSearchPengajuan)>0){
            return response([
                'status' => true,
                'total' => count($dataSearchPengajuan),
                'message' => 'success',
                'data' => $chart
            ], 200);
        }else {
            return response([
                'status' => true,
                'total' => count($dataSearchPengajuan),
                'message' => 'not found'
            ], 200);
        }
    }

    public function ChartDataAadb()
    {
        $dataKendaraan = Kendaraan::select('id_kendaraan','unit_kerja_id','unit_kerja','jenis_aadb','jenis_kendaraan_id','jenis_kendaraan','merk_kendaraan','tipe_kendaraan','tahun_kendaraan','pengguna')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('aadb_tbl_jenis_kendaraan','jenis_kendaraan_id','id_jenis_kendaraan')
            ->get();

        $dataJenisKendaraan = JenisKendaraan::get();
        foreach ($dataJenisKendaraan as $data) {
            $dataArray[] = $data->jenis_kendaraan;
            $dataArray[] = $dataKendaraan->where('jenis_kendaraan', $data->jenis_kendaraan)->count();
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['kendaraan'] = $dataKendaraan;
        $chart = json_encode($dataChart);
        return $chart;
    }

    public function SearchChartDataAadb(Request $request)
    {
        $dataKendaraan = Kendaraan::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('aadb_tbl_jenis_kendaraan','jenis_kendaraan_id','id_jenis_kendaraan');

        $dataJenisKendaraan = JenisKendaraan::get();

        if($request->hasAny(['jenis_aadb', 'unit_kerja','jenis_kendaraan','merk_kendaraan', 'tahun_kendaraan', 'pengguna'])){
            if($request->jenis_aadb){
                $dataSearch = $dataKendaraan->where('jenis_aadb',$request->jenis_aadb);
            }
            if($request->unit_kerja){
                $dataSearch = $dataKendaraan->where('unit_kerja_id',$request->unit_kerja);
            }
            if($request->jenis_kendaraan){
                $dataSearch = $dataKendaraan->where('jenis_kendaraan_id',$request->jenis_kendaraan);
            }
            if($request->merk_kendaraan){
                $dataSearch = $dataKendaraan->where('merk_kendaraan',$request->merk_kendaraan);
            }
            if($request->tahun_kendaraan){
                $dataSearch = $dataKendaraan->where('tahun_kendaraan',$request->tahun_kendaraan);
            }
            if($request->pengguna){
                $dataSearch = $dataKendaraan->where('pengguna',$request->pengguna);
            }

            $dataSearch = $dataSearch->get();

        }else {
            $dataSearch = $dataKendaraan->get();
        }

        // dd($dataSearch);
        foreach ($dataJenisKendaraan as $data) {
            $dataArray[]          = $data->jenis_kendaraan;
            $dataArray[]          = $dataSearch->where('jenis_kendaraan', $data->jenis_kendaraan)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table']= $dataSearch;
        $chart = json_encode($dataChart);

        if(count($dataSearch) > 0){
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'success',
                'data'      => $chart
            ], 200);
        }else {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'not found'
            ], 200);
        }
    }

    public function Select2Aadb(Request $request, $aksi)
    {
        if ($aksi == 'kendaraan') {
            $search = $request->search;

            if ($search == '') {
                $kendaraan  = Kendaraan::select('id_kendaraan', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_kendaraan," ",tipe_kendaraan," / ",pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->get();
            } else {
                $kendaraan  = Kendaraan::select('id_kendaraan', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_kendaraan," ",tipe_kendaraan," / ",pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->where('merk_kendaraan', 'like', '%' . $search . '%')
                    ->where('no_plat_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('tipe_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('pengguna', 'like', '%' . $search . '%')
                    ->get();
            }

            $response = array();
            foreach ($kendaraan as $data) {
                $response[] = array(
                    "id"    =>  $data->id_kendaraan,
                    "text"  =>  $data->nama_kendaraan
                );
            }

            return response()->json($response);
        }
    }

    // ===============================================
    //                   OLDAT
    // ===============================================
    public function Oldat()
    {
        $timKerja        = TimKerja::get();
        $unitKerja       = UnitKerja::get();
        $googleChartData = $this->ChartDataOldat();
        if(Auth::user()->pegawai->jabatan_id == 2 || Auth::user()->pegawai->jabatan_id == 5) {
            $pengajuan  = FormUsulan::with('detailPengadaan')->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('status_proses_id','!=','5')
                ->orderBy('tanggal_usulan', 'DESC')->limit(5)
                ->paginate(5);
        } else {
            $pengajuan  = FormUsulan::with('detailPengadaan')->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('status_proses_id','!=','5')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->orderBy('tanggal_usulan', 'DESC')->limit(5)
                ->paginate(5);
        }

        return view('v_super_user.apk_oldat.index', compact('googleChartData','unitKerja','timKerja','pengajuan'));
    }

    public function Items(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $barang = Barang::join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->orderBy('tahun_perolehan','DESC')
                ->get();

            return view('v_super_user.apk_oldat.daftar_barang', compact('barang'));

        } elseif ($aksi == 'detail') {
            $barang = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', $id)
                ->first();
            $riwayat = RiwayatBarang::join('oldat_tbl_barang','id_barang','barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('tbl_pegawai','tbl_pegawai.id_pegawai','oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->where('barang_id', $id)
                ->get();

            return view('v_super_user.apk_oldat.detail_barang', compact('barang','riwayat'));
        }
    }

    public function ReportOldat(Request $request, $aksi, $id)
    {
        if ($id == 'daftar') {
            if($aksi == 'pengadaan') {
                $pengajuan      = FormUsulanPengadaan::get();
                $kategoriBarang = KategoriBarang::get();
                $chartPengajuan = $this->ChartReportOldat($aksi);
                $unitKerja      = UnitKerja::get();
                return view('v_super_user.apk_oldat.laporan', compact('aksi','kategoriBarang','pengajuan','chartPengajuan','unitKerja'));
            } else {
                $pengajuan      = FormUsulanPerbaikan::get();
                $kategoriBarang = KategoriBarang::get();
                $chartPengajuan = $this->ChartReportOldat($aksi);
                $unitKerja      = UnitKerja::get();
                return view('v_super_user.apk_oldat.laporan', compact('aksi','kategoriBarang','pengajuan','chartPengajuan','unitKerja'));
            }
        }
    }

    public function Recap(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriBarang     = KategoriBarang::get();
            $tahunPerolehan     = Barang::select('tahun_perolehan')->groupBy('tahun_perolehan')->orderBy('tahun_perolehan','ASC')->paginate(2);
            $unitKerja          = UnitKerja::get();
            $timKerja           = TimKerja::get();
            $dataBarang         = Barang::select('id_barang', 'kategori_barang','tahun_perolehan', 'pegawai_id', 'tim_kerja', 'unit_kerja')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->get();

                // dd($timKerja);

            $rekapTotalBarang   = Barang::select('kategori_barang', DB::raw('count(id_barang) as totalbarang'))
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->groupBy('kategori_barang')
                ->get();

            foreach ($tahunPerolehan as $dataTahunPerolehan) {
                foreach ($unitKerja as $dataUnitKerja) {
                    foreach ($timKerja as $dataTimKerja) {
                        foreach ($kategoriBarang as $dataKategoriBarang) {
                            $rekapTahunPerolehan[$dataTahunPerolehan->tahun_perolehan][$dataUnitKerja->unit_kerja][$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tahun_perolehan', $dataTahunPerolehan->tahun_perolehan)->where('unit_kerja', $dataUnitKerja->unit_kerja)->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();

                            $rekapUnitKerja[$dataUnitKerja->unit_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('unit_kerja', $dataUnitKerja->unit_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();

                            $rekapTimKerja[$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();
                        }
                    }
                }
            }
            // dd($rekapTahunPerolehan['2015']);

            return view('v_super_user.apk_oldat.daftar_rekap', compact('timKerja','tahunPerolehan','rekapTotalBarang','rekapTahunPerolehan', 'rekapUnitKerja', 'rekapTimKerja','kategoriBarang'));
        } else {
        }
    }

    public function SubmissionOldat(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            if(Auth::user()->pegawai->jabatan_id == 2 || Auth::user()->pegawai->jabatan_id == 5) {
                $formUsulan  = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')
                    ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                    ->orderBy('tanggal_usulan','DESC')
                    ->get();
            } else {
                $formUsulan  = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->orderBy('tanggal_usulan','DESC')
                ->get();
            }

            return view('v_super_user.apk_oldat.daftar_pengajuan', compact('formUsulan'));
        } elseif ($aksi == 'form-usulan') {
            $totalUsulan    = FormUsulan::where('jenis_form', $id)->count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $kategoriBarang = KategoriBarang::get();
            $pegawai    = Pegawai::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->first();

            return view('v_super_user.apk_oldat.form_usulan', compact('id','idUsulan','kategoriBarang', 'pegawai'));

        } elseif ($aksi == 'proses-pengajuan' && $id == 'pengadaan') {
            $idFormUsulan = Carbon::now()->format('dmy').rand(100,999). FormUsulan::count();
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan       = $idFormUsulan;
            $formUsulan->pegawai_id           = $request->input('pegawai_id');
            $formUsulan->kode_form            = 'OLDAT_001';
            $formUsulan->jenis_form           = 'pengadaan';
            $formUsulan->total_pengajuan      = array_sum($request->jumlah_barang);
            $formUsulan->tanggal_usulan       = $request->input('tanggal_usulan' );
            $formUsulan->rencana_pengguna     = $request->input('rencana_pengguna');
            $formUsulan->status_proses_id     = 1;
            $formUsulan->otp_usulan_pengusul  = $request->kode_otp;
            $formUsulan->no_surat_usulan      = $request->no_surat_usulan;
            $formUsulan->save();

            $barang = $request->kategori_barang_id;
            foreach ($barang as $i => $kategoriBarang)
            {
                $jumlah = $request->jumlah_barang[$i];
                for($x = 1; $x <= $jumlah; $x++) {
                    $cekDataDetail  = FormUsulanPengadaan::count();
                    $detailUsulan   = new FormUsulanPengadaan();
                    $detailUsulan->id_form_usulan_pengadaan  = $idFormUsulan.$cekDataDetail.$i;
                    $detailUsulan->form_usulan_id         = $idFormUsulan;
                    $detailUsulan->kategori_barang_id     = $kategoriBarang;
                    $detailUsulan->merk_barang            = $request->merk_barang[$i];
                    $detailUsulan->spesifikasi_barang     = $request->spesifikasi_barang[$i];
                    $detailUsulan->jumlah_barang          = 1;
                    $detailUsulan->satuan_barang          = $request->satuan_barang[$i];
                    $detailUsulan->estimasi_biaya         = $request->estimasi_biaya[$i];
                    $detailUsulan->save();
                }
            }

            return redirect('super-user/oldat/surat/surat-usulan/'. $request->kode_otp);
        } elseif ($aksi == 'proses-pengajuan' && $id == 'perbaikan') {
            $idFormUsulan = Carbon::now()->format('dmy').rand(100,999). FormUsulan::count();
            $cekData = FormUsulan::count();
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan = $idFormUsulan;
            $formUsulan->pegawai_id          = $request->input('pegawai_id');
            $formUsulan->kode_form           = 'OLDAT_001';
            $formUsulan->jenis_form          = 'perbaikan';
            $formUsulan->total_pengajuan     = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan      = $request->input('tanggal_usulan' );
            $formUsulan->rencana_pengguna    = $request->input('rencana_pengguna');
            $formUsulan->status_proses_id    = 1;
            $formUsulan->otp_usulan_pengusul = $request->kode_otp;
            $formUsulan->save();

            $barang = $request->kode_barang;
            foreach ($barang as $i => $kodeBarang)
            {
                $cekDataDetail  = FormUsulanPerbaikan::count();
                $detailUsulan   = new FormUsulanPerbaikan();
                $detailUsulan->id_form_usulan_perbaikan  = $idFormUsulan.$cekDataDetail.$i;
                $detailUsulan->form_usulan_id            = $idFormUsulan;
                $detailUsulan->barang_id                 = $kodeBarang;
                $detailUsulan->save();
            }

            return redirect('super-user/oldat/dashboard')->with('success','Berhasil membuat pengajuan');
        } elseif ($aksi == 'proses-diterima') {
            if ($request->status_usulan == 4) {
                FormUsulan::where('id_form_usulan', $id)->update([
                    'status_proses_id'     => 4,
                    'konfirmasi_pengajuan' => $request->konfirmasi,
                    'otp_bast_pengusul'    => $request->kode_otp
                ]);
            } elseif ($request->status_usulan == 5) {
                FormUsulan::where('id_form_usulan', $id)->update([
                    'status_proses_id'     => 5,
                    'otp_bast_kabag'    => $request->kode_otp
                ]);
            } else {
                FormUsulan::where('id_form_usulan', $id)->update([
                    'status_pengajuan_id' => 1,
                    'status_proses_id'    => 2,
                    'otp_usulan_kabag'    => $request->kode_otp
                ]);
            }

            return redirect('super-user/oldat/dashboard')->with('success','Berhasil melakukan konfirmasi usulan');

        } elseif ($aksi == 'proses-ditolak') {
            FormUsulan::where('kode_otp_usulan', $id)->update([ 'status_pengajuan' => 'tolak', 'status_proses' => 'selesai' ]);
            return redirect('super-user/oldat/dashboard')->with('failed','Usulan Pengajuan Ditolak');
        }
    }

    public function LetterOldat(Request $request, $aksi, $id)
    {
        if ($aksi == 'pengajuan') {
            $cekSurat       = FormUsulan::where('id_form_usulan', $id)->first();
            if ($cekSurat->jenis_form == 'pengadaan') {
                $suratPengajuan = FormUsulan::with('detailPengadaan')
                    ->join('tbl_pegawai','id_pegawai','pegawai_id')
                    ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->join('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                    ->where('id_form_usulan', $id)
                    ->get();
            } else {
                $suratPengajuan = FormUsulan::with('detailPerbaikan')
                    ->join('tbl_pegawai','id_pegawai','pegawai_id')
                    ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->join('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                    ->where('id_form_usulan', $id)
                    ->get();
            }

            return view('v_super_user.apk_oldat.surat_pengajuan', compact('suratPengajuan'));

        } elseif ($aksi == 'buat-bast') {
            $cekSurat   = FormUsulan::where('id_form_usulan', $id)->first();
            $tujuan     = 'BAST';
            $pengajuan  = FormUsulan::leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->get();
            return view('v_super_user.apk_oldat.buat_bast', compact('cekSurat','tujuan', 'pengajuan', 'id'));

        } elseif ($aksi == 'proses-bast') {
            $cekSurat   = FormUsulan::where('id_form_usulan', $id)->first();
            $idBarang   = $request->id_barang;
            $fotoBarang = $request->foto_barang;
            if ($cekSurat->jenis_form == 'pengadaan') {
                foreach ($idBarang as $i => $idBarang) {
                    if ($request->foto_barang[$i] != null) {
                        $barang = new FormUsulanPengadaan();
                        $foto = $request->file('foto_barang');
                        $filename  = Carbon::now()->format('ddmy') . $i . '_' . $request->foto_barang[$i]->getClientOriginalName();
                        $request->foto_barang[$i]->move('gambar/barang_bmn/', $filename);
                        $barang->foto_barang = $filename;
                    } else {
                        $filename = null;
                    }
                    // Update pengajuan
                    $fotoBarang = $request->foto_barang;
                    FormUsulan::where('id_form_usulan', $id)
                        ->update([
                            'status_proses'         => 'selesai',
                            'kode_otp_bast'         => $request->kode_otp,
                            'konfirmasi_pengajuan'  => $request->konfirmasi
                        ]);

                    FormUsulanPengadaan::where('id_form_usulan_pengadaan', $idBarang)
                    ->update([
                        'foto_barang' => $filename
                    ]);
                    // Tambah barang
                    $barang = new Barang();
                    $barang->id_barang          = rand(100000, 999999);
                    $barang->form_usulan_id     = $id;
                    $barang->unit_kerja_id      = Auth::user()->pegawai->unit_kerja_id;
                    $barang->kategori_barang_id = $request->kategori_barang_id[$i];
                    $barang->kode_barang        = $request->kode_barang[$i];
                    $barang->nup_barang         = $request->nup_barang[$i];
                    $barang->spesifikasi_barang = $request->merk_barang[$i] . ' ' . $request->spesifikasi_barang[$i];
                    $barang->jumlah_barang      = $request->jumlah_barang[$i];
                    $barang->satuan_barang      = $request->satuan_barang[$i];
                    $barang->nilai_perolehan    = $request->nilai_perolehan[$i];
                    $barang->tahun_perolehan    = $request->tahun_perolehan[$i];
                    $barang->kondisi_barang_id  = 1;
                    $barang->foto_barang        = $filename;
                    $barang->save();
                }
            } else {
                foreach ($idBarang as $i => $idBarang) {
                    // Update pengajuan
                    $fotoBarang = $request->foto_barang;
                    FormUsulan::where('id_form_usulan', $id)
                        ->update([
                            'status_proses'         => 'selesai',
                            'kode_otp_bast'         => $request->kode_otp,
                            'konfirmasi_pengajuan'  => $request->konfirmasi
                        ]);

                    Barang::where('id_barang', $idBarang)->update([ 'status_barang' => 2 ]);

                    $cekBarang = RiwayatBarang::count();
                    $riwayat   = new RiwayatBarang();
                    $riwayat->id_riwayat_barang    = $cekBarang + 1;
                    $riwayat->pegawai_id           = $request->pegawai_id;
                    $riwayat->barang_id            = $idBarang;
                    $riwayat->tanggal_pengguna     = $request->tanggal_pengguna;
                    $riwayat->kondisi_barang_id    = 1;
                    $riwayat->keperluan_penggunaan = $request->rencana_pengguna;
                    $riwayat->save();
                }
            }


            return redirect('super-user/oldat/surat/surat-bast/'.$request->kode_otp)->with('success','Berhasil membuat BAST');

        } elseif ($aksi == 'print-surat-bast') {
            $pegawai    = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')->where('id_form_usulan', $id)->first();
            if($pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $bast = FormUsulan::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->first();

            return view('v_super_user.apk_oldat.print_surat_bast', compact('pimpinan','bast'));
        } elseif ($aksi == 'surat-bast'){
            $pegawai    = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')->where('otp_bast_ppk', $id)->first();
            if($pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $bast = FormUsulan::where('otp_bast_ppk', $id)
                ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->first();
            return view('v_super_user.apk_oldat.surat_bast', compact('bast','pimpinan'));

        } elseif ($aksi == 'surat-usulan') {
            $pegawai        = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')->where('otp_usulan_pengusul', $id)->first();

            if($pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan  = FormUsulan::where('otp_usulan_pengusul', $id)
                ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->first();

            return view('v_super_user.apk_oldat.surat_usulan', compact('usulan','pimpinan'));
        } elseif ($aksi == 'print-surat-usulan') {
            $pegawai        = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')->where('id_form_usulan', $id)->first();

            if($pegawai->unit_kerja_id == 1) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',1)->first();
            } else {
                $pimpinan = null;
            }

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->first();

            return view('v_super_user.apk_oldat.print_surat_usulan', compact('usulan','pimpinan'));
        }
    }

    public function Select2Oldat(Request $request, $id)
    {
        if ($id == 'daftar') {
            $search = $request->search;

            if ($search == '') {
                $result  = Barang::select('id_barang', DB::raw('CONCAT(kode_barang,".",nup_barang," / ",merk_tipe_barang," / ",nama_pegawai," / ", unit_kerja) AS merk_tipe_barang'))
                    ->join('tbl_pegawai','id_pegawai','pegawai_id')
                    ->join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_pegawai.unit_kerja_id')
                    ->where('kategori_barang_id', $request->kategori)
                    ->pluck('id_barang','merk_tipe_barang');
            } else {
                $result  = Kendaraan::select('id_barang', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_kendaraan," ",tipe_kendaraan," / ",pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->where('merk_kendaraan', 'like', '%' . $search . '%')
                    ->where('no_plat_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('tipe_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('pengguna', 'like', '%' . $search . '%')
                    ->get();
            }

        } elseif ($id == 'detail') {
            $result   = Barang::join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id')
                ->where('id_barang', $request->idBarang)
                ->get();
        }

        // dd($result);
        return response()->json($result);

    }

    public function ChartReportOldat($pengajuan)
    {
        if ($pengajuan == 'pengadaan') {
            $dataPengajuan = FormUsulan::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->where('jenis_form', $pengajuan)
                ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
                ->orderBy('tanggal_usulan','DESC')
                ->get();

            $totalPengajuan = FormUsulan::select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
                ->groupBy('jenis_form','bulan')
                ->where('jenis_form', $pengajuan)
                ->get();
        } else {
            $dataPengajuan = FormUsulan::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->join('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where('jenis_form', $pengajuan)
                ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
                ->orderBy('tanggal_usulan','DESC')
                ->get();

            $totalPengajuan = FormUsulan::select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->format('Y-m'))
                ->groupBy('jenis_form','bulan')
                ->where('jenis_form', $pengajuan)
                ->get();
        }

        foreach ($totalPengajuan as $data) {
            $dataArray[] = Carbon::parse($data->bulan)->isoFormat('MMMM');
            $dataArray[] = $data->total_pengajuan;
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        // Laporan
        $oldatUsulan    = FormUsulan::where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), Carbon::now()->isoFormat('Y-M'))->get();
        $oldatJenisForm = FormUsulan::select('jenis_form')->groupBy('jenis_form')->get();
        foreach ($oldatJenisForm as $data) {
            $dataArray['usulan']  = $data->jenis_form;
            $dataArray['ditolak'] = $oldatUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['proses']  = $oldatUsulan->where('status_proses_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['selesai'] = $oldatUsulan->where('status_proses_id',5)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['bulan']   = Carbon::now()->isoFormat('MMMM Y');
            $reportOldat[] = $dataArray;
            unset($dataArray);
        }
        $dataChart['laporan'] = $reportOldat;
        $dataChart['pengajuan'] = $dataPengajuan;
        $chart = json_encode($dataChart);
        // dd($chart);
        return $chart;
    }

    public function SearchChartReportOldat(Request $request)
    {
        $dataPengajuan = FormUsulan::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->join('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
            ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
            ->orderBy('tanggal_usulan','DESC')
            ->where('jenis_form', $request->form);

        $totalPengajuan = FormUsulan::select('jenis_form', DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as bulan"), DB::raw("count(id_form_usulan) as total_pengajuan"))
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->groupBy('jenis_form','bulan')
            ->where('jenis_form', $request->form);

        if($request->hasAny(['bulan', 'unit_kerja','status_pengajuan','status_proses'])){
            if($request->bulan){
                $dataSearchPengajuan = $dataPengajuan->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan);
                $dataTotalPengajuan  = $totalPengajuan->where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan);
            }
            if($request->unit_kerja){
                $dataSearchPengajuan = $dataPengajuan->where('tbl_pegawai.unit_kerja_id',$request->unit_kerja);
                $dataTotalPengajuan  = $totalPengajuan->where('tbl_pegawai.unit_kerja_id',$request->unit_kerja);
            }
            if($request->status_pengajuan){
                $dataSearchPengajuan = $dataPengajuan->where('status_pengajuan_id', $request->status_pengajuan);
                $dataTotalPengajuan  = $totalPengajuan->where('status_pengajuan_id', $request->status_pengajuan);
            }
            if($request->status_proses){
                $dataSearchPengajuan = $dataPengajuan->where('status_proses_id', $request->status_proses);
                $dataTotalPengajuan  = $totalPengajuan->where('status_proses_id', $request->status_proses);
            }

            $dataSearchPengajuan = $dataSearchPengajuan->get();
            $dataTotalPengajuan  = $dataTotalPengajuan->get();
        }else {
            $dataSearchPengajuan = $dataPengajuan->get();
            $dataTotalPengajuan  = $totalPengajuan->get();
        }

        foreach ($dataTotalPengajuan as $data) {
            $dataArray[] = Carbon::parse($data->bulan)->isoFormat('MMMM');
            $dataArray[] = $data->total_pengajuan;
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        // Laporan
        $oldatUsulan    = FormUsulan::where(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m'))"), $request->bulan)->get();
        $oldatJenisForm = FormUsulan::select('jenis_form')->groupBy('jenis_form')->get();
        foreach ($oldatJenisForm as $data) {
            $dataArray['usulan'] = $data->jenis_form;
            $dataArray['ditolak'] = $oldatUsulan->where('status_pengajuan_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['proses'] = $oldatUsulan->where('status_proses_id',2)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['selesai'] = $oldatUsulan->where('status_proses_id',5)->where('jenis_form', $data->jenis_form)->count();
            $dataArray['bulan']   = Carbon::parse($request->bulan)->isoFormat('MMMM Y');
            $reportOldat[] = $dataArray;
            unset($dataArray);
        }

        $dataChart['searchLaporan'] = $reportOldat;
        $dataChart['table'] = $dataSearchPengajuan;
        $chart = json_encode($dataChart);

        if(count($dataSearchPengajuan)>0){
            return response([
                'status' => true,
                'total' => count($dataSearchPengajuan),
                'message' => 'success',
                'data' => $chart
            ], 200);
        }else {
            return response([
                'status' => true,
                'total' => count($dataSearchPengajuan),
                'message' => 'not found'
            ], 200);
        }
    }

    public function ChartDataOldat()
    {
        $dataBarang = Barang::select('id_barang', 'kategori_barang', 'unit_kerja', 'pegawai_id','tim_kerja','tahun_perolehan','merk_tipe_barang','spesifikasi_barang', 'kondisi_barang','nama_pegawai')
            ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'id_kondisi_barang', 'kondisi_barang_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->orderBy('tahun_perolehan','DESC')
            ->get();


        $dataKategoriBarang = KategoriBarang::get();
        foreach ($dataKategoriBarang as $data) {
            $dataArray[] =$data->kategori_barang;
            $dataArray[] = $dataBarang->where('kategori_barang', $data->kategori_barang)->count();
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['barang'] = $dataBarang;
        $chart = json_encode($dataChart);
        return $chart;
    }

    public function SearchChartDataOldat(Request $request){
        $dataBarang = Barang::select('id_barang','kategori_barang','pegawai_id','id_unit_kerja','unit_kerja',
                                     'oldat_tbl_barang.unit_kerja_id','id_tim_kerja','tim_kerja','tahun_perolehan','merk_tipe_barang',
                                     'spesifikasi_barang', 'kondisi_barang','nama_pegawai')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'id_kondisi_barang', 'kondisi_barang_id')
            ->join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','oldat_tbl_barang.unit_kerja_id')
            ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->orderBy('tahun_perolehan','DESC');


        $dataKategoriBarang = KategoriBarang::get();

        if($request->hasAny(['tahun', 'unit_kerja','tim_kerja'])){
            if($request->tahun){
                $dataSearchBarang = $dataBarang->where('tahun_perolehan',$request->tahun);
            }
            if($request->unit_kerja){
                $dataSearchBarang = $dataBarang->where('oldat_tbl_barang.unit_kerja_id',$request->unit_kerja);
            }
            if($request->tim_kerja){
                $dataSearchBarang = $dataBarang->where('id_tim_kerja',$request->tim_kerja);
            }

            $dataSearchBarang = $dataSearchBarang->get();

        }else {
            $dataSearchBarang = $dataBarang->get();
        }

        foreach ($dataKategoriBarang as $data) {
            $dataArray[] = $data->kategori_barang;
            $dataArray[] = $dataSearchBarang->where('kategori_barang', $data->kategori_barang)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }
        // dd($dataChart);
        $dataChart['table']= $dataSearchBarang;
        $chart = json_encode($dataChart);

        if(count($dataSearchBarang)>0){
            return response([
                'status' => true,
                'total' => count($dataSearchBarang),
                'message' => 'success',
                'data' => $chart
            ], 200);
        }else {
            return response([
                'status' => true,
                'total' => count($dataSearchBarang),
                'message' => 'not found'
            ], 200);
        }
    }

    // ===============================================
    //                   PPK
    // ===============================================

    public function SubmissionPpk(Request $request, $modul, $tujuan, $aksi, $id)
    {
        if($modul == 'oldat')
        {
            $totalUsulan    = FormUsulan::where('jenis_form', $id)->count();
            $idBast         = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            if ($aksi == 'proses-pengadaan') {
                $idForm = $request->detail_usulan_id;
                foreach ($idForm as $i => $id_form_usulan_pengadaan) {
                    FormUsulanPengadaan::where('id_form_usulan_pengadaan', $id_form_usulan_pengadaan)->update([
                        'nilai_perolehan' => $request->nilai_perolehan[$i],
                        'nomor_kontrak'   => $request->nomor_kontrak[$i],
                        'nomor_kwitansi'  => $request->nomor_kwitansi[$i]
                    ]);
                    $total[$i] =+ $request->nilai_perolehan[$i];
                }

                FormUsulan::where('id_form_usulan', $id)->update([
                    'tanggal_bast'     => $request->tanggal_bast,
                    'total_biaya'      => array_sum($total),
                    'no_surat_bast'    => $request->no_surat_bast,
                    'status_proses_id' => 3,
                    'otp_bast_ppk'     => $request->otp_bast_ppk
                ]);

                $idBarang = $request->id_barang;
                foreach($idBarang as $i => $id_barang)
                {
                    if ($request->foto_barang[$i] != null) {
                        $barang = new FormUsulanPengadaan();
                        $foto = $request->file('foto_barang');
                        $filename  = Carbon::now()->format('ddmy') . $i . '_' . $request->foto_barang[$i]->getClientOriginalName();
                        $request->foto_barang[$i]->move('gambar/barang_bmn/', $filename);
                        $barang->foto_barang = $filename;
                    } else {
                        $filename = null;
                    }

                    $tambahBarang = new Barang();
                    $tambahBarang->id_barang             = $id_barang;
                    $tambahBarang->form_usulan_id        = $request->id_form_usulan;
                    $tambahBarang->unit_kerja_id         = $request->unit_kerja_id;
                    $tambahBarang->kategori_barang_id    = $request->kategori_barang_id[$i];
                    $tambahBarang->kode_barang           = $request->kode_barang[$i];
                    $tambahBarang->nup_barang            = $request->nup_barang[$i];
                    $tambahBarang->merk_tipe_barang      = $request->merk_tipe_barang[$i];
                    $tambahBarang->spesifikasi_barang    = $request->spesifikasi_barang[$i];
                    $tambahBarang->jumlah_barang         = $request->jumlah_barang[$i];
                    $tambahBarang->satuan_barang         = $request->satuan_barang[$i];
                    $tambahBarang->kondisi_barang_id     = 1;
                    $tambahBarang->nilai_perolehan       = $request->nilai_perolehan[$i];
                    $tambahBarang->tahun_perolehan       = $request->tahun_perolehan[$i];
                    $tambahBarang->foto_barang           = $filename;
                    $tambahBarang->save();
                }

                return redirect('super-user/oldat/dashboard')->with('success', 'Berhasil Memproses Usulan');

            } elseif ($aksi == 'proses-perbaikan') {

                if ($request->foto_kwitansi == null) {
                    $fotoKwitansi = $request->foto_lama;
                } else {
                    $dataUsulan = FormUsulan::where('id_form_usulan', $id)->first();

                    if ($request->hasfile('foto_kwitansi')) {
                        if ($dataUsulan->lampiran != ''  && $dataUsulan->lampiran != null) {
                            $file_old = public_path() . '\gambar\kwitansi\oldat_perbaikan\\' . $dataUsulan->lampiran;
                            unlink($file_old);
                        }
                        $file       = $request->file('foto_kwitansi');
                        $filename   = $file->getClientOriginalName();
                        $file->move('gambar/kwitansi/oldat_perbaikan/', $filename);
                        $dataUsulan->lampiran = $filename;
                    } else {
                        $dataUsulan->lampiran = '';
                    }
                    $fotoKwitansi = $dataUsulan->lampiran;
                }


                FormUsulan::where('id_form_usulan', $request->id_form_usulan)->update([
                    'tanggal_bast'     => $request->tanggal_bast,
                    'nilai_perolehan'  => $request->total_biaya,
                    'lampiran'         => $fotoKwitansi,
                    'no_surat_bast'    => $request->no_surat_bast,
                    'status_proses_id' => 3,
                    'otp_bast_ppk'     => $request->otp_bast_ppk
                ]);

                return redirect('super-user/oldat/dashboard')->with('success', 'Berhasil Memproses Usulan');
            } else {
                $form       = $aksi;
                $tujuan     = 'BAST';
                $pengajuan  = FormUsulan::leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                    ->where('id_form_usulan', $id)
                    ->get();
                return view('v_super_user.apk_oldat.ppk_proses', compact('idBast','tujuan', 'pengajuan', 'id','form'));
            }
        }

        if ($modul == 'aadb')
        {
            if ($tujuan == 'pengajuan') {

                $totalUsulan = UsulanAadb::count();
                $idBast      = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $form        = $aksi;
                $tujuan      = 'BAST';
                $pengajuan   = UsulanAadb::leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                    ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
                    ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                    ->where('id_form_usulan', $id)
                    ->first();
                return view('v_super_user.apk_aadb.ppk_proses', compact('idBast','tujuan', 'pengajuan','aksi', 'id','form'));
            }

            if ($tujuan == 'proses-usulan')
            {
                if ($aksi == 'pengadaan') {
                    if ($request->foto_kwitansi == null) {
                        $fotoKwitansi = $request->foto_lama;
                    } else {
                        $dataUsulan = UsulanAadb::where('id_form_usulan', $id)->first();

                        if ($request->hasfile('foto_kwitansi')) {
                            if ($dataUsulan->lampiran != ''  && $dataUsulan->lampiran != null) {
                                $file_old = public_path() . '\gambar\kwitansi\pengadaan\\' . $dataUsulan->lampiran;
                                unlink($file_old);
                            }
                            $file       = $request->file('foto_kwitansi');
                            $filename   = $file->getClientOriginalName();
                            $file->move('gambar/kwitansi/pengadaan/', $filename);
                            $dataUsulan->lampiran = $filename;
                        } else {
                            $dataUsulan->lampiran = '';
                        }
                        $fotoKwitansi = $dataUsulan->lampiran;
                    }

                    UsulanAadb::where('id_form_usulan', $request->id_form_usulan)->update([
                        'tanggal_bast'     => $request->tanggal_bast,
                        'total_biaya'      => $request->total_biaya,
                        'lampiran'         => $fotoKwitansi,
                        'no_surat_bast'    => $request->no_surat_bast,
                        'status_proses_id' => 3,
                        'otp_bast_ppk'     => $request->otp_bast_ppk
                    ]);

                    $kendaraan = new Kendaraan();
                    $kendaraan->id_kendaraan            = $request->id_kendaraan;
                    $kendaraan->form_usulan_id          = $request->id_form_usulan;
                    $kendaraan->unit_kerja_id           = 1;
                    $kendaraan->jenis_aadb              = $request->jenis_aadb;
                    $kendaraan->kode_barang             = $request->kode_barang;
                    $kendaraan->jenis_kendaraan_id      = $request->jenis_kendaraan;
                    $kendaraan->merk_kendaraan          = $request->merk_kendaraan;
                    $kendaraan->tipe_kendaraan          = $request->tipe_kendaraan;
                    $kendaraan->no_plat_kendaraan       = $request->no_plat_kendaraan;
                    $kendaraan->mb_stnk_plat_kendaraan  = $request->mb_stnk_plat_kendaraan;
                    $kendaraan->no_plat_rhs             = $request->no_plat_rhs;
                    $kendaraan->mb_stnk_plat_rhs        = $request->mb_stnk_plat_rhs;
                    $kendaraan->tahun_kendaraan         = $request->tahun_kendaraan;
                    $kendaraan->kondisi_kendaraan_id    = 1;
                    $kendaraan->save();

                    if ($request->jenis_aadb == 'sewa') {
                        $idKendaraanSewa = KendaraanSewa::count();
                        $kendaraanSewa = new KendaraanSewa();
                        $kendaraanSewa->id_kendaraan_sewa   = $idKendaraanSewa + 1;
                        $kendaraanSewa->kendaraan_id        = $request->id_kendaraan;
                        $kendaraanSewa->mulai_sewa          = $request->mulai_sewa;
                        $kendaraanSewa->penyedia            = $request->penyedia;
                        $kendaraanSewa->save();
                    }

                    return redirect('super-user/aadb/dashboard')->with('success', 'Berhasil Memproses Usulan');

                } elseif ($aksi == 'servis') {
                    if ($request->foto_kwitansi == null) {
                        $fotoKwitansi = $request->foto_lama;
                    } else {
                        $dataUsulan = UsulanAadb::where('id_form_usulan', $id)->first();

                        if ($request->hasfile('foto_kwitansi')) {
                            if ($dataUsulan->lampiran != ''  && $dataUsulan->lampiran != null) {
                                $file_old = public_path() . '\gambar\kwitansi\servis\\' . $dataUsulan->lampiran;
                                unlink($file_old);
                            }
                            $file       = $request->file('foto_kwitansi');
                            $filename   = $file->getClientOriginalName();
                            $file->move('gambar/kwitansi/servis/', $filename);
                            $dataUsulan->lampiran = $filename;
                        } else {
                            $dataUsulan->lampiran = '';
                        }
                        $fotoKwitansi = $dataUsulan->lampiran;
                    }

                    UsulanAadb::where('id_form_usulan', $id)->update([
                        'tanggal_bast'     => $request->tanggal_bast,
                        'total_biaya'      => $request->total_biaya,
                        'lampiran'         => $fotoKwitansi,
                        'no_surat_bast'    => $request->no_surat_bast,
                        'status_proses_id' => 3,
                        'otp_bast_ppk'     => $request->otp_bast_ppk
                    ]);

                    return redirect('super-user/aadb/dashboard')->with('success','Berhasil memproses usulan servis kendaraan');
                } elseif ($aksi == 'perpanjangan-stnk') {
                    if ($request->foto_stnk == null) {
                        $fotoStnk = $request->foto_lama;
                    } else {
                        $dataUsulan = UsulanAadb::where('id_form_usulan', $id)->first();

                        if ($request->hasfile('foto_stnk')) {
                            if ($dataUsulan->lampiran != ''  && $dataUsulan->lampiran != null) {
                                $file_old = public_path() . '\gambar\kendaraan\stnk\\' . $dataUsulan->lampiran;
                                unlink($file_old);
                            }
                            $file       = $request->file('foto_stnk');
                            $filename   = $file->getClientOriginalName();
                            $file->move('gambar/kendaraan/stnk/', $filename);
                            $dataUsulan->lampiran = $filename;
                        } else {
                            $dataUsulan->lampiran = '';
                        }
                        $fotoStnk = $dataUsulan->lampiran;
                    }

                    UsulanAadb::where('id_form_usulan', $id)->update([
                        'tanggal_bast'     => $request->tanggal_bast,
                        'total_biaya'      => $request->total_biaya,
                        'lampiran'         => $fotoStnk,
                        'no_surat_bast'    => $request->no_surat_bast,
                        'status_proses_id' => 3,
                        'otp_bast_ppk'     => $request->otp_bast_ppk
                    ]);

                    $form = $request->detail_usulan_id;
                    foreach($form as $i => $detail_usulan_id)
                    {
                        UsulanPerpanjanganSTNK::where('id_form_usulan_perpanjangan_stnk', $detail_usulan_id)
                            ->update([
                                'mb_stnk_baru' => $request->mb_stnk_baru[$i]
                            ]);
                    }

                    return redirect('super-user/aadb/dashboard')->with('success','Berhasil memproses usulan perpanjang STNK kendaraan');
                } elseif ($aksi == 'voucher-bbm') {
                    UsulanAadb::where('id_form_usulan', $id)->update([
                        'tanggal_bast'     => $request->tanggal_bast,
                        'no_surat_bast'    => $request->no_surat_bast,
                        'status_proses_id' => 3,
                        'otp_bast_ppk'     => $request->otp_bast_ppk
                    ]);


                    return redirect('super-user/aadb/dashboard')->with('success','Berhasil memproses usulan voucher bbm kendaraan');
                }
            }
        }
    }

    // public function OTPQiscus(Request $request) {
    //     $APPID          = "xhcyl-gdjqycflhpdmf1g";
    //     $channelId      = "3297";
    //     $penerima       = '6285772252563';
    //     $otp            = rand(1000,9999);
    //     $client         = new GuzzleHttpClient();

    //     $headers = [
    //         'Content-Type'      => 'application/json',
    //         'Qiscus-App-Id'     => 'xhcyl-gdjqycflhpdmf1g',
    //         'Qiscus-Secret-Key' => 'f9fe5cc6b488fb6ea5aabfd42351aba4'
    //     ];

    //     $body = '{
    //         "messaging_product": "whatsapp",
    //         "to": '.$penerima.',
    //         "text": {
    //             "body": "your-message-content"
    //         }
    //     }';

    //     $request = new Psr7Request('POST', 'https://graph.facebook.com/'.$APPID.'/'.$channelId.'/messages', $headers, $body);
    //     $res = $client->sendAsync($request)->wait();
    //     return $otp;
    // }

}
