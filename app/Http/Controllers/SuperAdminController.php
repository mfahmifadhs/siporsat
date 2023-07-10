<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserExport;
use App\Exports\PegawaiExport;
use App\Exports\TimKerjaExport;
use App\Exports\UnitKerjaExport;
use App\Exports\BarangExport;
use App\Imports\AADB\KendaraanImport;
use App\Imports\BarangImport;
use App\Imports\TimKerjaImport;
use App\Imports\UnitKerjaImport;
use App\Imports\PegawaiImport;
use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\KendaraanSewa;
use App\Models\AADB\KondisiKendaraan;
use App\Models\AADB\RiwayatKendaraan;
use App\Models\AADB\UsulanAadb;
use App\Models\AADB\UsulanKendaraan;
use App\Models\AADB\UsulanPerpanjanganSTNK;
use App\Models\AADB\UsulanServis;
use App\Models\AADB\UsulanVoucherBBM;
use App\Models\ATK\Atk;
use App\Models\ATK\JenisAtk;
use App\Models\ATK\KategoriAtk;
use App\Models\ATK\SubKelompokAtk;
use App\Models\ATK\UsulanAtk;
use App\Models\ATK\UsulanAtkDetail;
use App\Models\ATK\UsulanAtkPengadaan;
use App\Models\ATK\UsulanAtkLampiran;
use App\Models\ATK\UsulanAtkPermintaan;
use App\Models\GDN\UsulanGdn;
use App\Models\GDN\UsulanGdnDetail;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use App\Models\OLDAT\FormUsulan;
use App\Models\OLDAT\FormUsulanPengadaan;
use App\Models\OLDAT\FormUsulanPerbaikan;
use App\Models\Level;
use App\Models\OLDAT\RiwayatBarang;
use App\Models\PegawaiJabatan;
use App\Models\Pegawai;
use App\Models\RDN\KondisiRumah;
use App\Models\RDN\PenghuniRumah;
use App\Models\RDN\RumahDinas;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\TimKerja;
use App\Models\UKT\UsulanUkt;
use App\Models\UKT\UsulanUktDetail;
use App\Models\UserAkses;

use DB;
use Hash;
use Validator;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('v_super_admin.index');
    }

    public function Letter(Request $request, $aksi, $id)
    {
        if ($aksi == 'usulan-gdn') {
            $modul = 'gdn';
            $form  = UsulanGdn::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanGdn::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_super_admin/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-atk') {
            $modul = 'atk';
            $form  = UsulanAtk::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_super_admin/surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_super_admin/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_super_admin/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'bast-atk') {
            $modul = 'atk';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_super_admin/surat_bast', compact('pimpinan', 'bast', 'modul'));
        } elseif ($aksi == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $form      = UsulanAadb::where('id_form_usulan', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_super_admin/surat_bast', compact('pimpinan', 'bast', 'modul', 'jenisAadb'));
        } elseif ($aksi == 'bast-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = FormUsulan::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_super_admin.surat_bast', compact('modul', 'bast', 'pimpinan'));
        }
    }

    public function PrintLetter(Request $request, $modul, $id)
    {
        if ($modul == 'usulan-gdn') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanGdn::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_super_admin/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-atk') {
            $form  = UsulanAtk::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_super_admin/print_surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-aadb') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_super_admin/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-oldat') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_super_admin/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'bast-atk') {
            $modul = 'atk';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_super_admin/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul'));
        } elseif ($modul == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $form      = UsulanAadb::where('id_form_usulan', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_super_admin/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul', 'jenisAadb'));
        } elseif ($modul == 'bast-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = FormUsulan::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_super_admin.print_surat_bast', compact('modul', 'bast', 'pimpinan'));
        }
    }

    public function Select2User(Request $request, $aksi, $id)
    {
        $search = $request->search;
        if ($id == 1)
        {
            if ($search == '') {
                $user  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('id_unit_kerja', 'asc')
                    ->get();
            } else {
                $user  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('id_unit_kerja', 'asc')
                    ->where('unit_kerja', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($id == 2) {
            if ($search == '') {
                $user  = Pegawai::select('id_pegawai as id', 'nama_pegawai as nama')
                    ->orderby('id_pegawai', 'asc')
                    ->get();
            } else {
                $user  = Pegawai::select('id_pegawai as id', 'nama_pegawai as nama')
                    ->orderby('id_pegawai', 'asc')
                    ->where('nama_pegawai', 'like', '%' . $search . '%')
                    ->get();
            }
        }

        $response = array();
        foreach ($user as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id.' - '.$data->nama
            );
        }

        return response()->json($response);
    }

    // ====================================================
    //                  RUMAH DINAS NEGARA
    // ====================================================

    public function Rdn(Request $request)
    {
        $googleChartData = $this->ChartDataRDN();
        $lokasiKota      = RumahDinas::select('lokasi_kota')->groupBy('lokasi_kota')->get();
        $penghuni        = PenghuniRumah::get();
        $rumahDinas      = PenghuniRumah::join('rdn_tbl_rumah_dinas','id_rumah_dinas', 'rumah_dinas_id')
            ->leftjoin('tbl_pegawai','id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_unit_kerja','id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->where('status_penghuni', 1)
            ->where(DB::raw("(DATE_FORMAT(masa_berakhir_sip, '%Y-%m'))"),'>',Carbon::now()->format('Y-m'))
            ->orderBy('masa_berakhir_sip','ASC')
            ->paginate(5);

        return view('v_super_admin.apk_rdn.index', compact('lokasiKota', 'googleChartData','penghuni','rumahDinas'));
    }

    public function OfficialResidence(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $rumah = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')->get();

            return view('v_super_admin.apk_rdn.daftar_rumah', compact('rumah'));
        } elseif ($aksi == 'detail') {
            $pegawai  = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->get();
            $penghuni = PenghuniRumah::leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->where('rumah_dinas_id', $id)
                ->orderBy('id_penghuni','DESC')
                ->first();
            $rumah    = RumahDinas::where('id_rumah_dinas', $id)
                ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')
                ->first();
            $kondisi  = KondisiRumah::get();

            return view('v_super_admin.apk_rdn.detail_rumah', compact('pegawai','rumah','penghuni','kondisi'));
        }
    }

    public function ChartDataRdn()
    {
        $dataPenghuni = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')->get();
        $dataRumah = PenghuniRumah::join('rdn_tbl_rumah_dinas','id_rumah_dinas','rumah_dinas_id')
            ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')
            ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
            ->get();
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
        $dataRumah = PenghuniRumah::join('rdn_tbl_rumah_dinas','id_rumah_dinas','rumah_dinas_id')
        ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')
        ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id');

        $lokasi    = RumahDinas::select('lokasi_kota')->groupBy('lokasi_kota')->get();

        if ($request->hasAny(['golongan_rumah', 'lokasi_kota', 'kondisi_rumah'])) {
            if ($request->golongan_rumah) {
                $dataSearch = $dataRumah->where('golongan_rumah', $request->golongan_rumah);
            }
            if ($request->lokasi_kota) {
                $dataSearch = $dataRumah->where('lokasi_kota', $request->lokasi_kota);
            }
            if ($request->kondisi_rumah) {
                $dataSearch = $dataRumah->where('kondisi_rumah_id', $request->kondisi_rumah);
            }

            $dataSearch = $dataSearch->get();
        } else {
            $dataSearch = $dataRumah->get();
        }

        foreach ($lokasi as $data) {
            $dataArray[]          = $data->lokasi_kota;
            $dataArray[]          = $dataSearch->where('lokasi_kota', $data->lokasi_kota)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table'] = $dataSearch;
        $chart = json_encode($dataChart);

        if (count($dataSearch) > 0) {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'success',
                'data'      => $chart
            ], 200);
        } else {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'not found'
            ], 200);
        }
    }

    // ====================================================
    //                       GDN
    // ====================================================

    public function Ukt(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $usulan = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('id_form_usulan','DESC')
                // ->where('pegawai_id', Auth::user()->pegawai_id)
                ->get();

            return view('v_super_admin.apk_ukt.index', compact('usulan'));
        } elseif ($aksi == 'hapus') {
            // Hapus Detail Usulan
            UsulanUkt::where('id_form_usulan', $id)->delete();
            UsulanUktDetail::where('form_usulan_id', $id)->delete();

            return redirect('super-admin/ukt/dashboard/daftar/seluruh-usulan')->with('success', 'Berhasil Menghapus Usulan');
        }
    }

    // ====================================================
    //                       GDN
    // ====================================================

    public function Gdn(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('id_form_usulan','DESC')
                // ->where('pegawai_id', Auth::user()->pegawai_id)
                ->get();

            return view('v_super_admin.apk_gdn.index', compact('usulan'));
        } elseif ($aksi == 'hapus') {
            // Hapus Detail Usulan
            UsulanGdn::where('id_form_usulan', $id)->delete();
            UsulanGdnDetail::where('form_usulan_id', $id)->delete();

            return redirect('super-admin/gdn/dashboard/daftar/seluruh-usulan')->with('success', 'Berhasil Menghapus Usulan');
        }
    }

    // ====================================================
    //                       ATK
    // ====================================================

    public function Atk(Request $request)
    {
        $dataChartAtk = $this->ChartDataAtk();
        $usulanUker  = UsulanAtk::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->where('unit_utama_id', '02401')
            ->get();

        $usulanTotal = UsulanAtk::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        // $usulanChart = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"))
        //     ->groupBy('month')
        //     ->get();

        $usulanChart = UsulanAtk::select(
                DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"),
                DB::raw('count(id_form_usulan) as total_usulan')
            )
                ->whereIn('jenis_form', ['distribusi','permintaan'])
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get();

        foreach ($usulanChart as $key => $value) {
            $result[] = ['Bulan', 'Total Usulan'];
            $result[++$key] = [Carbon::parse($value->month)->isoFormat('MMMM Y'), $value->total_usulan];
        }

        $chartAtk = json_encode($result);

        // $chartData = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'jenis_form')
        //     ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
        //     ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
        //     ->get();

        // foreach ($usulanChart as $key => $value) {
        //     $result[] = ['Bulan', 'Total Usulan Distribusi'];
        //     $result[++$key] = [
        //         Carbon::parse($value->month)->isoFormat('MMMM Y'),
        //         $chartData->where('month', $value->month)->whereIn('jenis_form', ['distribusi','permintaan'])->count(),
        //     ];
        // }

        // $usulanChartAtk = json_encode($result);

        return view('v_super_admin.apk_atk.index', compact('usulanUker', 'usulanTotal', 'chartAtk', 'dataChartAtk'));
    }

    public function SubmissionAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $pengajuan  = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->get();

            return view('v_super_admin.apk_atk.daftar_pengajuan', compact('pengajuan'));
        } elseif ($aksi == 'hapus') {
            // Hapus Detail Usulan
            $usulan = UsulanAtk::where('id_form_usulan', $id)->first();
            if ($usulan->jenis_form == 'pengadaan') {
                $pengadaan = UsulanAtkPengadaan::where('form_usulan_id',$id)->get();
                foreach ($pengadaan as $dataPengadaan) {
                    UsulanAtkPermintaan::where('pengadaan_id', $dataPengadaan->id_form_usulan_pengadaan)->delete();
                }
                UsulanAtkPengadaan::where('form_usulan_id',$id)->delete();
                UsulanAtk::where('id_form_usulan',$id)->delete();
            } elseif ($usulan->jenis_form == 'distribusi') {
                $permintaan = UsulanAtkPermintaan::where('form_usulan_id',$id)->get();
                foreach ($permintaan as $dataPermintaan) {
                    $pengadaan = UsulanAtkPengadaan::where('id_form_usulan_pengadaan',$dataPermintaan->pengadaan_id)->first();
                    UsulanAtkPengadaan::where('id_form_usulan_pengadaan', $dataPermintaan->pengadaan_id)
                        ->update([
                            'jumlah_pemakaian' => $pengadaan->jumlah_pemakaian - $dataPermintaan->jumlah_disetujui
                        ]);
                }
                UsulanAtkPermintaan::where('form_usulan_id',$id)->delete();
                UsulanAtk::where('id_form_usulan',$id)->delete();
            }

            // $detail = UsulanAtkDetail::select('atk_id', DB::raw('sum(jumlah_pengajuan) as total'))
            //     ->groupBy('atk_id')
            //     ->where('form_usulan_id', $id)
            //     ->get();

            // if ($usulan->jenis_form == 'pengadaan' && $usulan->status_pengajuan_id != null && $usulan->status_proses_id == 5)
            // {
            //     $lampiran = UsulanAtkLampiran::where('form_usulan_id', $id)->get();
            //     foreach ($lampiran as $atkLampiran)
            //     {
            //         $file_old = public_path() . '\gambar\kwitansi\atk_pengadaan\\' . $atkLampiran->file_kwitansi;
            //         unlink($file_old);
            //         UsulanAtkLampiran::where('form_usulan_id', $id)->delete();
            //     }

            //     foreach ($detail as $atk)
            //     {
            //         $volumeAtk = Atk::where('id_atk', $atk->atk_id)->first();
            //         $update    = Atk::where('id_atk', $atk->atk_id)->update(['total_atk' => ($volumeAtk->total_atk - $atk->total)]);
            //         UsulanAtkDetail::where('form_usulan_id', $id)->delete();
            //     }

            //     UsulanAtk::where('id_form_usulan', $id)->delete();
            // } elseif ($usulan->jenis_form == 'distribusi' && $usulan->status_pengajuan_id != null && $usulan->status_proses_id == 5) {
            //     foreach ($detail as $atk)
            //     {
            //         $volumeAtk = Atk::where('id_atk', $atk->atk_id)->first();
            //         $update    = Atk::where('id_atk', $atk->atk_id)->update(['total_atk' => ($volumeAtk->total_atk + $atk->total)]);
            //         UsulanAtk::where('id_form_usulan', $id)->delete();
            //         UsulanAtkDetail::where('form_usulan_id', $id)->delete();
            //     }
            // } else {
            //     UsulanAtk::where('id_form_usulan', $id)->delete();
            // }

            return redirect('super-admin/atk/usulan/daftar/seluruh-usulan')->with('success', 'Berhasil Menghapus Usulan');
        }
    }

    public function OfficeStationery(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $atk = ATK::with('KategoriATK')->get();
            return view('v_super_admin.apk_atk.daftar_atk', compact('atk'));
        }
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
                $atk  = SubKelompokAtk::select('id_subkelompok_atk as id', 'subkelompok_atk as nama')
                    ->orderby('id_subkelompok_atk', 'asc')
                    ->orWhere('subkelompok_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 2) {
            if ($search == '') {
                $atk  = JenisAtk::select('id_jenis_atk as id', 'subkelompok_atk_id', 'jenis_atk as nama')
                    ->orderby('id_jenis_atk', 'asc')
                    ->get();
            } else {
                $atk  = JenisAtk::select('id_jenis_atk as id', 'subkelompok_atk_id', 'jenis_atk as nama')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('id_jenis_atk', 'like', '%' . $search . '%')
                    ->orWhere('jenis_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 3) {
            if ($search == '') {
                $atk  = KategoriAtk::select('id_kategori_atk as id', 'jenis_atk_id', 'kategori_atk as nama')
                    ->orderby('id_kategori_atk', 'asc')
                    ->get();
            } else {
                $atk  = KategoriAtk::select('id_kategori_atk as id', 'jenis_atk_id', 'kategori_atk as nama')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('id_kategori_atk', 'like', '%' . $search . '%')
                    ->orWhere('kategori_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 4) {
            if ($search == '') {
                $atk  = Atk::select('id_atk as id as id', 'kategori_atk_id', 'merk_atk as nama')
                    ->orderby('id_atk', 'asc')
                    ->get();
            } else {
                $atk  = Atk::select('id_atk as id', 'kategori_atk_id', 'merk_atk as nama')
                    ->orderby('id_atk', 'asc')
                    ->orWhere('merk_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 5) {
            $atk  = StokAtk::select('id_stok as id', 'atk_id', 'stok_atk as stok', 'satuan')
                ->orderby('id_stok', 'asc')
                ->get();
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id . ' - ' . $data->nama,
                "stok"   =>  $data->stok,
                "satuan" =>  $data->satuan
            );
        }

        return response()->json($response);
    }

    public function ChartDataAtk()
    {
        $dataAtk = UsulanAtkPengadaan::select(
            'jenis_barang',
            'nama_barang',
            'spesifikasi',
            'satuan',
            DB::raw('sum(jumlah_disetujui) as jumlah_disetujui'),
            DB::raw('sum(jumlah_pemakaian) as jumlah_pemakaian')
        )
            ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
            ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('jenis_barang', 'nama_barang', 'spesifikasi', 'satuan')
            ->where('status_proses_id', 5)
            ->get();

        $totalAtk = UsulanAtkPengadaan::select('nama_barang', DB::raw('sum(jumlah_disetujui) as stok'))
            ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
            ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('nama_barang')
            ->where('status_proses_id', 5)
            ->get();

        foreach ($totalAtk as $data) {
            $dataArray[] = $data->nama_barang;
            $dataArray[] = (int) $data->stok;
            // $totalStok =  $stok->where('id_kategori_atk', $data->id_kategori_atk)->get();
            // $dataArray[] = $totalStok[$i]->stok;
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['atk'] = $dataAtk;
        $chart = json_encode($dataChart);
        // dd($chart);
        return $chart;
    }

    public function SearchChartDataAtk(Request $request)
    {
        $dataAtk = Atk::join('atk_tbl_kelompok_sub_kategori', 'id_kategori_atk', 'kategori_atk_id')
            ->join('atk_tbl_kelompok_sub_jenis', 'id_jenis_atk', 'jenis_atk_id')
            ->join('atk_tbl_kelompok_sub', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->orderBy('total_atk', 'DESC');

        $totalAtk = Atk::select('id_kategori_atk', 'kategori_atk', DB::raw('sum(total_atk) as stok'))
            ->join('atk_tbl_kelompok_sub_kategori', 'id_kategori_atk', 'kategori_atk_id')
            ->join('atk_tbl_kelompok_sub_jenis', 'id_jenis_atk', 'jenis_atk_id')
            ->join('atk_tbl_kelompok_sub', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->groupBy('id_kategori_atk', 'kategori_atk');

        if ($request->hasAny(['kategori', 'jenis', 'nama', 'merk'])) {
            if ($request->kategori) {
                $dataSearchAtk = $dataAtk->where('id_subkelompok_atk', $request->kategori);
                $dataTotalAtk  = $totalAtk->where('id_subkelompok_atk', $request->kategori);
            }
            if ($request->jenis) {
                $dataSearchAtk = $dataAtk->where('id_jenis_atk', $request->jenis);
                $dataTotalAtk  = $totalAtk->where('id_jenis_atk', $request->jenis);
            }
            if ($request->nama) {
                $dataSearchAtk = $dataAtk->where('id_kategori_atk', $request->nama);
                $dataTotalAtk  = $totalAtk->where('id_kategori_atk', $request->nama);
            }
            if ($request->merk) {
                $dataSearchAtk = $dataAtk->where('id_atk', $request->merk);
                $dataTotalAtk  = $totalAtk->where('id_atk', $request->merk);
            }

            $resultSearchAtk = $dataSearchAtk->get();
            $resultTotalAtk  = $dataTotalAtk->get();
        } else {
            $resultSearchAtk = $dataAtk->get();
            $resultTotalAtk  = $totalAtk->get();
        }

        foreach ($resultTotalAtk as $data) {
            $dataArray[] = $data->kategori_atk;
            $dataArray[] = (int) $data->stok;
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }
        $dataChart['table'] = $resultSearchAtk;
        $chart = json_encode($dataChart);
        // dd($chart);
        if (count($resultSearchAtk) > 0) {
            return response([
                'status' => true,
                'total' => count($resultSearchAtk),
                'message' => 'success',
                'data' => $chart
            ], 200);
        } else {
            return response([
                'status' => true,
                'total' => count($resultSearchAtk),
                'message' => 'not found'
            ], 200);
        }
    }

    // ====================================================
    //                       AADB
    // ====================================================

    public function Aadb(Request $request)
    {
        $usulanUker = UsulanAadb::select(
            'id_unit_kerja',
            'unit_kerja',
            DB::raw('COUNT(CASE WHEN jenis_form = "1" THEN id_form_usulan END) AS total_pengadaan'),
            DB::raw('COUNT(CASE WHEN jenis_form = "2" THEN id_form_usulan END) AS total_servis'),
            DB::raw('COUNT(CASE WHEN jenis_form = "3" THEN id_form_usulan END) AS total_stnk'),
            DB::raw('COUNT(CASE WHEN jenis_form = "4" THEN id_form_usulan END) AS total_voucher')
        )
        ->leftJoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
        ->rightJoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
        ->groupBy('id_unit_kerja', 'unit_kerja')
        ->get();

        $usulanTotal = UsulanAadb::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        $usulanChart = UsulanAadb::select(
            DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"),
            DB::raw('count(id_form_usulan) as total_usulan')
        )
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        $chartData = UsulanAadb::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'jenis_form')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->get();

        foreach ($usulanChart as $key => $value) {
            $result[] = ['Bulan', 'Total Pengadaan', 'Total Servis', 'Total Perpanjangan STNK', 'Total Voucher BBM'];
            $result[++$key] = [
                Carbon::parse($value->month)->isoFormat('MMMM Y'),
                $chartData->where('month', $value->month)->where('jenis_form', '1')->count(),
                $chartData->where('month', $value->month)->where('jenis_form', '2')->count(),
                $chartData->where('month', $value->month)->where('jenis_form', '3')->count(),
                $chartData->where('month', $value->month)->where('jenis_form', '4')->count(),
            ];
        }

        $chartAadb = json_encode($result);
        return view('v_super_admin.apk_aadb.index', compact('usulanUker', 'usulanTotal', 'chartAadb'));
    }

    public function SubmissionAadb(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $pengajuan  = UsulanAadb::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->get();

            return view('v_super_admin.apk_aadb.daftar_pengajuan', compact('pengajuan'));
        } elseif ($aksi == 'hapus') {
            // Hapus Detail Usulan
            $usulan = UsulanAadb::where('id_form_usulan', $id)->first();
            if ($usulan->jenis_form == 1) {

                $pengadaan = Kendaraan::where('form_usulan_id', $id)
                    ->join('aadb_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
                    ->get();

                foreach ($pengadaan as $dataPengadaan) {
                    if ($dataPengadaan->jenis_aadb == 'sewa') {
                        KendaraanSewa::where('kendaraan_id', $dataPengadaan->id_kendaraan)->delete();
                    }
                }

                if ($usulan->lampiran != null) {
                    $file = public_path() . '\gambar\kwitansi\pengadaan\\' . $usulan->lampiran;
                    unlink($file);
                }
                Kendaraan::where('form_usulan_id', $id)->delete();
                UsulanKendaraan::where('form_usulan_id', $id)->delete();
            } elseif ($usulan->jenis_form == 2) {
                if ($usulan->lampiran != null) {
                    $file = public_path() . '\gambar\kwitansi\servis\\' . $usulan->lampiran;
                    unlink($file);
                }
                UsulanServis::where('form_usulan_id', $id)->delete();
            } elseif ($usulan->jenis_form == 3) {
                if ($usulan->lampiran != null) {
                    $file = public_path() . '\gambar\kendaraan\stnk\\' . $usulan->lampiran;
                    unlink($file);
                }
                UsulanPerpanjanganSTNK::where('form_usulan_id', $id)->delete();
            } elseif ($usulan->jenis_form == 4) {
                UsulanVoucherBBM::where('form_usulan_id', $id)->delete();
            }

            // Hapus Form Usulan
            UsulanAadb::where('id_form_usulan', $id)->delete();
            return redirect('super-admin/aadb/usulan/daftar/seluruh-usulan')->with('success', 'Berhasil Menghapus Usulan');
        }
    }

    public function Vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kendaraan = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->join('aadb_tbl_kondisi_kendaraan', 'id_kondisi_kendaraan', 'kondisi_kendaraan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('jenis_aadb', 'ASC')
                ->get();

            return view('v_super_admin.apk_aadb.daftar_kendaraan', compact('kendaraan'));
        } elseif ($aksi == 'detail') {
            $unitKerja = UnitKerja::get();
            $jenis     = JenisKendaraan::get();
            $kondisi   = KondisiKendaraan::get();
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->first();

            return view('v_super_admin.apk_aadb.detail_kendaraan', compact('unitKerja', 'jenis', 'kondisi', 'kendaraan'));
        } elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_kendaraan'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('super-admin/aadb/kendaraan/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            } else {
                if ($request->foto_kendaraan == null) {
                    $fotoKendaraan = $request->foto_lama;
                } else {
                    $dataKendaraan = Kendaraan::where('id_kendaraan', 'like', '%' . $id . '%')->first();

                    if ($request->hasfile('foto_kendaraan')) {
                        if ($dataKendaraan->foto_barang != ''  && $dataKendaraan->foto_kendaraan != null) {
                            $file_old = public_path() . '\gambar\kendaraan\\' . $dataKendaraan->foto_kendaraan;
                            unlink($file_old);
                        }
                        $file       = $request->file('foto_kendaraan');
                        $filename   = $file->getClientOriginalName();
                        $file->move('gambar/kendaraan/', $filename);
                        $dataKendaraan->foto_kendaraan = $filename;
                    } else {
                        $dataKendaraan->foto_kendaraan = '';
                    }
                    $fotoKendaraan = $dataKendaraan->foto_kendaraan;
                }

                $barang = Kendaraan::where('id_kendaraan', 'like', '%' . $id . '%')->update([
                    'unit_kerja_id'           => $request->unit_kerja_id,
                    'jenis_aadb'              => $request->jenis_aadb,
                    'kode_barang'             => $request->kode_barang,
                    'nup_barang'              => $request->nup_barang,
                    'jenis_kendaraan_id'      => $request->id_jenis_kendaraan,
                    'merk_tipe_kendaraan'     => $request->merk_tipe_kendaraan,
                    'no_plat_kendaraan'       => $request->no_plat_kendaraan,
                    'mb_stnk_plat_kendaraan'  => $request->mb_stnk_plat_kendaraan,
                    'no_plat_rhs'             => $request->no_plat_rhs,
                    'mb_stnk_plat_rhs'        => $request->mb_stnk_plat_rhs,
                    'no_bpkb'                 => $request->no_bpkb,
                    'no_rangka'               => $request->no_rangka,
                    'no_mesin'                => $request->no_mesin,
                    'tahun_kendaraan'         => $request->tahun_kendaraan,
                    'tanggal_perolehan'       => $request->tanggal_perolehan,
                    'kondisi_kendaraan_id'    => $request->id_kondisi_kendaraan,
                    'nilai_perolehan'         => $request->nilai_perolehan,
                    'keterangan_aadb'         => $request->keterangan_aadb,
                    'pengguna'                => $request->pengguna,
                    'jabatan'                 => $request->jabatan,
                    'pengemudi'               => $request->pengemudi,
                    'foto_kendaraan'          => $fotoKendaraan

                ]);
            }
            if ($request->proses == 'pengguna-baru') {
                $status = RiwayatKendaraan::where('kendaraan_id', $id)
                    ->update(['status_pengguna' => 2]);

                $riwayat   = new RiwayatKendaraan();
                $riwayat->kendaraan_id      = $id;
                $riwayat->tanggal_pengguna  = Carbon::now();
                $riwayat->pengguna          = $request->pengguna;
                $riwayat->jabatan           = $request->jabatan;
                $riwayat->pengemudi         = $request->pengemudi;
                $riwayat->status_pengguna   = 1;
                $riwayat->save();
            }

            return redirect('super-admin/aadb/kendaraan/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Kendaraan');
        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatKendaraan::where('id_riwayat_kendaraan', $request->id_riwayat_kendaraan)->update([
                'tanggal_pengguna'     => Carbon::now()
            ]);

            return redirect('super-admin/aadb/kendaraan/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Kendaraan');
        } elseif ($aksi == 'hapus-riwayat') {
            $riwayat   = RiwayatKendaraan::where('id_riwayat_kendaraan', $id)->first();
            $kendaraan = Kendaraan::where('id_kendaraan', $riwayat->kendaraan_id)->update(['pengguna' => null]);
            RiwayatKendaraan::where('id_riwayat_kendaraan', $id)->delete();
            return redirect('super-admin/aadb/kendaraan/detail/' . $riwayat->kendaraan_id)->with('success', 'Berhasil Menghapus Riwayat Pengguna Kendaraan');
        } elseif ($aksi == 'file-kendaraan') {
            Excel::import(new KendaraanImport(), $request->file);
            return redirect('super-admin/aadb/kendaraan/daftar/seluruh-kendaraan')->with('success', 'Berhasil Mengupload Data Kendaraan');
        }
    }

    public function ChartDataAadb()
    {
        $dataKendaraan = Kendaraan::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('aadb_tbl_jenis_kendaraan', 'jenis_kendaraan_id', 'id_jenis_kendaraan')
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
            ->join('aadb_tbl_jenis_kendaraan', 'jenis_kendaraan_id', 'id_jenis_kendaraan');

        $dataJenisKendaraan = JenisKendaraan::get();

        if ($request->hasAny(['jenis_aadb', 'unit_kerja', 'jenis_kendaraan'])) {
            if ($request->jenis_aadb) {
                $dataSearch = $dataKendaraan->where('jenis_aadb', $request->jenis_aadb);
            }
            if ($request->unit_kerja) {
                $dataSearch = $dataKendaraan->where('unit_kerja_id', $request->unit_kerja);
            }
            if ($request->jenis_kendaraan) {
                $dataSearch = $dataKendaraan->where('jenis_kendaraan_id', $request->jenis_kendaraan);
            }

            $dataSearch = $dataSearch->get();
        } else {
            $dataSearch = $dataKendaraan->get();
        }

        foreach ($dataJenisKendaraan as $data) {
            $dataArray[]          = $data->jenis_kendaraan;
            $dataArray[]          = $dataSearch->where('jenis_kendaraan', $data->jenis_kendaraan)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table'] = $dataSearch;
        $chart = json_encode($dataChart);

        if (count($dataSearch) > 0) {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'success',
                'data'      => $chart
            ], 200);
        } else {
            return response([
                'status'    => true,
                'total'     => count($dataSearch),
                'message'   => 'not found'
            ], 200);
        }
    }

    public function Select2AadbDashboard(Request $request, $aksi, $id)
    {
        $search = $request->search;
        if ($aksi == 1) {
            if ($search == '') {
                $aadb  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('unit_kerja', 'asc')
                    ->get();
            } else {
                $aadb  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('unit_kerja', 'asc')
                    ->where('id_unit_kerja', 'like', '%' . $search . '%')
                    ->orWhere('unit_kerja', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 2) {
            if ($search == '') {
                $aadb  = JenisKendaraan::select('id_jenis_kendaraan as id', 'jenis_kendaraan as nama')
                    ->orderby('id_jenis_kendaraan', 'asc')
                    ->get();
            } else {
                $aadb  = JenisKendaraan::select('id_jenis_kendaraan as id', 'jenis_kendaraan as nama')
                    ->orderby('id_jenis_kendaraan', 'asc')
                    ->where('id_jenis_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('jenis_kendaraan', 'like', '%' . $search . '%')
                    ->get();
            }
        }

        $response = array();
        foreach ($aadb as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id . ' - ' . $data->nama
            );
        }

        return response()->json($response);
    }

    // ====================================================
    //                       OLDAT
    // ====================================================

    public function Oldat()
    {
        $googleChartData = $this->ChartDataOldat();
        $usulan  = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

            $usulanUker = FormUsulan::select(
                'id_unit_kerja',
                'unit_kerja',
                DB::raw('COUNT(CASE WHEN jenis_form = "pengadaan" THEN id_form_usulan END) AS total_pengadaan'),
                DB::raw('COUNT(CASE WHEN jenis_form = "perbaikan" THEN id_form_usulan END) AS total_perbaikan')
            )
            ->leftJoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightJoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_kerja')
            ->get();

            $usulanTotal = FormUsulan::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC')
                ->get();

            $usulanChart = FormUsulan::select(
                DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"),
                DB::raw('count(id_form_usulan) as total_usulan')
            )
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->groupBy('month')
                ->orderBy('month', 'ASC')
                ->get();

            $chartData = FormUsulan::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'jenis_form')
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->get();

            foreach ($usulanChart as $key => $value) {
                $result[] = ['Bulan', 'Total Pengadaan', 'Total Perbaikan'];
                $result[++$key] = [
                    Carbon::parse($value->month)->isoFormat('MMMM Y'),
                    $chartData->where('month', $value->month)->where('jenis_form', 'pengadaan')->count(),
                    $chartData->where('month', $value->month)->where('jenis_form', 'perbaikan')->count(),
                ];
            }

        $chartOldat = json_encode($result);

        return view('v_super_admin.apk_oldat.index', compact('usulanUker', 'usulanTotal', 'chartOldat','googleChartData', 'usulan'));
    }

    public function Items(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $char = '"';
            $barang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang','satuan_barang',
                'nilai_perolehan','kondisi_barang','pengguna_barang','unit_kerja','tahun_perolehan',
                DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"))
                ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                ->orderBy('tahun_perolehan', 'DESC')
                ->get();

            $result = json_decode($barang);
            return view('v_super_admin.apk_oldat.daftar_barang', compact('barang'));
        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai', 'ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->where('id_barang', 'like', '%' . $id . '%')->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang', 'id_barang', 'barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('barang_id', 'like', '%' . $id . '%')->get();

            return view('v_super_admin.apk_oldat.detail_barang', compact('kategoriBarang', 'kondisiBarang', 'pegawai', 'barang', 'riwayat'));
        } elseif ($aksi == 'upload') {
            Excel::import(new BarangImport(), $request->upload);
            return redirect('super-admin/oldat/barang/data/semua')->with('success', 'Berhasil Mengupload Data Barang');
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_barang'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('super-admin/oldat/barang/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            } else {
                if ($request->foto_barang == null) {
                    $fotoBarang = $request->foto_lama;
                } else {
                    $dataBarang = Barang::where('id_barang', 'like', '%' . $id . '%')->first();

                    if ($request->hasfile('foto_barang')) {
                        if ($dataBarang->foto_barang != ''  && $dataBarang->foto_barang != null) {
                            $file_old = public_path() . '\gambar\barang_bmn\\' . $dataBarang->foto_barang;
                            unlink($file_old);
                        }
                        $file       = $request->file('foto_barang');
                        $filename   = $file->getClientOriginalName();
                        $file->move('gambar/barang_bmn/', $filename);
                        $dataBarang->foto_barang = $filename;
                    } else {
                        $dataBarang->foto_barang = '';
                    }
                    $fotoBarang = $dataBarang->foto_barang;
                }
                $barang = Barang::where('id_barang', 'like', '%' . $id . '%')->update([
                    'pengguna_barang'       => $request->pengguna_barang,
                    'kategori_barang_id'    => $request->id_kategori_barang,
                    'kode_barang'           => $request->kode_barang,
                    'nup_barang'            => $request->nup_barang,
                    'merk_tipe_barang'      => $request->merk_tipe_barang,
                    'spesifikasi_barang'    => $request->spesifikasi_barang,
                    'jumlah_barang'         => $request->jumlah_barang,
                    'satuan_barang'         => $request->satuan_barang,
                    'kondisi_barang_id'     => $request->id_kondisi_barang,
                    'nilai_perolehan'       => $request->nilai_perolehan,
                    'tahun_perolehan'       => $request->tahun_perolehan,
                    'nilai_perolehan'       => $request->nilai_perolehan,
                    'status_barang'         => $request->status_barang,
                    'foto_barang'           => $fotoBarang

                ]);
            }
            if ($request->proses == 'pengguna-baru') {
                $cekBarang = RiwayatBarang::orderBy('id_riwayat_barang','DESC')->first();
                $riwayat   = new RiwayatBarang();
                $riwayat->id_riwayat_barang = $cekBarang->id_riwayat_barang + 1;
                $riwayat->barang_id         = $id;
                $riwayat->pengguna_barang   = $request->input('pengguna_barang');
                $riwayat->tanggal_pengguna  = Carbon::now();
                $riwayat->kondisi_barang_id = $request->input('id_kondisi_barang');
                $riwayat->save();
            }

            return redirect('super-admin/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $request->id_riwayat_barang)->update([
                'tanggal_pengguna'     => $request->tanggal_pengguna,
                'keperluan_penggunaan' => $request->keperluan_penggunaan
            ]);

            return redirect('super-admin/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'hapus-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $id)->delete();
            return redirect('super-admin/oldat/barang/detail/' . $request->barang_id)->with('success', 'Berhasil Menghapus Riwayat Barang');
        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } elseif ($aksi == 'file-barang') {
            Excel::import(new BarangImport(), $request->file);
            return redirect('super-admin/oldat/barang/daftar/seluruh-barang')->with('success', 'Berhasil Mengupload Data Kendaraan');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    public function SubmissionOldat(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $usulan  = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->get();

            return view('v_super_admin.apk_oldat.daftar_pengajuan', compact('usulan'));
        } elseif ($aksi == 'hapus') {
            // Hapus Detail Usulan
            $usulan = FormUsulan::where('id_form_usulan', $id)->first();
            if ($usulan->jenis_form == 'pengadaan') {
                FormUsulanPengadaan::where('form_usulan_id', $id)->delete();
                Barang::where('form_usulan_id', $id)->delete();
            } else {
                FormUsulanPerbaikan::where('form_usulan_id', $id)->delete();
            }

            // Hapus Form Usulan
            FormUsulan::where('id_form_usulan', $id)->delete();
            return redirect('super-admin/oldat/usulan/daftar/seluruh-usulan')->with('success', 'Berhasil Menghapus Usulan');
        }
    }

    public function Select2OldatDashboard(Request $request, $aksi, $id)
    {
        $search = $request->search;
        if ($aksi == 1) {
            if ($search == '') {
                $oldat  = KategoriBarang::select('id_kategori_barang as id', 'kategori_barang as nama')
                    ->orderby('kategori_barang', 'asc')
                    ->get();
            } else {
                $oldat  = KategoriBarang::select('id_kategori_barang as id', 'kategori_barang as nama')
                    ->orderby('kategori_barang', 'asc')
                    ->where('id_kategori_barang', 'like', '%' . $search . '%')
                    ->orWhere('kategori_barang', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 2) {
            if ($search == '') {
                $oldat  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('unit_kerja', 'asc')
                    ->get();
            } else {
                $oldat  = UnitKerja::select('id_unit_kerja as id', 'unit_kerja as nama')
                    ->orderby('unit_kerja', 'asc')
                    ->where('id_unit_kerja', 'like', '%' . $search . '%')
                    ->orWhere('unit_kerja', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 3) {
            if ($search == '') {
                $oldat  = KondisiBarang::select('id_kondisi_barang as id', 'kondisi_barang as nama')
                    ->orderby('id_kondisi_barang', 'asc')
                    ->get();
            } else {
                $oldat  = KondisiBarang::select('id_kondisi_barang as id', 'kondisi_barang as nama')
                    ->orderby('id_kondisi_barang', 'asc')
                    ->where('id_kondisi_barang', 'like', '%' . $search . '%')
                    ->orWhere('kondisi_barang', 'like', '%' . $search . '%')
                    ->get();
            }
        }

        $response = array();
        foreach ($oldat as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id . ' - ' . $data->nama
            );
        }

        return response()->json($response);
    }

    public function ChartDataOldat()
    {
        $char = '"';
        $dataBarang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang','satuan_barang',
            'nilai_perolehan','unit_kerja','tahun_perolehan','kondisi_barang','nama_pegawai',
            DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"))
            ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
            ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->orderBy('tahun_perolehan', 'DESC')
            ->get();

        $dataKategoriBarang = KategoriBarang::get();
        foreach ($dataKategoriBarang as $data) {
            $dataArray[] = $data->kategori_barang;
            $dataArray[] = $dataBarang->where('kategori_barang', $data->kategori_barang)->count();
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['barang'] = $dataBarang;
        $chart = json_encode($dataChart);
        return $chart;
    }

    public function SearchChartDataOldat(Request $request)
    {
        $char = '"';
        $dataBarang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang','satuan_barang',
            'nilai_perolehan','tahun_perolehan','kondisi_barang','nama_pegawai', 'unit_kerja',
            DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"))
            ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
            ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
            ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->orderBy('tahun_perolehan', 'DESC');

        $dataKategoriBarang = KategoriBarang::get();

        if ($request->hasAny(['barang', 'unit_kerja', 'kondisi'])) {
            if ($request->barang) {
                $dataSearchBarang = $dataBarang->where('kode_barang', $request->barang);
            }
            if ($request->unit_kerja) {
                $dataSearchBarang = $dataBarang->where('oldat_tbl_barang.unit_kerja_id', $request->unit_kerja);
            }
            if ($request->kondisi) {
                $dataSearchBarang = $dataBarang->where('kondisi_barang_id', $request->kondisi);
            }

            $dataSearchBarang = $dataSearchBarang->get();
        } else {
            $dataSearchBarang = $dataBarang->get();
        }

        foreach ($dataKategoriBarang as $data) {
            $dataArray[] = $data->kategori_barang;
            $dataArray[] = $dataSearchBarang->where('kategori_barang', $data->kategori_barang)->count();
            $dataChart['chart'][] = $dataArray;
            unset($dataArray);
        }

        $dataChart['table'] = $dataSearchBarang;
        $chart = json_encode($dataChart);

        if (count($dataSearchBarang) > 0) {
            return response([
                'status' => true,
                'total' => count($dataSearchBarang),
                'message' => 'success',
                'data' => $chart
            ], 200);
        } else {
            return response([
                'status' => true,
                'total' => count($dataSearchBarang),
                'message' => 'not found'
            ], 200);
        }
    }



    // ====================================================
    //                    KEWENANGAN
    // ====================================================

    public function showAuthority(Request $request, $aksi, $id)
    {
        return redirect('super-admin/oldat/dashboard');
    }

    // ====================================================
    //                      LEVEL
    // ====================================================

    public function showLevel(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $level   = Level::get();
            return view('v_super_admin.daftar_level', compact('level'));
        }
    }

    // ====================================================
    //                      PENGGUNA
    // ====================================================

    public function showUsers(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $level     = Level::get();
            $unitKerja = UnitKerja::get();
            $pegawai   = Pegawai::orderBy('nama_pegawai')->get();
            $pengguna  = UserAkses::rightjoin('users', 'users.id', 'tbl_users_akses.user_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'users.pegawai_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->leftjoin('tbl_unit_utama', 'tbl_unit_utama.id_unit_utama', 'tbl_unit_kerja.unit_utama_id')
                ->join('tbl_level', 'tbl_level.id_level', 'users.level_id')
                ->orderBy('level_id', 'DESC')
                ->get();
            return view('v_super_admin.daftar_pengguna', compact('level', 'unitKerja', 'pegawai', 'pengguna'));
        } elseif ($aksi == 'proses-tambah') {
            $cekUsername  = Validator::make($request->all(), [
                'username'    => 'unique:users',
            ]);
            if ($cekUsername->fails()) {
                return redirect('super-admin/pengguna/data/semua')->with('failed', 'Username telah terdaftar');
            } else {
                $user = User::count();
                $id   = Carbon::now()->isoFormat('DMYY') . rand(100, 999) . $user;
                $pengguna = new User();
                $pengguna->id               = $id;
                $pengguna->level_id         = $request->input('id_level');
                $pengguna->pegawai_id       = $request->input('id_pegawai');
                $pengguna->username         = $request->input('username');
                $pengguna->password         = Hash::make($request->input('password'));
                $pengguna->password_teks    = $request->input('password');
                $pengguna->status_google2fa = 0;
                $pengguna->save();

                $cekHakAkses = UserAkses::count();
                $idPengguna  = User::select('id')->orderBy('id', 'DESC')->first();
                $hakAkses = new UserAkses();
                $hakAkses->id_user_akses    = $cekHakAkses + 1;
                $hakAkses->user_id          = $id;
                $hakAkses->is_oldat         = $request->input('is_oldat');
                $hakAkses->is_aadb          = $request->input('is_aadb');
                $hakAkses->is_atk           = $request->input('is_atk');
                $hakAkses->is_mtc           = $request->input('is_mtc');
                $hakAkses->save();

                return redirect('super-admin/pengguna/data/semua')->with('success', 'Berhasil membuat pengguna baru');
            }
        } elseif ($aksi == 'proses-ubah') {

            $cekData = User::where('username', $request->username)->where('id', $id)->count();
            if ($cekData == 0) {
                $cekUsername  = Validator::make($request->all(), [
                    'username'    => 'unique:users',
                ]);
                if ($cekUsername->fails()) {
                    return redirect('super-admin/pengguna/data/semua')->with('failed', 'Username telah terdaftar');
                }
            }

            $pengguna = User::where('id', $id)->update([
                'level_id'      => $request->id_level,
                'pegawai_id'    => $request->id_pegawai,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'password_teks' => $request->password
            ]);

            $hakAkses = UserAkses::where('user_id', $id)->update([
                'is_oldat'  => $request->is_oldat,
                'is_aadb'   => $request->is_aadb,
                'is_atk'    => $request->is_atk,
                'is_mtc'    => $request->is_mtc
            ]);

            return redirect('super-admin/pengguna/data/semua')->with('success', 'Berhasil mengubah informasi pengguna');
        } elseif ($aksi == 'download') {
            return Excel::download(new UserExport(), 'data_pengguna.xlsx');
        } else {
            $pengguna = User::where('id', $id);
            $pengguna->delete();
            return redirect('super-admin/pengguna/data/semua')->with('success', 'Berhasil menghapus pengguna');
        }
    }

    // ====================================================
    //                      PEGAWAI
    // ====================================================

    public function showEmployees(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $jabatan   = PegawaiJabatan::get();
            $timKerja  = TimKerja::get();
            $unitKerja = UnitKerja::get();
            $pegawai   = Pegawai::leftjoin('tbl_pegawai_jabatan', 'tbl_pegawai_jabatan.id_jabatan', 'tbl_pegawai.jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->orderBy('id_pegawai', 'DESC')
                ->get();

            return view('v_super_admin.daftar_pegawai', compact('jabatan', 'timKerja', 'unitKerja', 'pegawai'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData   = Pegawai::count();
            $idPegawai = str_pad($cekData + 1, 4, 0, STR_PAD_LEFT);
            $pegawai = new Pegawai();
            $pegawai->id_pegawai         = '100122' . $idPegawai;
            $pegawai->nip_pegawai        = $request->input('nip');
            $pegawai->nama_pegawai       = $request->input('nama_pegawai');
            $pegawai->nohp_pegawai       = $request->input('nohp_pegawai');
            $pegawai->jabatan_id         = $request->input('id_jabatan');
            $pegawai->tim_kerja_id       = $request->input('id_tim_kerja');
            $pegawai->unit_kerja_id      = $request->input('id_unit_kerja');
            $pegawai->keterangan_pegawai = $request->input('keterangan_pegawai');
            $pegawai->save();

            return redirect('super-admin/pegawai/data/semua')->with('success', 'Berhasil menambah data pegawai');
        } elseif ($aksi == 'proses-ubah') {
            $pegawai = Pegawai::where('id_pegawai', $id)->update([
                'nip_pegawai'           => $request->nip,
                'nama_pegawai'          => ucwords($request->nama_pegawai),
                'nohp_pegawai'          => $request->nohp_pegawai,
                'jabatan_id'            => $request->id_jabatan,
                'tim_kerja_id'          => $request->id_tim_kerja,
                'unit_kerja_id'         => $request->id_unit_kerja,
                'keterangan_pegawai'    => $request->input('keterangan_pegawai')
            ]);
            return redirect('super-admin/pegawai/data/semua')->with('success', 'Berhasil mengubah data pegawai');
        } elseif ($aksi == 'upload') {
            Excel::import(new PegawaiImport(), $request->upload);
            return redirect('super-admin/pegawai/data/semua')->with('success', 'Berhasil mengupload data pegawai');
        } elseif ($aksi == 'download') {
            return Excel::download(new PegawaiExport(), 'data_pegawai.xlsx');
        } else {
            $cekData = User::join('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'users.pegawai_id')->where('pegawai_id', $id)->count();

            if ($cekData == 1) {
                return redirect('super-admin/pegawai/data/semua')->with('failed', 'Terjadi Kesalahan, Tidak Dapat Menghapus Data Pegawai Ini');
            } else {
                $pegawai = Pegawai::where('id_pegawai', $id);
                $pegawai->delete();
                return redirect('super-admin/pegawai/data/semua')->with('success', 'Berhasil menghapus data pegawai');
            }
        }
    }

    // ====================================================
    //                      TIM KERJA
    // ====================================================

    public function showWorkteam(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $unitKerja = UnitKerja::get();
            $timKerja  = TimKerja::join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_tim_kerja.unit_kerja_id')->get();
            return view('v_super_admin.daftar_tim_kerja', compact('unitKerja', 'timKerja'));
        } elseif ($aksi == 'upload') {
            Excel::import(new TimKerjaImport(), $request->upload);
            return redirect('super-admin/tim-kerja/data/semua')->with('success', 'Berhasil mengupload data tim kerja');
        } elseif ($aksi == 'proses-tambah') {
            $cekData  = TimKerja::get()->count();
            $timKerja = new TimKerja();
            $timKerja->id_tim_kerja  = $cekData + 1;
            $timKerja->unit_kerja_id = $request->input('id_unit_kerja');
            $timKerja->tim_kerja     = strtolower($request->input('tim_kerja'));
            $timKerja->save();
            return redirect('super-admin/tim-kerja/data/semua')->with('success', 'Berhasil menambah data tim kerja');
        } elseif ($aksi == 'proses-ubah') {
            $timKerja = TimKerja::where('id_tim_kerja', $id)->update([
                'unit_kerja_id' => $request->id_unit_kerja,
                'tim_kerja'     => strtolower($request->tim_kerja)
            ]);
            return redirect('super-admin/tim-kerja/data/semua')->with('success', 'Berhasil mengubah informasi tim kerja');
        } elseif ($aksi == 'download') {
            return Excel::download(new TimKerjaExport(), 'data_tim_kerja.xlsx');
        } else {
            $timKerja = TimKerja::where('id_tim_kerja', $id);
            $timKerja->delete();
            return redirect('super-admin/tim-kerja/data/semua')->with('success', 'Berhasil menghapus data tim kerja');
        }
    }

    // ====================================================
    //                      UNIT KERJA
    // ====================================================

    public function showWorkunit(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $unitKerja  = UnitKerja::join('tbl_unit_utama', 'tbl_unit_utama.id_unit_utama', 'tbl_unit_kerja.unit_utama_id')->get();
            return view('v_super_admin.daftar_unit_kerja', compact('unitKerja'));
        } elseif ($aksi == 'upload') {
            Excel::import(new UnitKerjaImport(), $request->upload);
            return redirect('super-admin/unit-kerja/data/semua')->with('success', 'Berhasil mengupload data unit kerja');
        } elseif ($aksi == 'download') {
            return Excel::download(new UnitKerjaExport(), 'data_unit_kerja.xlsx');
        } else {
            $unitKerja = UnitKerja::where('id_unit_kerja', $id);
            $unitKerja->delete();
            return redirect('super-admin/unit-kerja/data/semua')->with('success', 'Berhasil menghapus data unit kerja');
        }
    }

    // ====================================================
    //                     LAPORAN
    // ====================================================


    public function ReportAtk()
    {
        $atk = UsulanAtk::select('unit_kerja', DB::RAW('COUNT(id_form_usulan) as total_usulan'))
                ->where('status_pengajuan_id', 1)
                ->whereIn('jenis_form', ['distribusi','permintaan'])
                ->join('users','id','user_id')
                ->join('tbl_pegawai','id_pegawai','users.pegawai_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->groupBy('unit_kerja')
                ->orderBy('unit_kerja', 'ASC')
                ->get();

        return view('v_super_admin.apk_atk.laporan', compact('atk'));
    }

    public function ReportGdn()
    {
        $gdn = UsulanGdn::select('unit_kerja', DB::RAW('COUNT(id_form_usulan) as total_usulan'))
                ->where('status_pengajuan_id', 1)
                ->join('users','id','user_id')
                ->join('tbl_pegawai','id_pegawai','users.pegawai_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->groupBy('unit_kerja')
                ->orderBy('unit_kerja', 'ASC')
                ->get();

        return view('v_super_admin.apk_gdn.laporan', compact('gdn'));
    }


    public function ReportUkt()
    {
        $ukt = UsulanUkt::select('unit_kerja', DB::RAW('COUNT(id_form_usulan) as total_usulan'))
                ->where('status_pengajuan_id', 1)
                ->join('users','id','user_id')
                ->join('tbl_pegawai','id_pegawai','users.pegawai_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->groupBy('unit_kerja')
                ->orderBy('unit_kerja', 'ASC')
                ->get();

        return view('v_super_admin.apk_ukt.laporan', compact('ukt'));
    }
}
