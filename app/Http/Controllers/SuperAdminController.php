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

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Level;
use App\Models\LevelSub;
use App\Models\PegawaiJabatan;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\UnitUtama;
use App\Models\TimKerja;
use App\Models\UserAkses;
use App\Models\FormUsulan;
use App\Models\FormUsulanPengadaan;
use App\Models\FormUsulanPerbaikan;

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
    //                    PENGAJUAN
    // ====================================================

    public function submission(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $formUsulan  = FormUsulan::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();

            return view('v_super_admin.apk_oldat.daftar_pengajuan', compact('formUsulan'));
        } else {
            $cekForm = FormUsulan::where('id_form_usulan', $id)->first();
            if($cekForm->jenis_form == 'pengadaan') {
                $formUsulan = FormUsulan::where('id_form_usulan', $id);
                $detailUsulan = FormUsulanPengadaan::where('form_usulan_id', $id);
            } else {
                $formUsulan = FormUsulan::where('id_form_usulan', $id);
                $detailUsulan = FormUsulanPerbaikan::where('form_usulan_id', $id);
            }
            $formUsulan->delete();
            $detailUsulan->delete();
            return redirect('super-admin/oldat/pengajuan/data/semua-pengajuan')->with('success', 'Berhasil Menghapus Pengajuan');
        }
    }

    // ====================================================
    //                    BARANG
    // ====================================================

    public function showItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $barang         = Barang::join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id')
            ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
            ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
            ->get();
            return view('v_super_admin.apk_oldat.daftar_barang', compact('kategoriBarang', 'kondisiBarang', 'barang'));
        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai','ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
            ->where('id_barang', $id)->first();
            return view('v_super_admin.apk_oldat.detail_barang', compact('kategoriBarang','kondisiBarang','pegawai','barang'));
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
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id)->update([
                'kategori_barang' => strtolower($request->kategori_barang)
            ]);
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Mengubah Kategori Barang');
        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    // ====================================================
    //                 KATEGORI BARANG
    // ====================================================

    public function showCategoryItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $kategoriBarang = KategoriBarang::get();
            return view('v_super_admin.apk_oldat.daftar_kategori_barang', compact('kategoriBarang'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id)->update([
                'kategori_barang' => strtolower($request->kategori_barang)
            ]);
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Mengubah Kategori Barang');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
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
            $pegawai   = Pegawai::get();
            $pengguna  = UserAkses::rightjoin('users','users.id','tbl_users_akses.user_id')
                ->join('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'users.pegawai_id')
                ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'tbl_unit_utama.id_unit_utama', 'tbl_unit_kerja.unit_utama_id')
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
                $idPengguna  = User::select('id')->orderBy('id','DESC')->first();
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
                ->get();
            return view('v_super_admin.daftar_pegawai', compact('jabatan', 'timKerja', 'unitKerja', 'pegawai'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData = Pegawai::count();
            $pegawai = new Pegawai();
            $pegawai->id_pegawai         = $cekData + 1;
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
