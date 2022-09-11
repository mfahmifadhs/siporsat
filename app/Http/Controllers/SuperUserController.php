<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\KondisiBarang;
use App\Models\TimKerja;
use App\Models\FormUsulan;
use App\Models\FormUsulanPengadaan;
use App\Models\FormUsulanPerbaikan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use DB;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;

class SuperUserController extends Controller
{
    public function index()
    {
        return view('v_super_user.index');
    }


    // ===============================================
    //                   OLDAT
    // ===============================================
    public function oldat()
    {
        $timKerja   = TimKerja::get();
        $unitKerja  = UnitKerja::get();
        $chartData  = $this->getChartData();
        $pengajuanPengadaan  = FormUsulan::with('detailPengadaan')
            ->join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->where('jenis_form','pengadaan')
            ->where('status_proses','!=','selesai')
            ->orderBy('tanggal_usulan', 'DESC')
            ->limit(5)
            ->get();
        $pengajuanPerbaikan  = FormUsulan::with('detailPerbaikan')
            ->join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->where('jenis_form','perbaikan')
            ->where('status_proses','!=','selesai')
            ->orderBy('tanggal_usulan', 'DESC')
            ->limit(5)
            ->get();

        return view('v_super_user.apk_oldat.index', compact('chartData','unitKerja','timKerja','pengajuanPengadaan', 'pengajuanPerbaikan'));
    }

    public function report(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::get();
            $barang         = Barang::join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->get();
            return view('v_super_user.apk_oldat.daftar_laporan', compact('kategoriBarang', 'kondisiBarang','pegawai', 'barang'));
        }
    }

    public function recap(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriBarang     = KategoriBarang::get();
            $tahunPerolehan      = Barang::select('tahun_perolehan')->groupBy('tahun_perolehan')->orderBy('tahun_perolehan','ASC')->get();
            $unitKerja          = UnitKerja::get();
            $timKerja           = TimKerja::get();
            $dataBarang         = Barang::select('id_barang', 'kategori_barang','tahun_perolehan', 'pegawai_id', 'tim_kerja', 'unit_kerja')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->get();

                dd($dataBarang);

            $rekapTotalBarang   = Barang::select('kategori_barang', DB::raw('count(id_barang) as totalbarang'))
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->groupBy('kategori_barang')
                ->get();

            foreach ($tahunPerolehan as $dataTahunPerolehan) {
                foreach ($unitKerja as $dataUnitKerja) {
                    foreach ($timKerja as $dataTimKerja) {
                        foreach ($kategoriBarang as $dataKategoriBarang) {
                            $rekapTahunPerolehan[$dataTahunPerolehan->tahun_perolehan][$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tahun_perolehan', $dataTahunPerolehan->tahun_perolehan)->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();

                            $rekapUnitKerja[$dataUnitKerja->unit_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('unit_kerja', $dataUnitKerja->unit_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();

                            $rekapTimKerja[$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();
                        }
                    }
                }
            }

            return view('v_super_user.apk_oldat.daftar_rekap', compact('timKerja','tahunPerolehan','rekapTotalBarang','rekapTahunPerolehan', 'rekapUnitKerja', 'rekapTimKerja','kategoriBarang'));
        } else {
        }
    }

    public function submission(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $formUsulan  = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();
            return view('v_super_user.apk_oldat.daftar_pengajuan', compact('formUsulan'));
        } elseif ($aksi == 'form-usulan') {
            $kategoriBarang = KategoriBarang::get();
            $pegawai    = Pegawai::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->first();

            return view('v_super_user.apk_oldat.form_usulan', compact('id','kategoriBarang', 'pegawai'));

        } elseif ($aksi == 'detail') {
            dd('detail');
        } elseif ($aksi == 'proses-pengajuan' && $id == 'pengadaan') {
            $cekData = FormUsulan::count();
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan    = 'pengadaan_'.($cekData+1);
            $formUsulan->pegawai_id        = $request->input('pegawai_id');
            $formUsulan->kode_form         = 'OLDAT_001';
            $formUsulan->jenis_form        = 'pengadaan';
            $formUsulan->total_pengajuan   = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan    = $request->input('tanggal_usulan' );
            $formUsulan->rencana_pengguna  = $request->input('rencana_pengguna');
            $formUsulan->status_proses     = 'belum proses';
            $formUsulan->kode_otp          = $request->kode_otp;
            $formUsulan->save();

            $barang = $request->kategori_barang_id;
            foreach ($barang as $i => $kategoriBarang)
            {
                $cekDataDetail  = FormUsulanPengadaan::count();
                $detailUsulan   = new FormUsulanPengadaan();
                $detailUsulan->id_form_usulan_pengadaan  = $cekDataDetail + 1;
                $detailUsulan->form_usulan_id         = 'pengadaan_'.($cekData + 1);
                $detailUsulan->kategori_barang_id     = $kategoriBarang;
                $detailUsulan->merk_barang            = $request->merk_barang[$i];
                $detailUsulan->spesifikasi_barang     = $request->spesifikasi_barang[$i];
                $detailUsulan->jumlah_barang          = $request->jumlah_barang[$i];
                $detailUsulan->satuan_barang          = $request->satuan_barang[$i];
                $detailUsulan->save();
            }

            return redirect('super-user/oldat/dashboard');
        } elseif ($aksi == 'proses-pengajuan' && $id == 'perbaikan') {
            $otp = rand(1000,9999);
            $cekData = FormUsulan::count();
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan = 'perbaikan_'.($cekData + 1);
            $formUsulan->pegawai_id = $request->input('pegawai_id');
            $formUsulan->kode_form  = 'OLDAT_001';
            $formUsulan->jenis_form = 'perbaikan';
            $formUsulan->total_pengajuan   = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan    = $request->input('tanggal_usulan' );
            $formUsulan->rencana_pengguna  = $request->input('rencana_pengguna');
            $formUsulan->status_proses     = 'belum proses';
            $formUsulan->kode_otp          = $otp;
            $formUsulan->save();

            $barang = $request->kode_barang;
            foreach ($barang as $i => $kodeBarang)
            {
                $cekDataDetail  = FormUsulanPerbaikan::count();
                $detailUsulan   = new FormUsulanPerbaikan();
                $detailUsulan->id_form_usulan_perbaikan  = $cekDataDetail + 1;
                $detailUsulan->form_usulan_id            = 'perbaikan_'.($cekData + 1);
                $detailUsulan->barang_id               = $kodeBarang;
                $detailUsulan->save();
            }

            return redirect('super-user/oldat/dashboard')->with('success','Berhasil membuat pengajuan');
        } elseif ($aksi == 'proses-diterima') {
            FormUsulan::where('kode_otp', $id)->update([ 'status_pengajuan' => 'terima', 'status_proses' => 'proses' ]);
            return redirect('super-user/oldat/dashboard')->with('success','Usulan Pengajuan Berhasil Diterima');
        } elseif ($aksi == 'proses-ditolak') {
            FormUsulan::where('kode_otp', $id)->update([ 'status_pengajuan' => 'tolak', 'status_proses' => 'selesai' ]);
            return redirect('super-user/oldat/dashboard')->with('failed','Usulan Pengajuan Ditolak');
        }
    }

    public function getChartData()
    {
        $dataBarang = Barang::select('id_barang', 'kategori_barang', 'unit_kerja', 'pegawai_id','tim_kerja')
            ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
            ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
            ->get();

        $dataKategoriBarang = KategoriBarang::get();
        foreach ($dataKategoriBarang as $data) {
            $labelChart[] = $data->kategori_barang;
            $dataChart[] = $dataBarang->where('kategori_barang', $data->kategori_barang)->count();
        }
        $resultChart['label'] = $labelChart;
        $resultChart['data'] = $dataChart;
        $chart = json_encode($resultChart);

        // dd($chart);
        return $chart;
    }

    public function searchChartData(Request $request){
        $dataBarang = Barang::select('id_barang','kategori_barang','pegawai_id','id_unit_kerja','oldat_tbl_barang.unit_kerja_id','id_tim_kerja','tim_kerja','tahun_perolehan')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
            ->join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','oldat_tbl_barang.unit_kerja_id')
            ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id');


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

        foreach($dataKategoriBarang as $data){
            $labelChart[] = $data->kategori_barang;
            $dataChart[] = $dataSearchBarang->where('kategori_barang',$data->kategori_barang)->count();
        }

        $resultChart['label'] = $labelChart;
        $resultChart['data'] = $dataChart;
        $chart = json_encode($resultChart);

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

    public function getDataBarang(Request $request, $id)
    {
        $user     = Auth()->user();
        $pegawai  = $user->pegawai;
        if ($id == 'daftar') {
            $result   = Barang::join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id')
                ->where('unit_kerja_id', $pegawai->unit_kerja_id)
                ->where('kategori_barang_id', $request->kategori)
                ->pluck('id_barang','spesifikasi_barang');

        } elseif ($id == 'detail') {
            $result   = Barang::join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id')
                ->where('id_barang', $request->idBarang)
                ->get();
        }
        return response()->json($result);

    }

    public function sendOTPWhatsapp(Request $request){
        $user           = Auth::user();
        $pegawai        = $user->pegawai;
        $version        = getenv("WHATSAPP_API_VERSION");
        $token          = getenv("WHATSAPP_API_token");
        $phoneNumberId  = getenv("WHATSAPP_API_PHONE_NUMBER_ID");
        $penerima       = '6285772652563';
        $otp            = rand(1000,9999);

        // if($request->jenisForm == 'pengadaan') {
        //     $formUsulan = new FormUsulan();
        //     $formUsulan->id_form_usulan = 'pengadaan_'.(rand(100,999));
        //     $formUsulan->pegawai_id     = Auth::user()->pegawai_id;
        //     $formUsulan->kode_form      = 'OLDAT_001';
        //     $formUsulan->jenis_form     = 'pengadaan';
        //     $formUsulan->kode_otp       = $otp;
        //     $formUsulan->save();
        // }

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
            "name": "siporsat_otp",
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
                        "text": "'.$request->jenisForm.'"
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

    public function getForm(Request $request) {
        $result = FormUsulan::where('id_form_usulan', $request->kode_otp)->first();
        return $result;
    }
}
