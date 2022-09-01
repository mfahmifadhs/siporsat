<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UserExport;
use App\Exports\LevelSubExport;
use App\Exports\TimKerjaExport;
use App\Exports\UnitKerjaExport;

use App\Imports\TimKerjaImport;
use App\Imports\UnitKerjaImport;

use App\Models\Aplikasi;
use App\Models\Level;
use App\Models\LevelSub;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\UnitUtama;
use App\Models\TimKerja;

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
    //                      LEVEL
    // ====================================================

    public function showLevel(Request $request, $aksi, $id)
    {
        if ($aksi == 'sub') {
            $aplikasi   = Aplikasi::get();
            $timKerja   = TimKerja::get();
            $subLevel   = LevelSub::join('tbl_level', 'tbl_level.id_level', 'tbl_level_sub.level_id')
                ->join('tbl_aplikasi', 'tbl_aplikasi.id_aplikasi', 'tbl_level_sub.aplikasi_id')->get();
            return view('v_super_admin.apk_oldat.daftar_level_sub', compact('timKerja', 'aplikasi', 'subLevel'));
        } elseif ($aksi == 'sub-proses-ubah') {
            $cekData  = LevelSub::where('id_sub_level', $id)->update(['aplikasi_id' => $request->id_aplikasi, 'sub_level' => strtolower($request->sublevel)]);
            return redirect('super-admin/level/sub/data')->with('success', 'Berhasil mengubah sub level');
        } elseif ($aksi == 'sub-proses-tambah') {
            $cekData  = LevelSub::get()->count();
            $subLevel = new LevelSub();
            $subLevel->id_sub_level = $cekData + 1;
            $subLevel->level_id     = $request->input('id_level');
            $subLevel->aplikasi_id  = $request->input('id_aplikasi');
            $subLevel->sub_level    = strtolower($request->input('sublevel'));
            $subLevel->save();
            return redirect('super-admin/level/sub/data')->with('success', 'Berhasil menambahkan sub level baru');
        } elseif ($aksi == 'download') {
            return Excel::download(new LevelSubExport(), 'sub_level_pengguna.xlsx');
        } else {
            $subLevel = LevelSub::where('id_sub_level', $id);
            $subLevel->delete();
            return redirect('super-admin/level/sub/data')->with('success', 'Berhasil menghapus sub level');
        }

        if ($aksi == 'utama') {
            $level = Level::get();
            return view('v_super_admin.apk_oldat.daftar_level_utama', compact('level'));
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
            $pengguna  = User::join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'users.unit_kerja_id')
                ->join('tbl_unit_utama', 'tbl_unit_utama.id_unit_utama', 'tbl_unit_kerja.unit_utama_id')
                ->join('tbl_level', 'tbl_level.id_level', 'users.level_id')
                ->get();
            return view('v_super_admin.apk_oldat.daftar_pengguna', compact('level', 'unitKerja', 'pengguna'));
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
                $pengguna->unit_kerja_id    = $request->input('id_unit_kerja');
                $pengguna->nama             = strtolower($request->input('nama'));
                $pengguna->jabatan          = strtolower($request->input('jabatan'));
                $pengguna->username         = $request->input('username');
                $pengguna->password         = Hash::make($request->input('password'));
                $pengguna->password_teks    = $request->input('password');
                $pengguna->save();
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
                'unit_kerja_id' => $request->id_unit_kerja,
                'nama'          => strtolower($request->nama),
                'jabatan'       => strtolower($request->jabatan),
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'password_teks' => $request->password
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
    //                      TIM KERJA
    // ====================================================

    public function showWorkteam(Request $request, $aksi, $id)
    {
        if ($aksi == 'data'){
            $unitKerja = UnitKerja::get();
            $timKerja  = TimKerja::join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_tim_kerja.unit_kerja_id')->get();
            return view('v_super_admin.apk_oldat.daftar_tim_kerja', compact('unitKerja','timKerja'));
        } elseif($aksi == 'upload') {
            Excel::import(new TimKerjaImport(), $request->upload);
            return redirect('super-admin/tim-kerja/data/semua')->with('success','Berhasil mengupload data tim kerja');
        } elseif($aksi == 'proses-tambah') {
            $cekData  = TimKerja::get()->count();
            $timKerja = new TimKerja();
            $timKerja->id_tim_kerja  = $cekData + 1;
            $timKerja->unit_kerja_id = $request->input('id_unit_kerja');
            $timKerja->tim_kerja     = strtolower($request->input('tim_kerja'));
            $timKerja->save();
            return redirect('super-admin/tim-kerja/data/semua')->with('success','Berhasil menambah data tim kerja');
        } elseif($aksi == 'proses-ubah') {
            $timKerja = TimKerja::where('id_tim_kerja', $id)->update([
                'unit_kerja_id' => $request->id_unit_kerja,
                'tim_kerja'     => strtolower($request->tim_kerja)
            ]);
            return redirect('super-admin/tim-kerja/data/semua')->with('success','Berhasil mengubah informasi tim kerja');
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
        if ($aksi == 'data'){
            $unitKerja  = UnitKerja::join('tbl_unit_utama','tbl_unit_utama.id_unit_utama','tbl_unit_kerja.unit_utama_id')->get();
            return view('v_super_admin.apk_oldat.daftar_unit_kerja', compact('unitKerja'));
        } elseif($aksi == 'upload') {
            Excel::import(new UnitKerjaImport(), $request->upload);
            return redirect('super-admin/unit-kerja/data/semua')->with('success','Berhasil mengupload data unit kerja');
        } elseif($aksi == 'download') {
            return Excel::download(new UnitKerjaExport(), 'data_unit_kerja.xlsx');
        } else {
            $unitKerja = UnitKerja::where('id_unit_kerja', $id);
            $unitKerja->delete();
            return redirect('super-admin/unit-kerja/data/semua')->with('success', 'Berhasil menghapus data unit kerja');
        }
    }
}
