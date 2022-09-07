<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\RiwayatBarang;
use App\Models\TimKerja;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class SuperUserController extends Controller
{
    public function index()
    {
        return view('v_super_user.index');
    }

    public function dashboardOldat()
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
}
