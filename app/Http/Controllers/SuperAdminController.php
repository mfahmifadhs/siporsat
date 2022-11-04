<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserExport;
use App\Exports\PegawaiExport;
use App\Exports\TimKerjaExport;
use App\Exports\UnitKerjaExport;
use App\Exports\BarangExport;

use App\Imports\TimKerjaImport;
use App\Imports\UnitKerjaImport;
use App\Imports\PegawaiImport;


use App\Models\AADB\Kendaraan;
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
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\TimKerja;
use App\Models\UserAkses;

use Auth;
use Hash;
use Validator;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index()
    {
        return view('v_super_admin.index');
    }

    // ====================================================
    //                       AADB
    // ====================================================

    public function aadb()
    {
        return view('v_super_admin.apk_aadb.index');
    }

    public function vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {

            if ($id == 'kendaraan') {
                $kendaraan = Kendaraan::get();
                return view('v_super_admin.apk_aadb.daftar_kendaraan', compact('kendaraan'));
            } else {
            }
        } else {
        }
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

        return view('v_super_admin.apk_oldat.index', compact('googleChartData', 'usulan'));
    }

    public function Items(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $char = '"';
            $barang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang', 'satuan_barang', 'nilai_perolehan', 'tahun_perolehan',
                'kondisi_barang', 'nama_pegawai', \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"), 'unit_kerja')
                ->join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','oldat_tbl_barang.unit_kerja_id')
                ->orderBy('tahun_perolehan', 'DESC')
                ->get();

            $result = json_decode($barang);
            return view('v_super_admin.apk_oldat.daftar_barang', compact('barang'));

        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai','ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', 'like', '%'.$id.'%')->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang','id_barang','barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->join('tbl_pegawai','tbl_pegawai.id_pegawai','oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->where('barang_id', 'like', '%'.$id.'%')->get();

            return view('v_super_admin.apk_oldat.detail_barang', compact('kategoriBarang','kondisiBarang','pegawai','barang','riwayat'));

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
                return redirect('super-admin/oldat/barang/detail/'. $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            }else{
                if($request->foto_barang == null) {
                    $fotoBarang = $request->foto_lama;
                } else {
                    $dataBarang = Barang::where('id_barang', 'like', '%'.$id.'%')->first();

                    if ($request->hasfile('foto_barang')){
                        if($dataBarang->foto_barang != ''  && $dataBarang->foto_barang != null){
                            $file_old = public_path().'\gambar\barang_bmn\\' . $dataBarang->foto_barang;
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
                $barang = Barang::where('id_barang', 'like', '%'.$id.'%')->update([
                    'pegawai_id'            => $request->id_pegawai,
                    'kategori_barang_id'    => $request->id_kategori_barang,
                    'kode_barang'           => $request->kode_barang,
                    'nup_barang'            => $request->nup_barang,
                    'spesifikasi_barang'    => $request->spesifikasi_barang,
                    'jumlah_barang'         => $request->jumlah_barang,
                    'satuan_barang'         => $request->satuan_barang,
                    'kondisi_barang_id'     => $request->id_kondisi_barang,
                    'nilai_perolehan'       => $request->nilai_perolehan,
                    'tahun_perolehan'       => $request->tahun_perolehan,
                    'nilai_perolehan'       => $request->nilai_perolehan,
                    'foto_barang'           => $fotoBarang

                ]);
            }
            if ($request->proses == 'pengguna-baru') {
                $cekBarang = RiwayatBarang::count();
                $riwayat   = new RiwayatBarang();
                $riwayat->id_riwayat_barang = $cekBarang + 1;
                $riwayat->barang_id         = $id;
                $riwayat->pegawai_id        = $request->input('id_pegawai');
                $riwayat->tanggal_pengguna  = Carbon::now();
                $riwayat->kondisi_barang_id = $request->input('id_kondisi_barang');
                $riwayat->save();
            }

            return redirect('super-admin/oldat/barang/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Barang');

        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $request->id_riwayat_barang)->update([
                'tanggal_pengguna'     => $request->tanggal_pengguna,
                'keperluan_penggunaan' => $request->keperluan_penggunaan
            ]);

            return redirect('super-admin/oldat/barang/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Barang');

        } elseif ($aksi == 'hapus-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $id)->delete();
            return redirect('super-admin/oldat/barang/detail/'. $id)->with('success', 'Berhasil Menghapus Riwayat Barang');

        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
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
            return redirect('super-admin/oldat/usulan/daftar/seluruh-usulan')->with('success','Berhasil Menghapus Usulan');
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
        $dataBarang = Barang::select(
            'id_barang',
            'kode_barang',
            'kategori_barang',
            'nup_barang',
            'jumlah_barang',
            'satuan_barang',
            'nilai_perolehan',
            'tahun_perolehan',
            'kondisi_barang',
            'nama_pegawai',
            \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"),
            'unit_kerja'
        )
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
        $dataBarang = Barang::select(
            'id_barang',
            'kode_barang',
            'kategori_barang',
            'nup_barang',
            'jumlah_barang',
            'satuan_barang',
            'nilai_perolehan',
            'tahun_perolehan',
            'kondisi_barang',
            'nama_pegawai',
            \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"),
            'unit_kerja'
        )
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
        // dd($dataChart);
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
                ->get();
            return view('v_super_admin.daftar_pengguna', compact('level', 'unitKerja', 'pegawai', 'pengguna'));
        } elseif ($aksi == 'proses-tambah') {
            $cekUsername  = Validator::make($request->all(), [
                'username'    => 'unique:users',
            ]);
            if ($cekUsername->fails()) {
                return redirect('super-admin/pengguna/data/semua')->with('failed', 'Username telah terdaftar');
            } else {
                $cekData  = User::get()->count();
                $pengguna = new User();
                $pengguna->id               = $cekData + 1;
                $pengguna->level_id         = $request->input('id_level');
                $pengguna->pegawai_id       = $request->input('id_pegawai');
                $pengguna->username         = $request->input('username');
                $pengguna->password         = Hash::make($request->input('password'));
                $pengguna->password_teks    = $request->input('password');
                $pengguna->save();

                $cekHakAkses = UserAkses::count();
                $idPengguna  = User::select('id')->orderBy('id', 'DESC')->first();
                $hakAkses = new UserAkses();
                $hakAkses->id_user_akses    = $cekHakAkses + 1;
                $hakAkses->user_id          = $idPengguna->id;
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
                ->orderBy('nama_pegawai')
                ->get();

            return view('v_super_admin.daftar_pegawai', compact('jabatan', 'timKerja', 'unitKerja', 'pegawai'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData   = Pegawai::count();
            $idPegawai = str_pad($cekData + 1, 4, 0, STR_PAD_LEFT);
            $pegawai = new Pegawai();
            $pegawai->id_pegawai         = '100122' . $idPegawai;
            $pegawai->nip_pegawai        = $request->input('nip');
            $pegawai->nama_pegawai       = strtoupper($request->input('nama_pegawai'));
            $pegawai->nohp_pegawai       = $request->input('nohp_pegawai');
            $pegawai->jabatan_id         = $request->input('id_jabatan');
            $pegawai->tim_kerja_id       = $request->input('id_tim_kerja');
            $pegawai->unit_kerja_id      = $request->input('id_unit_kerja');
            $pegawai->keterangan_pegawai = $request->input('keterangan_pegawai');
            $pegawai->save();

            return redirect('super-admin/pegawai/data/semua')->with('success', 'Berhasil menambah data pegawai');
        } elseif ($aksi == 'proses-ubah') {
            $pegawai = Pegawai::where('id_pegawai', $id)->update([
                'nip_pegawai'           => $request->nip_pegawai,
                'nama_pegawai'          => ucwords($request->nama_pegawai),
                'nohp_pegawai'          => $request->nohp_pegawai,
                'jabatan_id'            => $request->id_jabatan,
                'tim_kerja_id'          => $request->id_tim_kerja,
                'unit_kerja_id'         => $request->id_unit_kerja,
                'keterangan_pegawai'    => ucwords($request->keterangan_pegawai)
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
    //                      UNIT UTAMA
    // ====================================================
}
