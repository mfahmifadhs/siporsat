<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserExport;
use App\Exports\PegawaiExport;
use App\Exports\TimKerjaExport;
use App\Exports\UnitKerjaExport;

use App\Imports\TimKerjaImport;
use App\Imports\UnitKerjaImport;
use App\Imports\PegawaiImport;

use App\Models\Aplikasi;
use App\Models\Level;
use App\Models\LevelSub;
use App\Models\PegawaiJabatan;
use App\Models\Pegawai;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\UnitUtama;
use App\Models\TimKerja;
use App\Models\UserAkses;

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
