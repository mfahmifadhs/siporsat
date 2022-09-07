<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\RiwayatBarang;
use App\Models\TimKerja;
use App\Models\FormUsulan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

use DB;
use Illuminate\Support\Facades\Auth;

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
        return view('v_super_user.apk_oldat.index',['chartData'=>$chartData,'unitKerja'=>$unitKerja, 'timKerja'=>$timKerja]);
    }

    public function getChartData()
    {
        // $dataRiwayatBarang = RiwayatBarang::select('id_riwayat_barang','barang_id','kategori_barang','oldat_tbl_riwayat_barang.pegawai_id','unit_kerja')
        //     ->join('oldat_tbl_barang','barang_id','id_barang')
        //     ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
        //     ->join('tbl_pegawai','id_pegawai','oldat_tbl_riwayat_barang.pegawai_id')
        //     ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
        //     ->get();

        $dataBarang = Barang::select('id_barang','kategori_barang','pegawai_id','unit_kerja','tim_kerja')
            ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
            ->join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->join('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
            ->get();
        
        $dataKategoriBarang = KategoriBarang::get();
        // $timKerja = TimKerja::get();
        // $unitKerja = UnitKerja::get();

        // foreach($timKerja as $item){
        //     foreach($dataKategoriBarang as $data){
        //         $dataChart[$item->tim_kerja][$data->kategori_barang] = $dataBarang->where('tim_kerja',$item->tim_kerja)->where('kategori_barang',$data->kategori_barang)->count();
        //     }
        // }
        // dd($dataChart);

        // dd($dataBarang);

        foreach($dataKategoriBarang as $data){
            $labelChart[] = $data->kategori_barang;
            $dataChart[] = $dataBarang->where('kategori_barang',$data->kategori_barang)->count();
        }
        $resultChart['label'] = $labelChart;
        $resultChart['data'] = $dataChart;
        $chart = json_encode($resultChart);

        // dd($chart);
        return $chart;
    }
    public function searchChartData(Request $request){
        return $request->all();
    }

    public function report(Request $request, $aksi, $id)
    {

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

            foreach ($timKerja as $dataTimKerja) {
                foreach ($kategoriBarang as $dataKategoriBarang) {
                    $rekap[$dataTimKerja->tim_kerja][$dataKategoriBarang->kategori_barang] = $dataBarang->where('tim_kerja', $dataTimKerja->tim_kerja)->where('kategori_barang', $dataKategoriBarang->kategori_barang)->count();
                }
            }


            return view('v_super_user.apk_oldat.daftar_rekap', compact('rekap','kategoriBarang'));
        } else {

        }
    }

    public function submission(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $formUsulan = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')->get();
            return view('v_super_user.apk_oldat.daftar_pengajuan', compact('formUsulan'));

        }elseif($aksi == 'form-usulan') {
            $kategoriBarang = KategoriBarang::get();
            $pegawai = Pegawai::join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->first();

            if ($id == 'pengadaan') {
                return view('v_super_user.apk_oldat.usulan_pengadaan', compact('kategoriBarang','pegawai'));
            } else {
                return view('v_super_user.apk_oldat.usulan_perbaikan', compact('pegawai'));
            }
        } elseif($aksi == 'proses-pengajuan' && $id == 'pengadaan') {
            dd($request->all());
        }
    }
}
