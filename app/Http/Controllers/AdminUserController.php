<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\BarangExport;
use App\Imports\AADB\KendaraanImport;
use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\KondisiKendaraan;
use App\Models\AADB\JenisUsulan;
use App\Models\AADB\UsulanAadb;
use App\Models\AADB\UsulanKendaraan;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use App\Models\OLDAT\RiwayatBarang;
use App\Models\Pegawai;
use App\Models\RDN\KondisiRumah;
use App\Models\RDN\PenghuniRumah;
use App\Models\ATK\UsulanAtk;
use App\Models\ATK\UsulanAtkPengadaan;
use App\Models\ATK\UsulanAtkPermintaan;
use App\Models\ATK\BastAtk;
use App\Models\ATK\BastAtkDetail;
use App\Models\ATK\Atk;
use App\Models\ATK\KategoriAtk;
use App\Models\ATK\TransaksiAtk;
use App\Models\ATK\TransaksiAtkDetail;
use App\Models\ATK\RiwayatAtk;
use App\Models\GDN\UsulanGdn;
use App\Models\OLDAT\FormUsulan;
use App\Models\RDN\RumahDinas;
use App\Models\UKT\UsulanUkt;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Auth;
use Google2FA;
use Hash;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    public function Index()
    {
        $usulanOldat  = FormUsulan::get();
        $usulanAadb   = UsulanAadb::get();
        $usulanAtk    = UsulanAtk::get();
        $usulanGdn    = UsulanGdn::get();
        $usulanUkt    = UsulanUkt::get();

        // Report Oldat
        $oldatUsulan    = FormUsulan::get();
        $oldatJenisForm = ['pengadaan', 'perbaikan'];
        if ($oldatJenisForm == []) {
            foreach ($oldatJenisForm as $data) {
                $dataArray['usulan']  = $data;
                $dataArray['ditolak'] = $oldatUsulan->where('status_pengajuan_id', 2)->where('jenis_form', $data)->count();
                $dataArray['proses']  = $oldatUsulan->where('status_proses_id', 2)->where('jenis_form', $data)->count();
                $dataArray['selesai'] = $oldatUsulan->where('status_proses_id', 5)->where('jenis_form', $data)->count();
                $reportOldat[]        = $dataArray;
                unset($dataArray);
            }
        } else {
            $reportOldat[] = null;
        }

        // Report AADB
        $aadbUsulan     = UsulanAadb::get();
        $aadbJenisForm  = JenisUsulan::get();

        if ($aadbJenisForm == []) {
            foreach ($aadbJenisForm as $data) {
                $dataArray['usulan']  = $data->jenis_form_usulan;
                $dataArray['ditolak'] = $aadbUsulan->where('status_pengajuan_id', 2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
                $dataArray['proses']  = $aadbUsulan->where('status_proses_id', 2)->where('jenis_form', $data->id_jenis_form_usulan)->count();
                $dataArray['selesai'] = $aadbUsulan->where('status_proses_id', 5)->where('jenis_form', $data->id_jenis_form_usulan)->count();
                $reportAadb[]         = $dataArray;
                unset($dataArray);
            }
        } else {
            $reportAadb[] = null;
        }

        // Report ATK
        $atkUsulan    = UsulanAtk::get();
        $atkJenisForm = UsulanAtk::select('jenis_form')->groupBy('jenis_form')->get();
        if ($atkJenisForm == []) {
            foreach ($atkJenisForm as $data) {
                $dataArray['usulan']  = $data->jenis_form;
                $dataArray['ditolak'] = $atkUsulan->where('status_pengajuan_id', 2)->where('jenis_form', $data->jenis_form)->count();
                $dataArray['proses']  = $atkUsulan->where('status_proses_id', 2)->where('jenis_form', $data->jenis_form)->count();
                $dataArray['selesai'] = $atkUsulan->where('status_proses_id', 5)->where('jenis_form', $data->jenis_form)->count();
                $reportAtk[]          = $dataArray;
                unset($dataArray);
            }
        } else {
            $reportAtk[] = null;
        }

        return view('v_admin_user.index', compact('usulanUkt', 'usulanOldat', 'usulanAadb', 'usulanAtk', 'usulanGdn', 'reportOldat', 'reportAadb', 'reportAtk'));
    }

    public function Profile(Request $request, $aksi, $id)
    {
        $user = User::where('id', Auth::user()->id)
            ->select(
                'id',
                'id_pegawai',
                'nip_pegawai',
                'nohp_pegawai',
                'nama_pegawai',
                'keterangan_pegawai',
                'unit_kerja',
                'username',
                'level',
                'status_google2fa',
                'password'
            )
            ->leftjoin('tbl_level', 'id_level', 'level_id')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
            ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->first();

        if ($aksi == 'user') {
            $google2fa  = app('pragmarx.google2fa');
            $secretkey  = $google2fa->generateSecretKey();
            $QR_Image   = $google2fa->getQRCodeInline(
                $registration_data = 'Siporsat Kemenkes',
                $registration_data = Auth::user()->username,
                $registration_data = $secretkey
            );
            return view('v_admin_user.profil', compact('user', 'QR_Image', 'secretkey'));
        } elseif ($aksi == 'reset-autentikasi') {
            User::where('id', $id)->update(['status_google2fa' => null]);
            return redirect('admin-user/profil/user/' . Auth::user()->id)->with('success', 'Berhasil mereset autentikasi 2fa');
        } elseif ($aksi == 'edit-profil') {
            $username = User::where('id', $id)->where('username', $request->username)->count();
            Pegawai::where('id_pegawai', $request->id_pegawai)
                ->update([
                    'nip_pegawai'   => $request->nip,
                    'nama_pegawai'  => $request->nama_pegawai,
                    'nohp_pegawai'  => $request->nohp_pegawai,
                    'keterangan_pegawai' => strtoupper($request->keterangan_pegawai)
                ]);


            if ($username != 1) {
                $cekUser    = Validator::make($request->all(), [
                    'username'   => 'unique:users'
                ]);

                if ($cekUser->fails()) {
                    return redirect('admin-user/profil/user/' . $id)->with('failed', 'Username sudah terdaftar');
                } else {
                    User::where('id', $id)
                        ->update([
                            'username' => $request->username
                        ]);
                }
                return redirect('keluar')->with('success', 'Berhasil mengubah username');
            }

            return redirect('admin-user/profil/user/' . $id)->with('success', 'Berhasil mengubah informasi profil');
        } elseif ($aksi == 'edit-password') {
            $pass = User::where('password_teks', $request->old_password)->where('id', Auth::user()->id)->count();
            if ($pass != 1) {
                return redirect('admin-user/profil/user/' . $id)->with('failed', 'Password lama anda salah');
            }

            $cekPass    = Validator::make($request->all(), [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ]);

            if ($cekPass->fails()) {
                return redirect('admin-user/profil/user/' . $id)->with('failed', 'Konfirmasi password salah');
            } else {
                User::where('id', $id)
                    ->update([
                        'password'      => Hash::make($request->password),
                        'password_teks' => $request->password
                    ]);
            }

            return redirect('keluar')->with('success', 'Berhasil mengubah password');
        } else {
            User::where('id', $id)->first();
            User::where('id', $id)->update([
                'google2fa_secret' => encrypt($request->secretkey),
                'status_google2fa' => 1
            ]);

            return redirect('admin-user/dashboard');
        }
    }

    public function Verification(Request $request, $aksi, $id)
    {
        if ($id == 'cek') {
            if (Auth::user()->sess_modul == 'atk') {
                $usulan = UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == 4) {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_pengusul' => $request->one_time_password,
                        'status_proses_id'  => 5
                    ]);
                    Google2FA::logout();

                    return redirect('admin-user/atk/surat/surat-bast/' . Auth::user()->sess_form_id);
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

    public function Letter(Request $request, $aksi, $id)
    {
        // Surat Usulan
        if ($aksi == 'usulan-ukt') {
            $modul = 'ukt';
            $form  = UsulanUkt::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanUkt::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-gdn') {
            $modul = 'gdn';
            $form  = UsulanGdn::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanGdn::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-atk') {
            $modul = 'atk';
            $form  = UsulanAtk::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        }
        // Berita Acara Serah Terima
        if ($aksi == 'bast-atk') {
            $modul = 'atk';
            if ($id == 'daftar') {
                $bast = BastAtk::join('atk_tbl_form_usulan', 'id_form_usulan', 'usulan_id')
                    ->select('id_bast', 'usulan_id', 'jenis_form', 'tanggal_usulan', 'no_surat_usulan', 'atk_tbl_bast.tanggal_bast', 'nomor_bast')
                    ->orderBy('tanggal_bast', 'DESC')
                    ->get();
                return view('v_admin_user/daftar_bast', compact('bast', 'modul'));
            } else {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->where('jabatan_id', '2')
                    ->where('unit_kerja_id', 465930)
                    ->first();

                $bast = BastAtk::where('id_bast', $id)
                    ->join('atk_tbl_form_usulan', 'id_form_usulan', 'usulan_id')
                    ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                    ->first();

                return view('v_admin_user/surat_bast', compact('pimpinan', 'bast', 'modul'));
            }
        } elseif ($aksi == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $form      = UsulanAadb::where('id_form_usulan', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/surat_bast', compact('pimpinan', 'bast', 'modul', 'jenisAadb'));
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
            return view('v_admin_user.surat_bast', compact('modul', 'bast', 'pimpinan'));
        } elseif ($aksi == 'bast-gdn') {
            $modul = 'gdn';
            $form  = UsulanUkt::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $bast = UsulanGdn::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user.surat_bast', compact('modul', 'bast', 'pimpinan'));
        } elseif ($aksi == 'bast-ukt') {
            $modul = 'ukt';
            $form  = UsulanUkt::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $bast = UsulanUkt::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user.surat_bast', compact('modul', 'bast', 'pimpinan'));
        }
        // Detail Berita Acara Serah Terima
        if ($aksi == 'detail-bast-oldat') {
            $modul = 'oldat';

            $bast = FormUsulan::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user.detail_bast', compact('modul', 'bast'));
        } elseif ($aksi == 'detail-bast-aadb') {
            $modul = 'aadb';
            $form      = UsulanAadb::where('id_form_usulan', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/detail_bast', compact('bast', 'modul', 'jenisAadb'));
        } elseif ($aksi == 'detail-bast-atk') {
            $modul = 'atk';
            $bast   = BastAtk::join('atk_tbl_form_usulan', 'id_form_usulan', 'usulan_id')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->select(
                    'atk_tbl_bast.*',
                    'atk_tbl_form_usulan.*',
                    'tbl_pegawai.*',
                    'tbl_unit_kerja.*',
                    'atk_tbl_bast.tanggal_bast',
                    'atk_tbl_bast.otp_bast_ppk',
                    'atk_tbl_bast.otp_bast_pengusul',
                    'atk_tbl_bast.otp_bast_kabag'
                )
                ->where('id_bast', $id)
                ->first();
            return view('v_admin_user.detail_bast', compact('modul', 'bast'));
        } elseif ($aksi == 'detail-bast-gdn') {
            $modul = 'gdn';

            $form      = UsulanGdn::where('id_form_usulan', $id)->pluck('id_form_usulan');

            $bast = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/detail_bast', compact('bast', 'modul'));

        } elseif ($aksi == 'detail-bast-ukt') {
            $modul = 'ukt';

            $form      = UsulanUkt::where('id_form_usulan', $id)->pluck('id_form_usulan');

            $bast = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/detail_bast', compact('bast', 'modul'));
        }
    }

    public function PrintLetter(Request $request, $modul, $id)
    {
        // Cetak Usulan
        if ($modul == 'usulan-ukt') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanUkt::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-gdn') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanGdn::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-atk') {
            $form  = UsulanAtk::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user/print_surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-aadb') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-oldat') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        }
        // Cetak Berita Acara Serah Terima
        if ($modul == 'bast-atk') {
            $modul = 'atk';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 465930)
                ->first();

            $bast = BastAtk::where('id_bast', $id)
                ->join('atk_tbl_form_usulan', 'id_form_usulan', 'usulan_id')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->select(
                    'atk_tbl_bast.*',
                    'atk_tbl_form_usulan.*',
                    'tbl_pegawai.*',
                    'tbl_unit_kerja.*',
                    'atk_tbl_bast.tanggal_bast',
                    'atk_tbl_bast.otp_bast_ppk',
                    'atk_tbl_bast.otp_bast_pengusul',
                    'atk_tbl_bast.otp_bast_kabag'
                )
                ->first();

            return view('v_admin_user/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul'));
        } elseif ($modul == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $form      = UsulanAadb::where('id_form_usulan', $id)->pluck('id_form_usulan');
            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul', 'jenisAadb'));
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
            return view('v_admin_user.print_surat_bast', compact('modul', 'bast', 'pimpinan'));
        } elseif ($modul == 'bast-gdn') {
            $modul = 'gdn';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = UsulanGdn::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user.print_surat_bast', compact('modul', 'bast', 'pimpinan'));
        } elseif ($modul == 'bast-ukt') {
            $modul = 'ukt';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = UsulanUkt::where('id_form_usulan', $id)
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_admin_user.print_surat_bast', compact('modul', 'bast', 'pimpinan'));
        }
        // Cetak Surat Penyerahan ATK
        if ($modul == 'penyerahan-atk') {
            $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_admin_user.apk_atk.surat_penyerahan', compact('usulan'));
        }
    }

    // ====================================================
    //                    RUMAH DINAS
    // ====================================================

    public function Rdn(Request $request, $aksi)
    {
        return view('v_admin_user.apk_rdn.index');
    }

    public function OfficialResidence(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $rumah      = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')->get();
            return view('v_admin_user.apk_rdn.daftar_rumah', compact('rumah'));
        } elseif ($aksi == 'detail') {
            $pegawai  = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')->get();
            $penghuni = PenghuniRumah::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->where('rumah_dinas_id', $id)
                ->orderBy('id_penghuni', 'DESC')
                ->first();
            $rumah    = RumahDinas::where('id_rumah_dinas', $id)
                ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')
                ->first();
            $kondisi  = KondisiRumah::get();

            return view('v_admin_user.apk_rdn.detail_rumah', compact('pegawai', 'rumah', 'penghuni', 'kondisi'));
        } elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_rumah'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('admin-user/rdn/rumah-dinas/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            } else {
                if ($request->foto_rumah == null) {
                    $fotoRumah = $request->foto_lama;
                } else {
                    $dataRumah = RumahDinas::where('id_rumah_dinas', $id)->first();

                    if ($request->hasfile('foto_rumah')) {
                        if ($dataRumah->foto_rumah != ''  && $dataRumah->foto_rumah != null) {
                            $file_old = public_path() . '\gambar\rumah_dinas\\' . $dataRumah->foto_rumah;
                            unlink($file_old);
                        }
                        $file       = $request->file('foto_rumah');
                        $filename   = $file->getClientOriginalName();
                        $file->move('gambar/rumah_dinas/', $filename);
                        $dataRumah->foto_rumah = $filename;
                    } else {
                        $dataRumah->foto_rumah = '';
                    }
                    $fotoRumah = $dataRumah->foto_rumah;
                }

                $rumah = RumahDinas::where('id_rumah_dinas', $id)->update([
                    'golongan_rumah'    => $request->golongan_rumah,
                    'nup_rumah'         => $request->nup_rumah,
                    'alamat_rumah'      => $request->alamat_rumah,
                    'lokasi_kota'       => $request->lokasi_kota,
                    'luas_bangunan'     => $request->luas_bangunan,
                    'luas_tanah'        => $request->luas_tanah,
                    'kondisi_rumah_id'  => $request->kondisi_rumah_id,
                    'foto_rumah'        => $fotoRumah
                ]);
            }

            if ($request->proses == 1) {
                $penghuni = PenghuniRumah::where('rumah_dinas_id', $id)->where('id_penghuni', $request->id_penghuni)
                    ->update([
                        'pegawai_id'         => $request->id_pegawai,
                        'nomor_sip'          => $request->nomor_sip,
                        'masa_berakhir_sip'  => $request->masa_berakhir_sip,
                        'jenis_sertifikat'   => $request->jenis_sertifikat,
                        'status_penghuni'    => $request->status_penghuni
                    ]);
            } else {
                $statusPenghuni = PenghuniRumah::select('status_penghuni')->where('rumah_dinas_id', $id)->get();
                foreach ($statusPenghuni as $dataPenghuni) {
                    if ($dataPenghuni->status_penghuni == 1) {
                        PenghuniRumah::where('rumah_dinas_id', $id)->update(['status_penghuni' => 2]);
                    }
                }

                $cekRiwayat = PenghuniRumah::count();
                $penghuni    = new PenghuniRumah();
                $penghuni->id_penghuni       = $cekRiwayat + 1;
                $penghuni->pegawai_id        = $request->id_pegawai;
                $penghuni->rumah_dinas_id    = $id;
                $penghuni->tanggal_update    = Carbon::now();
                $penghuni->nomor_sip         = $request->nomor_sip;
                $penghuni->masa_berakhir_sip = $request->masa_berakhir_sip;
                $penghuni->jenis_sertifikat  = $request->jenis_sertifikat;
                $penghuni->status_penghuni   = $request->status_penghuni;
                $penghuni->save();
            }

            return redirect('admin-user/rdn/rumah-dinas/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Rumah Dinas');
        }
    }

    // ===============================================
    //             ALAT TULIS KANTOR (--ATK)
    // ===============================================

    public function Atk(Request $request)
    {
        $result = [];
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

        $usulanChart = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"))
            ->groupBy('month')
            ->get();

        $chartData = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'jenis_form')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->get();

        foreach ($usulanChart as $key => $value) {
            $result[] = ['Bulan', 'Total Usulan Pengadaan', 'Total Usulan Distribusi'];
            $result[++$key] = [
                Carbon::parse($value->month)->isoFormat('MMMM Y'),
                $chartData->where('month', $value->month)->where('jenis_form', 'pengadaan')->count(),
                $chartData->where('month', $value->month)->where('jenis_form', 'distribusi')->count(),
            ];
        }

        $usulanChartAtk = json_encode($result);

        return view('v_admin_user.apk_atk.index', compact('usulanUker', 'usulanTotal', 'usulanChartAtk', 'dataChartAtk'));
    }

    public function OfficeStationery(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $googleChartData = $this->ChartDataAtk();
            $usPengadaan = UsulanAtk::where('jenis_form', 'pengadaan')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->get();

            $usDistribusi = UsulanAtk::where('jenis_form', 'distribusi')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->get();

            return view('v_admin_user.apk_atk.daftar_atk', compact('googleChartData', 'usPengadaan', 'usDistribusi'));
        } elseif ($aksi == 'tambah-atk') {
            $kategori = 'atk';
            return view('v_admin_user.apk_atk.tambah_atk', compact('kategori', 'id'));
        } elseif ($aksi == 'proses-tambah-barang') {
            $kategori   = $request->barang;
            foreach ($kategori as $i => $kategoriAtk) {
                $total      = KategoriATK::where('jenis_atk_id', $request->jenis_atk)->count();
                $idKategori = $request->jenis_atk . str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                $kategori   = new KategoriATK();
                $kategori->id_kategori_atk = $idKategori;
                $kategori->jenis_atk_id    = $request->jenis_atk;
                $kategori->kategori_atk    = strtoupper($request->barang[$i]);
                $kategori->save();
            }
            return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success', 'Berhasil menambah barang');
        } elseif ($aksi == 'proses-tambah-detail') {
            $kategori   = $request->barang;
            foreach ($kategori as $i => $kategoriAtk) {
                $total  = ATK::where('kategori_atk_id', $request->kategori_atk)->count();
                $idAtk  = $request->kategori_atk . str_pad($total + 1, 5, 0, STR_PAD_LEFT);
                $atk   = new ATK();
                $atk->id_atk           = $idAtk;
                $atk->kategori_atk_id  = $request->kategori_atk;
                $atk->merk_atk         = strtoupper($request->barang[$i]);
                $atk->total_atk        = 0;
                $atk->satuan           = strtoupper($request->satuan[$i]);
                $atk->save();
            }
            return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success', 'Berhasil menambah barang');
        } elseif ($aksi == 'detail-kategori') {
            $kategoriAtk    = KategoriATK::get();
            $jenisAtk       = JenisATK::get();
            $subkelompokAtk = SubKelompokATK::get();
            $kelompokAtk    = KelompokATK::get();
            return view('v_admin_user.apk_atk.kategori_atk', compact('id', 'kelompokAtk', 'subkelompokAtk', 'jenisAtk', 'kategoriAtk'));
        } elseif ($aksi == 'edit-atk') {
            if ($request->atk == 'merk_atk') {
                ATK::where('id_atk', $request->id_atk)->update([
                    'merk_atk' => strtoupper($request->merk_atk),
                    'satuan'   => $request->satuan
                ]);
                return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success', 'Berhasil mengubah informasi barang');
            } elseif ($request->atk == 'kategori_atk') {
                KategoriATK::where('id_kategori_atk', $request->id_kategori_atk)->update(['kategori_atk' => strtoupper($request->kategori_atk)]);
                return redirect('admin-user/atk/barang/detail-kategori/kategori')->with('success', 'Berhasil mengubah informasi kategori barang');
            } elseif ($request->atk == 'jenis_atk') {
                JenisATK::where('id_jenis_atk', $request->id_jenis_atk)->update(['jenis_atk' => strtoupper($request->jenis_atk)]);
                return redirect('admin-user/atk/barang/detail-kategori/jenis')->with('success', 'Berhasil mengubah informasi jenis barang');
            } elseif ($request->atk == 'subkelompok_atk') {
                SubKelompokATK::where('id_subkelompok_atk', $request->id_subkelompok_atk)->update(['subkelompok_atk' => strtoupper($request->subkelompok_atk)]);
                return redirect('admin-user/atk/barang/detail-kategori/kelompok')->with('success', 'Berhasil mengubah informasi kelompok barang');
            }
        } elseif ($aksi == 'riwayat-semua') {
            $spek = Crypt::decrypt($id);
            $pengadaan = UsulanAtkPengadaan::join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('spesifikasi', $spek)
                ->where('status_proses_id', 5)
                ->orderBy('tanggal_usulan', 'DESC')
                ->get();

            $permintaan = UsulanAtkPermintaan::select(
                'atk_tbl_form_usulan.*',
                'atk_tbl_form_usulan_pengadaan.*',
                'tbl_pegawai.*',
                'tbl_unit_kerja.*',
                'atk_tbl_form_usulan_permintaan.jumlah',
                'atk_tbl_form_usulan_permintaan.jumlah_disetujui'
            )
                ->join('atk_tbl_form_usulan_pengadaan', 'id_form_usulan_pengadaan', 'pengadaan_id')
                ->join('atk_tbl_form_usulan', 'id_form_usulan', 'atk_tbl_form_usulan_permintaan.form_usulan_id')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('spesifikasi', $spek)
                ->where('status_proses_id', 5)
                ->orderBy('tanggal_usulan', 'DESC')
                ->get();

            $atk = UsulanAtkPengadaan::where('spesifikasi', $spek)
                ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
                ->select(
                    'jenis_barang',
                    'nama_barang',
                    'spesifikasi',
                    DB::raw('sum(jumlah_disetujui) as jumlah_disetujui'),
                    DB::raw('sum(jumlah_pemakaian) as jumlah_pemakaian')
                )
                ->where('status_proses_id', 5)
                ->groupBy('jenis_barang', 'nama_barang', 'spesifikasi')
                ->first();
            return view('v_admin_user.apk_atk.riwayat', compact('atk', 'pengadaan', 'permintaan'));
        }
    }

    public function LetterAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'surat-usulan') {

            if (Auth::user()->pegawai->unit_kerja_id == 465930) {
                $ppk = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->where('jabatan_id', '5')->where('unit_kerja_id', 465930)->first();
            } else {
                $ppk = null;
            }

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->get();

            return view('v_admin_user/apk_atk/surat_usulan', compact('ppk', 'usulan'));
        } elseif ($aksi == 'surat-bast') {
            if (Auth::user()->pegawai->unit_kerja_id == 465930) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();
            } else {
                $pimpinan = null;
            }

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/apk_atk/surat_bast', compact('pimpinan', 'bast', 'id'));
        } elseif ($aksi == 'print-surat-usulan') {
            if (Auth::user()->pegawai->unit_kerja_id == 465930) {
                $ppk = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->where('jabatan_id', '5')->where('unit_kerja_id', 465930)->first();
            } else {
                $ppk = null;
            }

            $usulan = UsulanAtk::where('otp_usulan_pengusul', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->get();

            return view('v_admin_user/apk_atk/print_surat_usulan', compact('ppk', 'usulan'));


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
                $pengadaanBaru->jenis_kendaraan_id      = $request->jenis_kendaraan_id;
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

                if ($request->jenis_aadb == 'sewa') {
                    $cekPengadaanSewa = KendaraanSewa::count();
                    $pengadaanSewa  = new KendaraanSewa();
                    $pengadaanSewa->id_kendaraan_sewa = $cekPengadaanSewa + 1;
                    $pengadaanSewa->kendaraan_id = $request->id_kendaraan;
                    $pengadaanSewa->mulai_sewa   = $request->mulai_sewa;
                    $pengadaanSewa->penyedia     = $request->penyedia;
                    $pengadaanSewa->save();
                }

                UsulanAadb::where('id_form_usulan', $id)->update(['status_proses' => 'selesai']);
            } elseif ($cekForm->jenis_form == 2) {
            } elseif ($cekForm->jenis_form == 3) {
            } else {
            }

            return redirect('admin-user/aadb/usulan/bast/' . $id);
        } elseif ($aksi == 'print-surat-bast') {
            if (Auth::user()->pegawai->unit_kerja_id == 465930) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();
            } else {
                $pimpinan = null;
            }

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_admin_user/apk_atk/print_surat_bast', compact('pimpinan', 'bast', 'id'));
        }
    }

    public function SubmissionAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {
            $uker   = UnitKerja::get();
            if ($id == 'validasi') {
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', '1')
                    ->where('is_checked', null)
                    ->get();
            } elseif ($id == 'ditolak') {
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_pengajuan_id', 2)
                    ->get();
            } elseif ($id == 'uker') {
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('jenis_form', $request->jenis_form)
                    ->where('unit_kerja_id', $request->id_unit_kerja)
                    ->get();
            } else {
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', $id)
                    ->where('is_checked', true)
                    ->get();
            }

            return view('v_admin_user.apk_atk.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'daftar') {
            $uker   = UnitKerja::get();

            $dataUsulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC');


            if ($request->hasAny(['unit_kerja_id', 'start_date', 'end_date', 'status_proses_id', 'jenis_form'])) {
                if ($request->unit_kerja_id) {
                    $searchUkt = $dataUsulan->where('unit_kerja_id', $request->unit_kerja_id);
                }
                if ($request->start_date) {
                    $searchUkt = $dataUsulan->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
                }
                if ($request->status_proses_id) {
                    $searchUkt = $dataUsulan->where('status_proses_id', $request->status_proses_id);
                }
                if ($request->jenis_form) {
                    $searchUkt = $dataUsulan->where('jenis_form', $request->jenis_form);
                }

                $usulan = $searchUkt->get();
            } else {
                $usulan = $dataUsulan->get();
            }

            return view('v_admin_user.apk_atk.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'input') {

            $jenisForm  = UsulanAtk::where('id_form_usulan', $id)->pluck('jenis_form');
            $total      = UsulanAtk::select('jenis_form', DB::raw('count(jenis_form) as total_form'))->groupBy('jenis_form')->where('jenis_form', $jenisForm)->first();
            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->first();

            return view('v_admin_user.apk_atk.pgudang_input', compact('total', 'id', 'usulan'));
        } elseif ($aksi == 'proses-input' && $id == 'pengadaan') {
            // Update form usulan
            UsulanAtk::where('id_form_usulan', $request->form_id)->update([
                'no_surat_bast'     => $request->no_surat_bast,
                'tanggal_bast'      => Carbon::now()
            ]);

            // Tambah Lampiran
            $idLampiran = UsulanAtkLampiran::count();
            $lampiran = new UsulanAtkLampiran();
            $lampiran->id_lampiran    = $idLampiran + 1;
            $lampiran->form_usulan_id = $request->form_id;
            $lampiran->nomor_kontrak  = $request->nomor_kontrak;
            $lampiran->nomor_kwitansi = $request->nomor_kwitansi;
            $lampiran->nilai_kwitansi = $request->nilai_kwitansi;

            if ($request->file_kwitansi != null) {
                $filename  = Carbon::now()->format('ddmy') . '_' . $request->file_kwitansi->getClientOriginalName();
                $request->file_kwitansi->move('gambar/kwitansi/atk_pengadaan/', $filename);
                $lampiran->file_kwitansi = $filename;
            } else {
                $lampiran->file_kwitansi = null;
            }
            $lampiran->save();

            $atk = $request->atk_id;
            foreach ($atk as $i => $atk_id) {
                // Riwayat stok barang
                $totalStok            = StokAtk::count();
                $stok                 = new StokAtk();
                $stok->id_stok        = $totalStok + 1;
                $stok->atk_id         = $atk_id;
                $stok->form_usulan_id = Auth::user()->sess_form_id;
                $stok->stok_atk       = $request->jumlah[$i];
                $stok->satuan         = $request->satuan[$i];
                $stok->save();
                // Update Harga Barang
                UsulanAtkDetail::where('id_form_usulan_detail', $request->form_detail_id[$i])
                    ->update([
                        'harga' => $request->harga[$i]
                    ]);
                // Update Stok Barang
                $stokAtk = Atk::where('id_atk', $atk_id)->first();
                Atk::where('id_atk', $atk_id)->update([
                    'total_atk' => $stokAtk->total_atk + $request->jumlah[$i]
                ]);
            }
            return redirect('admin-user/verif/usulan-atk/' . $request->form_id);
            // return redirect('admin-user/atk/surat/surat-bast/'. $id)->with('success', 'Pembelian barang telah selesai dilakukan');

        } elseif ($aksi == 'proses-input' && $id == 'distribusi') {
            return redirect('admin-user/verif/usulan-atk/' . $request->form_id);
        } elseif ($aksi == 'penyerahan') {
            if ($request->tanggal_bast) {
                // dd($request->all());
                // Insert BAST
                $id_bast = (int) Carbon::now()->format('dhimy');
                $bast = new BastAtk();
                $bast->id_bast = $id_bast;
                $bast->usulan_id = $id;
                $bast->tanggal_bast = $request->tanggal_bast;
                $bast->nomor_bast   = $request->no_surat_bast;
                $bast->save();

                // Update tanggal penyerahan ATK
                $permintaan = $request->id_permintaan;
                foreach ($permintaan as $i => $id_permintaan) {
                    if ($request->jumlah_penyerahan[$i] != 0) {
                        // Select data permintaan atk
                        $sudah_diserahkan = UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->first();
                        // Update jumlah penyerahan
                        UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->update([
                            'bast_id'            => $id_bast,
                            'jumlah_penyerahan'  => (int) $sudah_diserahkan->jumlah_penyerahan + $request->jumlah_penyerahan[$i],
                        ]);
                        $permintaan = UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->first();
                        // Jika permintaan = diserahkan atau seluruh barang sudah diserahkan maka status penyerahan true
                        if ($permintaan->jumlah_disetujui == $permintaan->jumlah_penyerahan) {
                            $status_penyerahan = 'true';
                        } else {
                            $status_penyerahan = 'false';
                        }
                        // Update status penyerahan
                        UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->update([
                            'status_penyerahan' => $status_penyerahan
                        ]);

                        // Update detail bast
                        $detailBast = new BastAtkDetail();
                        $detailBast->bast_id = $id_bast;
                        $detailBast->permintaan_id = $id_permintaan;
                        $detailBast->jumlah_bast_detail = $request->jumlah_penyerahan[$i];
                        $detailBast->save();
                    }
                }

                return redirect('admin-user/surat/detail-bast-atk/' . $id_bast)->with('success', 'Berhasil Menyerahkan ATK');
            } else {
                $totalUsulan = BastAtk::count();
                $idBast      = (int) str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->where('id_form_usulan', $id)
                    ->first();

                return view('v_admin_user.apk_atk.penyerahan', compact('idBast', 'usulan'));
            }
        } elseif ($aksi == 'edit') {
            $totalUsulan = BastAtk::count();
            $idBast      = (int) str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $atk    = Atk::orderBy('deskripsi_barang')->where('status_id', 1)->get();
            $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('id_form_usulan', $id)
                ->first();
            return view('v_admin_user.apk_atk.edit', compact('idBast','usulan','atk'));
        } elseif ($aksi == 'proses-edit') {
	    // Insert BAST
            $id_bast = (int) Carbon::now()->format('dmhs');
            $bast = new BastAtk();
            $bast->id_bast        = $id_bast;
            $bast->usulan_id      = $id;
            $bast->tanggal_bast   = $request->tanggal_bast;
            $bast->nomor_bast     = $request->no_surat_bast;
            $bast->otp_bast_ppk   = rand(111111,999999);
            $bast->otp_bast_kabag = rand(111111,999999);
            $bast->save();

            // Update tanggal penyerahan ATK
            $permintaan = $request->id_permintaan;
            foreach ($permintaan as $i => $id_permintaan) {
                if ($request->jumlah_penyerahan[$i] != 0) {
                    // Select data permintaan atk
                    $sudah_diserahkan = UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->first();
                    // Update jumlah penyerahan
                    UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->update([
                        'bast_id'            => $id_bast,
                        'jumlah_penyerahan'  => (int) $sudah_diserahkan->jumlah_penyerahan + $request->jumlah_penyerahan[$i],
                    ]);
                    $permintaan = UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->first();
                    // Jika permintaan = diserahkan atau seluruh barang sudah diserahkan maka status penyerahan true
                    if ($permintaan->jumlah_disetujui == $permintaan->jumlah_penyerahan) {
                        $status_penyerahan = 'true';
                    } else {
                        $status_penyerahan = 'false';
                    }
                    // Update status penyerahan
                    UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->update([
                        'atk_id' => $request->id_atk[$i],
                        'status_penyerahan' => $status_penyerahan,
                    ]);

                    // Update detail bast
                    $detailBast = new BastAtkDetail();
                    $detailBast->bast_id = $id_bast;
                    $detailBast->permintaan_id = $id_permintaan;
                    $detailBast->jumlah_bast_detail = $request->jumlah_penyerahan[$i];
                    $detailBast->save();

                    if ($request->modul == 'permintaan') {
                    	// Insert Riwayat Transaksi
                    	$riwayat = new RiwayatAtk();
                    	$riwayat->usulan_id       = $id_bast;
                    	$riwayat->atk_id          = $request->id_atk[$i];
                    	$riwayat->jumlah          = (int) $request->jumlah_penyerahan[$i];
                    	$riwayat->status_riwayat  = 'keluar';
                    	$riwayat->tanggal_riwayat = Carbon::now();
                    	$riwayat->save();
		            }
                }
            }

            return redirect('admin-user/surat/detail-bast-atk/' . $id_bast)->with('success', 'Berhasil Menyerahkan ATK');
        } elseif ($aksi == 'pembatalan') {
            $totalUsulan = BastAtk::count();
            $permintaan  = UsulanAtkPermintaan::where('id_permintaan', $id)->first();
            $usulan      = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('id_form_usulan', $permintaan->form_usulan_id)
                ->first();


            return view('v_admin_user.apk_atk.pembatalan', compact('usulan', 'permintaan'));
        } elseif ($aksi == 'batal-permintaan') {
            $permintaan = UsulanAtkPermintaan::where('id_permintaan', $id)
                ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
                ->first();
            // Hapus Permintaan
            UsulanAtkPermintaan::where('id_permintaan', $id)->update([
                'status'            => $permintaan->jumlah_penyerahan == null || $permintaan->jumlah_penyerahan == 0 ? 'ditolak' : 'diterima',
                'jumlah_disetujui'  => ($permintaan->jumlah_disetujui - $request->jumlah_batal),
                'keterangan'        => $request->keterangan
            ]);

            if ($permintaan->jenis_form == 'distribusi') {
                // Update Jumlah Permintaan
                UsulanAtkPengadaan::where('id_form_usulan_pengadaan', $permintaan->pengadaan_id)->update([
                    'jumlah_pemakaian'  => ($permintaan->jumlah_pemakaian - $request->jumlah_batal)
                ]);
            }

            // Update Status Permintaan
            $updPermintaan  = UsulanAtkPermintaan::where('id_permintaan', $id)->first();
            if ($updPermintaan->jumlah_disetujui == $updPermintaan->jumlah_penyerahan) {
                $status_penyerahan = 'true';
            } else {
                $status_penyerahan = 'false';
            }
            // Update status penyerahan
            UsulanAtkPermintaan::where('id_permintaan', $id)->update([
                'status_penyerahan' => $status_penyerahan
            ]);

            return redirect('admin-user/atk/usulan/edit/' . $permintaan->form_usulan_id)->with('success', 'Berhasil Membatalkan Permintaan');
        } elseif ($aksi == 'laporan') {
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

            $usulanChart = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"))
                ->groupBy('month')
                ->get();

            $chartData = UsulanAtk::select(DB::raw("(DATE_FORMAT(tanggal_usulan, '%Y-%m')) as month"), 'jenis_form')
                ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->get();

            foreach ($usulanChart as $key => $value) {
                $result[] = ['Bulan', 'Total Usulan Pengadaan', 'Total Usulan Distribusi'];
                $result[++$key] = [
                    Carbon::parse($value->month)->isoFormat('MMMM Y'),
                    $chartData->where('month', $value->month)->where('jenis_form', 'pengadaan')->count(),
                    $chartData->where('month', $value->month)->where('jenis_form', 'distribusi')->count(),
                ];
            }

            $usulanChartAtk = json_encode($result);

            return view('v_admin_user.apk_atk.laporan', compact('usulanUker', 'usulanTotal', 'usulanChartAtk', 'dataChartAtk'));
        } elseif ($aksi == 'validasi') {
            if ($request->all()) {
                UsulanAtk::where('id_form_usulan', $id)->update([
                    'total_pengajuan' => count(array_filter($request->status_pengajuan)),
                    'is_checked'      => true
                ]);

                foreach ($request->id_permintaan as $i => $id_permintaan) {
                    UsulanAtkPermintaan::where('id_permintaan', $id_permintaan)->update([
                        'status'            => $request->status_pengajuan[$i] == 'true' ? 'diterima' : 'ditolak',
                        'jumlah_disetujui'  => $request->status_pengajuan[$i] == 'true' ? $request->permintaanAtk[$i] : null,
                        'keterangan'        => $request->keterangan[$i]
                    ]);

                    if ($request->status_pengajuan[$i] == null && $request->modul == 'distribusi')
                    {
                        $pemakaian = UsulanAtkPengadaan::where('id_form_usulan_pengadaan', $request->id_pengadaan[$i])->first();
                        UsulanAtkPengadaan::where('id_form_usulan_pengadaan', $request->id_pengadaan[$i])->update([
                            'jumlah_pemakaian' => $pemakaian->jumlah_pemakaian - $request->permintaanAtk[$i]
                        ]);
                    }
                }

                return redirect('admin-user/surat/usulan-atk/'. $id)->with('success', 'Berhasil Melakukan Validasi');
            } else {
                $totalUsulan = BastAtk::count();
                $idBast      = (int) str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->where('id_form_usulan', $id)
                    ->first();
                return view('v_admin_user.apk_atk.validasi', compact('idBast', 'usulan'));
            }
        }
    }

    public function Select2Atk(Request $request, $aksi, $id)
    {
        $search = $request->search;
        if ($aksi == '1') {
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
                $atk  = JenisAtk::select('id_jenis_atk as id', 'subkelompok_atk_id', 'jenis_atk as nama')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('subkelompok_atk_id', $id)
                    ->get();
            } else {
                $atk  = JenisAtk::select('id_jenis_atk', 'subkelompok_atk_id', 'jenis_atk')
                    ->orderby('id_jenis_atk', 'asc')
                    ->where('subkelompok_atk_id', $id)
                    ->where('id_jenis_atk', 'like', '%' . $search . '%')
                    ->orWhere('jenis_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 3) {
            if ($search == '') {
                $atk  = KategoriAtk::select('id_kategori_atk as id', 'jenis_atk_id', 'kategori_atk as nama')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('jenis_atk_id', $id)
                    ->get();
            } else {
                $atk  = KategoriAtk::select('id_kategori_atk', 'jenis_atk_id', 'kategori_atk')
                    ->orderby('id_kategori_atk', 'asc')
                    ->where('jenis_atk_id', $id)
                    ->where('id_kategori_atk', 'like', '%' . $search . '%')
                    ->orWhere('kategori_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 4) {
            $totalKategori = KategoriATK::where('id_kategori_atk', $id)->count();
            $idAtk         = array($id . str_pad($totalKategori + 1, 5, 0, STR_PAD_LEFT));
        }

        $response = array();
        foreach ($atk as $data) {
            $response[] = array(
                "id"     =>  $data->id,
                "text"   =>  $data->id . ' - ' . $data->nama
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

    public function WarehouseAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'dashboard') {
            $result = null;
            $dataChartAtk = $this->ChartDataAtk();
            $riwayatUker  = RiwayatAtk::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
                ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
                ->where('unit_utama_id', '02401')
                ->get();

            $riwayatTotal = TransaksiAtk::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')->get();

            $chartDate = RiwayatAtk::select(DB::raw("(DATE_FORMAT(tanggal_riwayat, '%Y-%m')) as month"))
                ->groupBy('month')
                ->get();

            $chartData = RiwayatAtk::select(DB::raw("(DATE_FORMAT(tanggal_riwayat, '%Y-%m')) as month"), 'status_riwayat')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->get();

            foreach ($chartDate as $key => $value) {
                $result[] = ['Bulan', 'Total Barang Masuk', 'Total Barang Keluar'];
                $result[++$key] = [
                    Carbon::parse($value->month)->isoFormat('MMMM Y'),
                    $chartData->where('month', $value->month)->where('status_riwayat', 'masuk')->count(),
                    $chartData->where('month', $value->month)->where('status_riwayat', 'keluar')->count(),
                ];
            }
            $usulanChartAtk = json_encode($result);

            return view('v_admin_user.apk_atk.gudang', compact('riwayatUker', 'riwayatTotal', 'usulanChartAtk'));
        } elseif ($aksi == 'stok') {
            $barang = [];
            $list = Atk::get();
            $atk  = RiwayatAtk::select('atk_id', 'kode_ref', 'kategori_id', 'deskripsi_barang', 'satuan_barang')
                ->join('atk_tbl_master', 'id_atk', 'atk_id')
                ->groupBy('atk_id', 'kode_ref', 'kategori_id', 'deskripsi_barang', 'satuan_barang')
                ->get();

            foreach ($atk as $i => $dataAtk) {
                $barang_masuk   = RiwayatAtk::select(DB::raw('SUM(jumlah) as total_barang_masuk'))
                    ->where('status_riwayat', 'masuk')
                    ->where('atk_id', $dataAtk->atk_id)
                    ->first();
                $barang_keluar  = RiwayatAtk::select(DB::raw('SUM(jumlah) as total_barang_keluar'))
                    ->where('status_riwayat', 'keluar')
                    ->where('atk_id', $dataAtk->atk_id)
                    ->first();

                $data['id_atk']        = $dataAtk->atk_id;
                $data['tanggal']       = $dataAtk->atk_id;
                $data['kategori_id']   = $dataAtk->kategori_id;
                $data['kode_ref']      = $dataAtk->kode_ref;
                $data['deskripsi']     = $dataAtk->deskripsi_barang;
                $data['satuan']        = $dataAtk->satuan_barang;
                $data['keterangan']    = $dataAtk->keterangan;
                $data['barang_masuk']  = $barang_masuk->total_barang_masuk;
                $data['barang_keluar'] = $barang_keluar->total_barang_keluar;
                $data['jumlah']        = $barang_masuk->total_barang_masuk - $barang_keluar->total_barang_keluar;
                $barang[] = $data;
                unset($data);
            }

            return view('v_admin_user.apk_atk.stok_gudang', compact('list', 'barang'));
        } elseif ($aksi == 'referensi') {
            $kategori  = KategoriAtk::get();
            $referensi = Atk::leftjoin('atk_tbl_kategori', 'id_kategori_atk', 'kategori_id')->get();
            return view('v_admin_user.apk_atk.daftar_referensi', compact('kategori', 'referensi'));
        } elseif ($aksi == 'daftar-transaksi') {
            $transaksi = TransaksiAtk::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->where('kategori_transaksi', $id)
                ->orderBy('tanggal_transaksi','DESC')
                ->get();
            return view('v_admin_user.apk_atk.daftar_transaksi', compact('transaksi', 'id'));
        } elseif ($aksi == 'tambah') {
            if ($id == 'Pembelian') {
                $uker   = '';
                $barang = Atk::get();
            } else {
                $uker   = UnitKerja::get();

                $atk = RiwayatAtk::select('atk_id', 'kode_ref', 'kategori_id', 'deskripsi_barang', 'satuan_barang')
                    ->join('atk_tbl_master', 'id_atk', 'atk_id')
                    ->groupBy('atk_id', 'kode_ref', 'kategori_id', 'deskripsi_barang', 'satuan_barang')
                    ->get();

                foreach ($atk as $i => $dataAtk) {
                    $barang_masuk   = RiwayatAtk::select(DB::raw('SUM(jumlah) as total_barang_masuk'))
                        ->where('status_riwayat', 'masuk')
                        ->where('atk_id', $dataAtk->atk_id)
                        ->first();
                    $barang_keluar  = RiwayatAtk::select(DB::raw('SUM(jumlah) as total_barang_keluar'))
                        ->where('status_riwayat', 'keluar')
                        ->where('atk_id', $dataAtk->atk_id)
                        ->first();
                    $data['id_atk']      = $dataAtk->atk_id;
                    $data['kode_ref']    = $dataAtk->kode_ref;
                    $data['deskripsi']   = $dataAtk->deskripsi_barang;
                    $data['satuan']      = $dataAtk->satuan_barang;
                    $data['keterangan']  = $dataAtk->keterangan;
                    $data['jumlah']      = $barang_masuk->total_barang_masuk - $barang_keluar->total_barang_keluar;
                    $barang[] = $data;
                    unset($data);
                }
            }

            return view('v_admin_user.apk_atk.tambah_transaksi', compact('uker', 'barang', 'id'));
        } elseif ($aksi == 'proses') {
            if ($id == 'pembelian') {
                // Total barang
                $total = collect($request->volume_transaksi)->filter(function ($value) {
                    return $value != 0;
                })->count();
                // Insert Transaksi Pembelian
                $idTransaksi = Carbon::now()->format('dhis');
                $pembelian = new TransaksiAtk();
                $pembelian->id_transaksi         = $idTransaksi;
                $pembelian->unit_kerja_id        = Auth::user()->pegawai->unit_kerja_id;
                $pembelian->tanggal_transaksi    = $request->tanggal_transaksi;
                $pembelian->nomor_kwitansi       = $request->nomor_kwitansi;
                $pembelian->nama_vendor          = $request->nama_vendor;
                $pembelian->alamat_vendor        = $request->alamat_vendor;
                $pembelian->keterangan_transaksi = $request->keterangan_transaksi;
                $pembelian->file_kwitansi        = $request->file_kwitansi;
                $pembelian->total_barang         = $total;
                $pembelian->total_biaya          = (int) $request->total_biaya;
                $pembelian->save();

                $atk = $request->atk_id;
                foreach ($atk as $i => $atkId) {
                    // Insert Detail Transaksi
                    if ($request->volume_transaksi[$i] != 0) {
                        $detail = new TransaksiAtkDetail();
                        $detail->transaksi_id     = $idTransaksi;
                        $detail->atk_id           = $atkId;
                        $detail->volume_transaksi = $request->volume_transaksi[$i];
                        $detail->harga_satuan     = $request->harga_satuan[$i];
                        $detail->jumlah_biaya     = $request->jumlah_biaya[$i];
                        $detail->save();
                        // Insert Riwayat Transaksi
                        $riwayat = new RiwayatAtk();
                        $riwayat->usulan_id       = $idTransaksi;
                        $riwayat->atk_id          = $atkId;
                        $riwayat->jumlah          = $request->volume_transaksi[$i];
                        $riwayat->status_riwayat  = 'masuk';
                        $riwayat->tanggal_riwayat = Carbon::now();
                        $riwayat->save();
                    }
                }
            } else {
                $idTransaksi = Carbon::now()->format('dhis');
                // Insert Transaksi Pembelian
                $permintaan = new TransaksiAtk();
                $permintaan->id_transaksi         = $idTransaksi;
                $permintaan->kategori_transaksi   = 'Permintaan';
                $permintaan->unit_kerja_id        = $request->unit_kerja_id;
                $permintaan->tanggal_transaksi    = $request->tanggal_transaksi;
                $permintaan->keterangan_transaksi = $request->keterangan_transaksi;
                $permintaan->total_barang         = count($request->atk_id);
                $permintaan->save();

                $atk = $request->atk_id;
                foreach ($atk as $i => $atkId) {
                    // Insert Detail Transaksi
                    if ($request->volume_transaksi[$i] != 0) {
                        $detail = new TransaksiAtkDetail();
                        $detail->transaksi_id     = $idTransaksi;
                        $detail->atk_id           = $atkId;
                        $detail->volume_transaksi = $request->volume_transaksi[$i];
                        $detail->harga_satuan     = 0;
                        $detail->jumlah_biaya     = 0;
                        $detail->save();
                        // Insert Riwayat Transaksi
                        $riwayat = new RiwayatAtk();
                        $riwayat->usulan_id       = $idTransaksi;
                        $riwayat->atk_id          = $atkId;
                        $riwayat->jumlah          = $request->volume_transaksi[$i];
                        $riwayat->status_riwayat  = 'keluar';
                        $riwayat->tanggal_riwayat = Carbon::now();
                        $riwayat->save();
                    }
                }
            }

            return redirect('admin-user/atk/gudang/detail-transaksi/' . $idTransaksi)->with('success', 'Berhasil Mencatat Transaksi');
        } elseif ($aksi == 'detail-transaksi') {
            $transaksi = TransaksiAtk::where('id_transaksi', $id)
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->first();
            return view('v_admin_user.apk_atk.detail_transaksi', compact('transaksi'));
        } elseif ($aksi == 'update-atk') {
            Atk::where('id_atk', $id)->update([
                'kode_ref'          => $request->kode_ref,
                'kategori_id'       => $request->kategori_id,
                'deskripsi_barang'  => $request->deskripsi_barang,
                'satuan_barang'     => $request->satuan_barang,
                'keterangan_barang' => $request->keterangan_barang
            ]);
            return redirect('admin-user/atk/gudang/referensi/daftar')->with('success', 'Berhasil Memperbarui Informasi');
        } elseif ($aksi == 'update-kategori') {
            KategoriAtk::where('id_kategori_atk', $id)->update([
                'deskripsi_kategori'  => $request->deskripsi_kategori,
                'satuan_kategori'     => $request->satuan_kategori,
                'keterangan_kategori' => $request->keterangan_kategori
            ]);
            return redirect('admin-user/atk/gudang/referensi/daftar')->with('success', 'Berhasil Memperbarui Informasi ' . $request->deskripsi_kategori);
        } elseif ($aksi == 'insert' && $id == 'atk') {
            $atk = new Atk();
            $atk->kode_ref          = $request->kode_ref;
            $atk->kategori_id       = $request->kategori_id;
            $atk->deskripsi_barang  = $request->deskripsi_barang;
            $atk->satuan_barang     = $request->satuan_barang;
            $atk->keterangan_barang = $request->keterangan_barang;
            $atk->save();
            return redirect('admin-user/atk/gudang/referensi/daftar')->with('success', 'Berhasil Menambah Referensi ATK Baru ' . $request->deskripsi_barang);
        } elseif ($aksi == 'insert' && $id == 'kategori') {
            $kategori = KategoriAtk::where('id_kategori_atk', $request->id_kategori_atk)->first();
            if ($kategori) {
                return redirect('admin-user/atk/gudang/referensi/daftar')->with('failed', 'Kategori ATK Sudah Terdaftar');
            }
            $atk = new KategoriAtk();
            $atk->id_kategori_atk       = $request->id_kategori_atk;
            $atk->deskripsi_kategori    = $request->deskripsi_kategori;
            $atk->satuan_kategori       = $request->satuan_kategori;
            $atk->keterangan_kategori   = $request->keterangan_kategori;
            $atk->save();
            return redirect('admin-user/atk/gudang/referensi/daftar')->with('success', 'Berhasil Menambah Kategori ATK Baru');
        }
    }

    // ====================================================
    //                        AADB
    // ====================================================

    public function Aadb(Request $request)
    {
        $usulanUker  = UsulanAadb::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->get();

        $usulanTotal = UsulanAadb::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        return view('v_admin_user.apk_aadb.index', compact('usulanUker', 'usulanTotal'));
    }

    public function SubmissionAadb(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $uker   = UnitKerja::get();

            $dataUsulan = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC');


            if ($request->hasAny(['unit_kerja_id', 'start_date', 'end_date', 'status_proses_id', 'jenis_form'])) {
                if ($request->unit_kerja_id) {
                    $searchUkt = $dataUsulan->where('unit_kerja_id', $request->unit_kerja_id);
                }
                if ($request->start_date) {
                    $searchUkt = $dataUsulan->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
                }
                if ($request->status_proses_id) {
                    $searchUkt = $dataUsulan->where('status_proses_id', $request->status_proses_id);
                }
                if ($request->jenis_form) {
                    $searchUkt = $dataUsulan->where('jenis_form', $request->jenis_form);
                }

                $usulan = $searchUkt->get();
            } else {
                $usulan = $dataUsulan->get();
            }

            return view('v_admin_user.apk_aadb.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'status') {
            $uker   = UnitKerja::get();

            if ($id == 'ditolak') {
                $usulan  = UsulanAadb::with('usulanKendaraan')
                    ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                    ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_pengajuan_id', 2)
                    ->get();
            } else {
                $usulan  = UsulanAadb::with('usulanKendaraan')
                    ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                    ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', $id)
                    ->get();
            }

            return view('v_admin_user.apk_aadb.daftar_pengajuan', compact('uker', 'usulan'));
        }
    }

    public function Vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $char = '"';
            $kendaraan = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->join('aadb_tbl_kondisi_kendaraan', 'id_kondisi_kendaraan', 'kondisi_kendaraan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('aadb_tbl_status_kendaraan', 'id_status_kendaraan', 'status_kendaraan_id')
                ->select(
                    'id_kendaraan',
                    'jenis_aadb',
                    'unit_kerja',
                    'no_plat_kendaraan',
                    'merk_tipe_kendaraan',
                    'kualifikasi',
                    'mb_stnk_plat_kendaraan',
                    'tahun_kendaraan',
                    'tanggal_perolehan',
                    'no_bpkb',
                    'no_rangka',
                    'no_mesin',
                    'nilai_perolehan',
                    'kondisi_kendaraan',
                    'pengguna',
                    'status_kendaraan'
                )
                ->orderBy('jenis_aadb', 'ASC')
                ->get();

            return view('v_admin_user.apk_aadb.daftar_kendaraan', compact('kendaraan'));
        } elseif ($aksi == 'detail') {
            $unitKerja = UnitKerja::get();
            $jenis     = JenisKendaraan::get();
            $kondisi   = KondisiKendaraan::get();
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->first();

            return view('v_admin_user.apk_aadb.detail_kendaraan', compact('unitKerja', 'jenis', 'kondisi', 'kendaraan'));
        } elseif ($aksi == 'upload') {
            Excel::import(new KendaraanImport(), $request->upload);
            return redirect('admin-user/aadb/kendaraan/')->with('success', 'Berhasil Mengupload Data Kendaraan');
        }
    }

    // ====================================================
    //                        OLDAT
    // ====================================================


    public function Oldat(Request $request)
    {
        $usulanUker  = FormUsulan::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->get();

        $usulanTotal = FormUsulan::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        return view('v_admin_user.apk_oldat.index', compact('usulanUker', 'usulanTotal'));
    }

    public function SubmissionOldat(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {
            $uker   = UnitKerja::get();
            if ($id == 'ditolak') {
                $usulan = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_pengajuan_id', 2)
                    ->get();
            } elseif ($id == 'uker') {
                $usulan = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('jenis_form', $request->jenis_form)
                    ->where('unit_kerja_id', $request->id_unit_kerja)
                    ->get();
            } else {
                $usulan = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', $id)
                    ->get();
            }
            return view('v_admin_user.apk_oldat.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'daftar') {
            $uker   = UnitKerja::get();

            $dataUsulan = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->select(
                    'id_form_usulan',
                    'tanggal_usulan',
                    'no_surat_usulan',
                    'unit_kerja',
                    'jenis_form',
                    'nama_pegawai',
                    'keterangan_pegawai',
                    'rencana_pengguna',
                    'status_proses_id',
                    'status_pengajuan_id'
                )
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC');

            if ($request->hasAny(['unit_kerja_id', 'start_date', 'end_date', 'status_proses_id', 'jenis_form'])) {
                if ($request->unit_kerja_id) {
                    $searchUkt = $dataUsulan->where('unit_kerja_id', $request->unit_kerja_id);
                }
                if ($request->start_date) {
                    $searchUkt = $dataUsulan->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
                }
                if ($request->status_proses_id) {
                    $searchUkt = $dataUsulan->where('status_proses_id', $request->status_proses_id);
                }
                if ($request->jenis_form) {
                    $searchUkt = $dataUsulan->where('jenis_form', $request->jenis_form);
                }

                if ($request->unit_kerja_id == null && $request->start_date == null  && $request->status_proses_id == null  && $request->jenis_form == null) {
                    $usulan = $dataUsulan->get();
                } else {
                    $usulan = $searchUkt->get();
                }
            } else {
                $usulan = $dataUsulan->get();
            }
            return view('v_admin_user.apk_oldat.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'laporan') {
            return back();
        }
    }

    public function showItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $char = '"';
            $barang = Barang::select(
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
                'unit_kerja',
                \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang")
            )
                ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                ->orderBy('tahun_perolehan', 'DESC')
                ->get();

            $result = json_decode($barang);
            return view('v_admin_user.apk_oldat.daftar_barang', compact('barang'));
        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai', 'ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', 'like', $id)
                ->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang', 'id_barang', 'barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->join('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where('barang_id', $id)
                ->get();

            return view('v_admin_user.apk_oldat.detail_barang', compact('kategoriBarang', 'kondisiBarang', 'pegawai', 'barang', 'riwayat'));
        } elseif ($aksi == 'upload') {
            Excel::import(new BarangImport(), $request->upload);
            return redirect('admin-user/oldat/barang/data/semua')->with('success', 'Berhasil Mengupload Data Barang');
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_barang'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('admin-user/oldat/barang/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            } else {
                if ($request->foto_barang == null) {
                    $fotoBarang = $request->foto_lama;
                } else {
                    $dataBarang = Barang::where('id_barang', $id)->first();

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

                $barang = Barang::where('id_barang', $id)->update([
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

            return redirect('admin-user/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $request->id_riwayat_barang)->update([
                'tanggal_pengguna'     => $request->tanggal_pengguna,
                'keperluan_penggunaan' => $request->keperluan_penggunaan
            ]);

            return redirect('admin-user/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } elseif ($aksi == 'tambah') {
            return back();
        }
    }

    public function showCategoryItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $kategoriBarang = KategoriBarang::get();
            return view('v_admin_user.apk_oldat.daftar_kategori_barang', compact('kategoriBarang'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id)->update([
                'kategori_barang' => strtolower($request->kategori_barang)
            ]);
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Mengubah Kategori Barang');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    /*===============================================================
                                GEDUNG DAN BANGUNAN
    ===============================================================*/

    public function Gdn(Request $request)
    {
        $usulanUker  = UsulanGdn::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->get();

        $usulanTotal = UsulanGdn::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        return view('v_admin_user.apk_gdn.index', compact('usulanUker', 'usulanTotal'));
    }

    public function SubmissionGdn(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {
            $uker   = UnitKerja::get();
            if ($id == 'ditolak') {
                $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_pengajuan_id', 2)
                    ->get();
            } else {
                $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', $id)
                    ->orWhere('unit_kerja_id', $id)
                    ->get();
            }


            return view('v_admin_user.apk_gdn.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'daftar') {
            $uker   = UnitKerja::get();

            $dataUsulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->select(
                    'id_form_usulan',
                    'tanggal_usulan',
                    'no_surat_usulan',
                    'unit_kerja',
                    'jenis_form',
                    'nama_pegawai',
                    'keterangan_pegawai',
                    'status_proses_id',
                    'status_pengajuan_id'
                )
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC');

            if ($request->hasAny(['unit_kerja_id', 'start_date', 'end_date', 'status_proses_id'])) {
                if ($request->unit_kerja_id) {
                    $searchUkt = $dataUsulan->where('unit_kerja_id', $request->unit_kerja_id);
                }
                if ($request->start_date) {
                    $searchUkt = $dataUsulan->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
                }
                if ($request->status_proses_id) {
                    $searchUkt = $dataUsulan->where('status_proses_id', $request->status_proses_id);
                }

                $usulan = $searchUkt->get();
            } else {
                $usulan = $dataUsulan->get();
            }

            return view('v_admin_user.apk_gdn.daftar_pengajuan', compact('uker', 'usulan'));
        }
    }

    /*===============================================================
                                KERUMAHTANGGAAN
    ===============================================================*/

    public function Ukt(Request $request)
    {
        $usulanUker  = UsulanUkt::select('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->rightjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->groupBy('id_unit_kerja', 'unit_utama_id', 'unit_kerja')
            ->get();

        $usulanTotal = UsulanUkt::leftjoin('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        return view('v_admin_user.apk_ukt.index', compact('usulanUker', 'usulanTotal'));
    }

    public function SubmissionUkt(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {
            $uker   = UnitKerja::get();
            if ($id == 'ditolak') {
                $usulan = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_pengajuan_id', 2)
                    ->get();
            } else {
                $usulan = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                    ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                    ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                    ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                    ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                    ->orderBy('status_pengajuan_id', 'ASC')
                    ->orderBy('status_proses_id', 'ASC')
                    ->orderBy('tanggal_usulan', 'DESC')
                    ->where('status_proses_id', $id)
                    ->orWhere('unit_kerja_id', $id)
                    ->get();
            }


            return view('v_admin_user.apk_ukt.daftar_pengajuan', compact('uker', 'usulan'));
        } elseif ($aksi == 'daftar') {
            $uker   = UnitKerja::get();

            $dataUsulan = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->leftjoin('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->select(
                    'id_form_usulan',
                    'tanggal_usulan',
                    'no_surat_usulan',
                    'unit_kerja',
                    'jenis_form',
                    'nama_pegawai',
                    'keterangan_pegawai',
                    'status_proses_id',
                    'status_pengajuan_id'
                )
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->orderBy('tanggal_usulan', 'DESC');

            if ($request->hasAny(['unit_kerja_id', 'start_date', 'end_date', 'status_proses_id'])) {
                if ($request->unit_kerja_id) {
                    $searchUkt = $dataUsulan->where('unit_kerja_id', $request->unit_kerja_id);
                }
                if ($request->start_date) {
                    $searchUkt = $dataUsulan->whereBetween('tanggal_usulan', [$request->start_date, $request->end_date]);
                }
                if ($request->status_proses_id) {
                    $searchUkt = $dataUsulan->where('status_proses_id', $request->status_proses_id);
                }

                $usulan = $searchUkt->get();
            } else {
                $usulan = $dataUsulan->get();
            }

            return view('v_admin_user.apk_ukt.daftar_pengajuan', compact('uker', 'usulan'));
        }
    }

    /*===============================================================
                                SELECT 2
    ===============================================================*/

    public function Select2(Request $request, $aksi)
    {
        if ($aksi == 'pegawai') {
            $search = $request->search;

            if ($search == '') {
                $pegawai  = Pegawai::select('id_pegawai', 'nama_pegawai')
                    ->orderby('nama_pegawai', 'asc')
                    ->get();
            } else {
                $pegawai  = Pegawai::select('id_pegawai', 'nama_pegawai')
                    ->orderby('nama_pegawai', 'asc')
                    ->where('nama_pegawai', 'like', '%' . $search . '%')
                    ->get();
            }

            $response = array();
            foreach ($pegawai as $data) {
                $response[] = array(
                    "id"    =>  $data->id_pegawai,
                    "text"  =>  $data->nama_pegawai
                );
            }

            return response()->json($response);
        }
    }

    /*===============================================================
                                JSON
    ===============================================================*/

    public function JsonJabatan(Request $request)
    {
        $result   = Pegawai::where('id_pegawai', $request->penghuni)->pluck('keterangan_pegawai', 'id_pegawai');
        return response()->json($result);
    }
}
