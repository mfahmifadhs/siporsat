<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\KondisiBarang;
use App\Models\TimKerja;
use App\Models\FormUsulan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use DB;

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
        $timKerja = TimKerja::get();
        $unitKerja = UnitKerja::get();
        $chartData = $this->getChartData();
        return view('v_super_user.apk_oldat.index', ['chartData' => $chartData, 'unitKerja' => $unitKerja, 'timKerja' => $timKerja]);
    }

    public function getChartData()
    {
        $dataBarang = Barang::select('id_barang', 'kategori_barang', 'pegawai_id', 'unit_kerja', 'tim_kerja')
            ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
            ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
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
        $dataBarang = Barang::select('id_barang','kategori_barang','pegawai_id','id_unit_kerja','unit_kerja','id_tim_kerja','tim_kerja','tahun_perolehan')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
            ->join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->join('tbl_tim_kerja','id_tim_kerja','tim_kerja_id');

        $dataKategoriBarang = KategoriBarang::get();

        if($request->hasAny(['tahun', 'unit_kerja','tim_kerja'])){
            if($request->tahun){
                $dataSearchBarang = $dataBarang->where('tahun_perolehan',$request->tahun);
            }
            if($request->unit_kerja){
                $dataSearchBarang = $dataBarang->where('id_unit_kerja',$request->unit_kerja);
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

    public function report(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::get();
            $barang         = Barang::join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->get();
            return view('v_super_user.apk_oldat.daftar_laporan', compact('kategoriBarang', 'kondisiBarang','pegawai', 'barang'));
        }
    }

    public function recap(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriBarang     = KategoriBarang::get();
            $timKerja           = TimKerja::get();
            $dataBarang         = Barang::select('id_barang', 'kategori_barang', 'pegawai_id', 'tim_kerja', 'unit_kerja')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_tim_kerja.unit_kerja_id')
                ->get();

            $rekapTotalBarang   = Barang::select('kategori_barang', DB::raw('count(id_barang) as totalbarang'))
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->groupBy('kategori_barang')
                ->get();

            foreach ($timKerja as $dataTimKerja) {
                foreach ($kategoriBarang as $dataKategoriBarang) {
                    $rekapTimKerja[$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();
                }
            }

            return view('v_super_user.apk_oldat.daftar_rekap', compact('rekapTotalBarang','rekapTimKerja', 'kategoriBarang'));
        } else {
        }
    }

    public function submission(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $formUsulan = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')->get();
            return view('v_super_user.apk_oldat.daftar_pengajuan', compact('formUsulan'));
        } elseif ($aksi == 'form-usulan') {
            $kategoriBarang = KategoriBarang::get();
            $pegawai = Pegawai::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->first();

            if ($id == 'pengadaan') {
                return view('v_super_user.apk_oldat.usulan_pengadaan', compact('kategoriBarang', 'pegawai'));
            } else {
                return view('v_super_user.apk_oldat.usulan_perbaikan', compact('pegawai'));
            }
        } elseif ($aksi == 'proses-pengajuan' && $id == 'pengadaan') {
            $otp = rand(1000,9999);
            $cekData = FormUsulan::count();
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan = $cekData + 1;
            $formUsulan->pegawai_id = $request->input('pegawai_id');
            $formUsulan->kode_form  = 'OLDAT_001';
            $formUsulan->jenis_form = 'pengadaan';
            $formUsulan->total_pengajuan   = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan    = $request->input('tanggal_usulan' );
            $formUsulan->rencana_pengguna  = $request->input('rencana_pengguna');
            $formUsulan->status_proses     = 'belum proses';
            $formUsulan->kode_otp          = $otp;
            $formUsulan->save();
            // $this->sendWhatsappNotification($otp, '+6285772652563');
            return $formUsulan;
        }
    }

    private function sendWhatsappNotification(string $otp, string $recipient)
    {
        $twilio_whatsapp_number = getenv("TWILIO_WHATSAPP_NUMBER");
        $account_sid            = getenv("TWILIO_ACCOUNT_SID");
        $auth_token             = getenv("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);
        $message = "Your registration pin code is $otp";
        return $client->messages->create("whatsapp:$recipient", array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }
}
