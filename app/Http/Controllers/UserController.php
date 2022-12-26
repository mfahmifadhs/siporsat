<?php

namespace App\Http\Controllers;

use App\Imports\Atk\ImportAlkom;
use App\Imports\Atk\ImportAtk;
use App\Models\AADB\JadwalServis;
use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
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
use App\Models\ATK\KelompokAtk;
use App\Models\ATK\StokAtk;
use App\Models\ATK\SubKelompokAtk;
use App\Models\ATK\UsulanAtk;
use App\Models\ATK\UsulanAtkDetail;
use App\Models\ATK\UsulanAtkPengadaan;
use App\Models\gdn\BidangKerusakan;
use App\Models\gdn\UsulanGdn;
use App\Models\gdn\UsulanGdnDetail;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\FormUsulan;
use App\Models\OLDAT\FormUsulanPengadaan;
use App\Models\OLDAT\FormUsulanPerbaikan;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use App\Models\OLDAT\RiwayatBarang;
use App\Models\Pegawai;
use App\Models\RDN\KondisiRumah;
use App\Models\RDN\PenghuniRumah;
use App\Models\RDN\RumahDinas;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google2FA;
use DB;
use Validator;
use Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function Index()
    {
        $usulanOldat  = FormUsulan::get();
        $usulanAadb   = UsulanAadb::get();
        $usulanAtk    = UsulanAtk::get();

        return view('v_user.index', compact('usulanOldat', 'usulanAadb', 'usulanAtk'));
    }

    public function Profile(Request $request, $aksi, $id)
    {
        $user = User::where('id', Auth::user()->id)
            ->join('tbl_level', 'id_level', 'level_id')
            ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
            ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
            ->first();

        if ($aksi == 'user') {
            $google2fa  = app('pragmarx.google2fa');
            $secretkey  = $google2fa->generateSecretKey();
            $QR_Image   = $google2fa->getQRCodeInline(
                $registration_data = 'Localhost',
                $registration_data = Auth::user()->username,
                $registration_data = $secretkey
            );

            return view('v_user.profil', compact('user', 'QR_Image', 'secretkey'));
        } elseif ($aksi == 'reset-autentikasi') {

            User::where('id', $id)->update(['status_google2fa' => null]);
            return redirect('unit-kerja/profil/user/' . Auth::user()->id)->with('success', 'Berhasil mereset autentikasi 2fa');
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
                    return redirect('unit-kerja/profil/user/' . $id)->with('failed', 'Username sudah terdaftar');
                } else {
                    User::where('id', $id)
                        ->update([
                            'username' => $request->username
                        ]);
                }
                return redirect('keluar')->with('success', 'Berhasil mengubah username');
            }

            return redirect('unit-kerja/profil/user/' . $id)->with('success', 'Berhasil mengubah informasi profil');
        } elseif ($aksi == 'edit-password') {
            $pass = User::where('password_teks', $request->old_password)->where('id', Auth::user()->id)->count();
            if ($pass != 1) {
                return redirect('unit-kerja/profil/user/' . $id)->with('failed', 'Password lama anda salah');
            }

            $cekPass    = Validator::make($request->all(), [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ]);

            if ($cekPass->fails()) {
                return redirect('unit-kerja/profil/user/' . $id)->with('failed', 'Konfirmasi password salah');
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

            return redirect('unit-kerja/dashboard');
        }
    }

    public function Verification(Request $request, $aksi, $id)
    {
        if ($id == 'cek') {
            if (Auth::user()->sess_modul == 'atk') {

                $usulan = UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == null) {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/surat/usulan-atk/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == '1') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pimpinan' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/surat/usulan-atk/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == '2') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'status_proses_id'    => 4
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/usulan/daftar/seluruh-usulan')->with('success', 'Berhasil Memproses Usulan');
                } elseif ($usulan->status_proses_id == '4') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'status_proses_id'    => 5
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/surat/surat-bast/' . Auth::user()->sess_form_id);
                }
            } elseif (Auth::user()->sess_modul == 'oldat') {
                $usulan = FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == null) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-oldat/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 1) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_kabag' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-oldat/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 2) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_ppk' => $request->one_time_password,
                        'status_proses_id'    => 4
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/oldat/surat/surat-bast/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 4) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_kabag'    => $request->one_time_password,
                        'status_proses_id'  => 5
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/oldat/surat/surat-bast/' . Auth::user()->sess_form_id);
                }
            } elseif (Auth::user()->sess_modul == 'aadb') {

                $usulan = UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == null) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-aadb/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 1) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_kabag' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-aadb/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 2) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_ppk' => $request->one_time_password,
                        'status_proses_id'    => 4
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/bast-aadb/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 4) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_kabag'    => $request->one_time_password,
                        'status_proses_id'  => 5
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/bast-aadb/' . Auth::user()->sess_form_id);
                }
            } elseif (Auth::user()->sess_modul == 'gdn') {
                $usulan = UsulanGdn::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == null) {
                    UsulanGdn::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-gdn/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 1) {
                    UsulanGdn::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_kabag' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/surat/usulan-gdn/' . Auth::user()->sess_form_id);
                }
            }
        } else {
            if ($aksi == 'usulan-gdn') {
                User::where('id', Auth::user()->id)->update([
                    'sess_modul'   => 'gdn',
                    'sess_form_id' => $id
                ]);

                return view('google2fa.index');
            } elseif ($aksi == 'usulan-atk') {
                User::where('id', Auth::user()->id)->update([
                    'sess_modul'   => 'atk',
                    'sess_form_id' => $id
                ]);
                return view('google2fa.index');
            } elseif ($aksi == 'usulan-oldat') {
                User::where('id', Auth::user()->id)->update([
                    'sess_modul'   => 'oldat',
                    'sess_form_id' => $id
                ]);
                return view('google2fa.index');
            } elseif ($aksi == 'usulan-aadb') {
                User::where('id', Auth::user()->id)->update([
                    'sess_modul'   => 'aadb',
                    'sess_form_id' => $id
                ]);
                return view('google2fa.index');
            }
        }
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

            return view('v_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
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

            return view('v_user/surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
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

            return view('v_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($aksi == 'usulan-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
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

            return view('v_user/surat_bast', compact('pimpinan', 'bast', 'modul'));
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

            return view('v_user/surat_bast', compact('pimpinan', 'bast', 'modul', 'jenisAadb'));
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
            return view('v_user.surat_bast', compact('modul', 'bast', 'pimpinan'));
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

            return view('v_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
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
            return view('v_user/print_surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
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

            return view('v_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-oldat') {
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();
            return view('v_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
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

            return view('v_user/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul'));
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

            return view('v_user/print_surat_bast', compact('pimpinan', 'bast', 'id', 'modul', 'jenisAadb'));
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
            return view('v_user.print_surat_bast', compact('modul', 'bast', 'pimpinan'));
        }
    }

    // ===============================================
    //               GEDUNG DAN BANGUNAN
    // ===============================================

    public function Gdn(Request $request)
    {
        $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('tanggal_usulan', 'DESC')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->where('gdn_tbl_form_usulan.pegawai_id', Auth::user()->pegawai_id)
            ->get();
        $googleChartData = $this->ChartDataAtk();

        return view('v_user.apk_gdn.index', compact('googleChartData', 'usulan'));
    }

    public function SubmissionGdn(Request $request, $aksi, $id)
    {
        if ($aksi == 'proses') {
            // $total = 0;
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $usulan = new UsulanGdn();
            $usulan->id_form_usulan     = $idFormUsulan;
            $usulan->pegawai_id         = Auth::user()->pegawai_id;
            $usulan->jenis_form         = $request->jenis_form;
            $usulan->no_surat_usulan    = $request->no_surat_usulan;
            $usulan->tanggal_usulan     = $request->tanggal_usulan;
            $usulan->save();

            $detail = $request->lokasi_bangunan;
            foreach ($detail as $i => $detailUsulan) {
                $totalUsulan = UsulanGdnDetail::count();
                $idUsulan    = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $detail      = new UsulanGdnDetail();
                $detail->id_form_usulan_detail  = $idFormUsulan . ($idUsulan + $i);
                $detail->form_usulan_id   = $idFormUsulan;
                $detail->bid_kerusakan_id = $request->bid_kerusakan_id[$i];
                $detail->lokasi_bangunan  = $detailUsulan;
                $detail->lokasi_spesifik  = $request->lokasi_spesifik[$i];
                $detail->keterangan       = $request->keterangan[$i];
                $detail->save();
            }

            UsulanGdn::where('id_form_usulan', $idFormUsulan)->update(['total_pengajuan' => count($request->lokasi_bangunan)]);
            return redirect('unit-kerja/verif/usulan-gdn/' . $idFormUsulan);
        } elseif ($aksi == 'proses-pembatalan') {
            UsulanGdnDetail::where('form_usulan_id', $id)->delete();
            UsulanGdn::where('id_form_usulan', $id)->delete();
            return redirect('unit-kerja/gdn/dashboard')->with('failed', 'Berhasil membatalkan usulan');
        } else {
            $totalUsulan    = UsulanGdn::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $bidKerusakan   = BidangKerusakan::get();
            return view('v_user.apk_gdn.usulan', compact('idUsulan', 'aksi', 'bidKerusakan'));
        }
    }

    public function JsGdn(Request $request, $aksi, $id)
    {
        $gdn  = BidangKerusakan::where('jenis_bid_kerusakan', $id)->get();
        $response = array();
        foreach ($gdn as $data) {
            $response[] = array(
                "id"     =>  $data->id_bid_kerusakan,
                "text"   =>  $data->bid_kerusakan
            );
        }

        return response()->json($response);
    }

    // ===============================================
    //                   OLDAT
    // ===============================================

    public function Oldat(Request $request)
    {
        $googleChartData = $this->ChartDataOldat();
        $usulan  = FormUsulan::orderBy('tanggal_usulan', 'DESC')
            ->where('pegawai_id', Auth::user()->pegawai_id)
            ->get();

        return view('v_user.apk_oldat.index', compact('googleChartData', 'usulan'));
    }

    public function Items(Request $request, $aksi, $id)
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
                DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang")
            )
                ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                ->orderBy('tahun_perolehan', 'DESC')
                ->get();

            $result = json_decode($barang);
            return view('v_user.apk_oldat.daftar_barang', compact('barang'));
        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai', 'ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', 'like', '%' . $id . '%')->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang', 'id_barang', 'barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang', 'id_kategori_barang', 'kategori_barang_id')
                ->join('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where('barang_id', 'like', '%' . $id . '%')->get();

            return view('v_user.apk_oldat.detail_barang', compact('kategoriBarang', 'kondisiBarang', 'pegawai', 'barang', 'riwayat'));
        } elseif ($aksi == 'upload') {
            Excel::import(new BarangImport(), $request->upload);
            return redirect('unit-kerja/oldat/barang/data/semua')->with('success', 'Berhasil Mengupload Data Barang');
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('unit-kerja/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_barang'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('unit-kerja/oldat/barang/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
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
                $cekBarang = RiwayatBarang::orderBy('id_riwayat_barang', 'DESC')->first();
                $riwayat   = new RiwayatBarang();
                $riwayat->id_riwayat_barang = $cekBarang->id_riwayat_barang + 1;
                $riwayat->barang_id         = $id;
                $riwayat->pegawai_id        = $request->input('id_pegawai');
                $riwayat->tanggal_pengguna  = Carbon::now();
                $riwayat->kondisi_barang_id = $request->input('id_kondisi_barang');
                $riwayat->save();
            }

            return redirect('unit-kerja/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $request->id_riwayat_barang)->update([
                'tanggal_pengguna'     => $request->tanggal_pengguna,
                'keperluan_penggunaan' => $request->keperluan_penggunaan
            ]);

            return redirect('unit-kerja/oldat/barang/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        } elseif ($aksi == 'hapus-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $id)->delete();
            return redirect('unit-kerja/oldat/barang/detail/' . $id)->with('success', 'Berhasil Menghapus Riwayat Barang');
        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('unit-kerja/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    public function SubmissionOldat(Request $request, $aksi, $id)
    {
        if ($aksi == 'proses-usulan' && $id == 'pengadaan') {
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan       = $idFormUsulan;
            $formUsulan->pegawai_id           = $request->input('pegawai_id');
            $formUsulan->kode_form            = 'OLDAT_001';
            $formUsulan->jenis_form           = 'pengadaan';
            $formUsulan->total_pengajuan      = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan       = $request->input('tanggal_usulan');
            $formUsulan->rencana_pengguna     = $request->input('rencana_pengguna');
            $formUsulan->no_surat_usulan      = $request->no_surat_usulan;
            $formUsulan->save();

            $barang = $request->kategori_barang_id;
            foreach ($barang as $i => $kategoriBarang) {
                $jumlah = $request->jumlah_barang[$i];
                for ($x = 1; $x <= $jumlah; $x++) {
                    $cekDataDetail  = FormUsulanPengadaan::count();
                    $detailUsulan   = new FormUsulanPengadaan();
                    $detailUsulan->id_form_usulan_pengadaan  = $idFormUsulan . $cekDataDetail . $i;
                    $detailUsulan->form_usulan_id         = $idFormUsulan;
                    $detailUsulan->kategori_barang_id     = $kategoriBarang;
                    $detailUsulan->merk_barang            = $request->merk_barang[$i];
                    $detailUsulan->spesifikasi_barang     = $request->spesifikasi_barang[$i];
                    $detailUsulan->jumlah_barang          = 1;
                    $detailUsulan->satuan_barang          = $request->satuan_barang[$i];
                    $detailUsulan->estimasi_biaya         = $request->estimasi_biaya[$i];
                    $detailUsulan->save();
                }
            }

            return redirect('unit-kerja/verif/usulan-oldat/' . $idFormUsulan);
        } elseif ($aksi == 'proses-usulan' && $id == 'perbaikan') {
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $formUsulan = new FormUsulan();
            $formUsulan->id_form_usulan      = $idFormUsulan;
            $formUsulan->pegawai_id          = $request->input('pegawai_id');
            $formUsulan->kode_form           = 'OLDAT_001';
            $formUsulan->jenis_form          = 'perbaikan';
            $formUsulan->total_pengajuan     = $request->input('total_pengajuan');
            $formUsulan->tanggal_usulan      = $request->input('tanggal_usulan');
            $formUsulan->rencana_pengguna    = $request->input('rencana_pengguna');
            $formUsulan->no_surat_usulan     = $request->no_surat_usulan;
            $formUsulan->save();

            $barang = $request->kode_barang;
            foreach ($barang as $i => $kodeBarang) {
                $cekDataDetail  = FormUsulanPerbaikan::count();
                $detailUsulan   = new FormUsulanPerbaikan();
                $detailUsulan->id_form_usulan_perbaikan  = $idFormUsulan . $cekDataDetail . $i;
                $detailUsulan->form_usulan_id            = $idFormUsulan;
                $detailUsulan->barang_id                 = $kodeBarang;
                $detailUsulan->keterangan_perbaikan      = $request->keterangan_kerusakan[$i];

                $detailUsulan->save();
            }

            return redirect('unit-kerja/verif/usulan-oldat/' . $idFormUsulan);
        } elseif ($aksi == 'proses-pembatalan') {
            FormUsulanPerbaikan::where('form_usulan_id', $id)->delete();
            FormUsulan::where('id_form_usulan', $id)->delete();
            return redirect('unit-kerja/oldat/dashboard')->with('failed', 'Berhasil membatalkan usulan');
        } else {
            $totalUsulan    = FormUsulan::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $kategoriBarang = KategoriBarang::orderBy('kategori_barang', 'ASC')->get();
            $pegawai    = Pegawai::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('id_pegawai', Auth::user()->pegawai_id)
                ->first();

            return view('v_user.apk_oldat.usulan', compact('aksi', 'idUsulan', 'kategoriBarang', 'pegawai'));
        }
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
            'kondisi_barang',
            'pengguna_barang',
            'unit_kerja',
            'pengguna_barang',
            DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"),
            DB::raw("DATE_FORMAT(tahun_perolehan, '%Y') as tahun_perolehan")
        )
            ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->orderBy('tahun_perolehan', 'DESC')
            ->where('oldat_tbl_barang.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
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
            'pengguna_barang',
            'unit_kerja',
            DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang")
        )
            ->join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_barang.kondisi_barang_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
            ->where('oldat_tbl_barang.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
            ->orderBy('tahun_perolehan', 'DESC');


        $dataKategoriBarang = KategoriBarang::get();

        if ($request->hasAny(['barang', 'kondisi'])) {
            if ($request->barang) {
                $dataSearchBarang = $dataBarang->where('kode_barang', $request->barang);
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

    public function Select2Oldat(Request $request, $id)
    {
        if ($id == 'daftar') {
            $search = $request->search;

            if ($search == '') {
                $result  = Barang::select('id_barang', DB::raw('CONCAT(unit_kerja," - ",kode_barang,".",nup_barang," - ",merk_tipe_barang) AS merk_tipe_barang'))
                    ->join('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'oldat_tbl_barang.unit_kerja_id')
                    ->where('kategori_barang_id', $request->kategori)
                    ->where('oldat_tbl_barang.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
                    ->where('status_barang', '!=', '2')
                    ->pluck('id_barang', 'merk_tipe_barang');
            }
        } elseif ($id == 'detail') {
            $result   = Barang::join('oldat_tbl_kondisi_barang', 'id_kondisi_barang', 'kondisi_barang_id')
                ->where('id_barang', 'like', '%' . $request->idBarang . '%')
                ->where('oldat_tbl_barang.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
                ->get();
        }

        // dd($result);
        return response()->json($result);
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

    // ===============================================
    //                   AADB
    // ===============================================
    public function Aadb(Request $request)
    {
        $unitKerja      = UnitKerja::get();
        $jenisKendaraan = JenisKendaraan::get();
        $merk           = Kendaraan::select('merk_tipe_kendaraan')->groupBy('merk_tipe_kendaraan')->get();
        $tahun          = Kendaraan::select('tahun_kendaraan')->groupBy('tahun_kendaraan')->get();
        $pengguna       = Kendaraan::select('pengguna')->groupBy('pengguna')->get();
        $kendaraan      = Kendaraan::orderBy('jenis_aadb', 'ASC')
            ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->get();
        $stnk           = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
            ->leftjoin('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->where(DB::raw("(DATE_FORMAT(mb_stnk_plat_kendaraan, '%Y-%m'))"), '>', Carbon::now()->format('Y-m'))
            ->orderBy('mb_stnk_plat_kendaraan', 'ASC')
            ->get();
        $googleChartData = $this->ChartDataAadb();

        $usulan  = UsulanAadb::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
            ->orderBy('tanggal_usulan', 'DESC')
            ->orderBy('status_proses_id', 'ASC')
            ->where('pegawai_id', Auth::user()->pegawai_id)
            ->get();

        $jadwalServis = JadwalServis::join('aadb_tbl_kendaraan', 'id_kendaraan', 'kendaraan_id')
            ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
            ->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
            ->get();

        return view('v_user.apk_aadb.index', compact(
            'unitKerja',
            'jenisKendaraan',
            'merk',
            'tahun',
            'pengguna',
            'googleChartData',
            'kendaraan',
            'usulan',
            'stnk',
            'jadwalServis'
        ));
    }

    public function Vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'detail') {
            $unitKerja = UnitKerja::get();
            $jenis     = JenisKendaraan::get();
            $kondisi   = KondisiKendaraan::get();
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->first();

            return view('v_user.apk_aadb.detail_kendaraan', compact('unitKerja', 'jenis', 'kondisi', 'kendaraan'));
        } elseif ($aksi == 'proses-ubah') {

            if ($request->update == 'foto') {
                if ($request->hapus == 1) {
                    $file_old = public_path() . '\gambar\kendaraan\\' . $request->foto_lama;
                    unlink($file_old);
                    Kendaraan::where('id_kendaraan', $id)->update([
                        'foto_kendaraan' => null
                    ]);
                    return redirect('unit-kerja/aadb/kendaraan/detail/' . $id)->with('failed', 'Berhasil menghapus foto kendaraan');
                } else {

                    $cekFoto  = Validator::make($request->all(), [
                        'foto_kendaraan'    => 'mimes: jpg,png,jpeg|max:4096',
                    ]);

                    if ($cekFoto->fails()) {
                        return redirect('unit-kerja/aadb/kendaraan/detail/' . $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
                    } else {
                        if ($request->foto_kendaraan == null) {
                            $fotoKendaraan = $request->foto_lama;
                        } else {
                            $dataKendaraan = Kendaraan::where('id_kendaraan', 'like', '%' . $id . '%')->first();

                            if ($request->hasfile('foto_kendaraan')) {
                                if ($dataKendaraan->foto_kendaraan != null) {
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
                            Kendaraan::where('id_kendaraan', $id)->update([
                                'foto_kendaraan' => $fotoKendaraan,
                            ]);
                        }
                    }
                }
            } else {

                Kendaraan::where('id_kendaraan', $id)->update([
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
                    'foto_kendaraan'          => $request->foto_kendaraan,
                    'status_kendaraan_id'     => $request->status_kendaraan_id
                ]);

                if ($request->proses == 'pengguna-baru') {
                    RiwayatKendaraan::where('kendaraan_id', $id)->update([
                        'status_pengguna'  => 2,
                        'status_pengemudi' => 2
                    ]);

                    $riwayat   = new RiwayatKendaraan();
                    $riwayat->kendaraan_id      = $id;
                    $riwayat->tanggal_pengguna  = Carbon::now();
                    $riwayat->pengguna          = $request->pengguna;
                    $riwayat->jabatan           = $request->jabatan;
                    $riwayat->pengemudi         = $request->pengemudi;
                    $riwayat->status_pengguna   = 1;

                    if ($request->pengemudi != null) {
                        $riwayat->status_pengemudi  = 1;
                    } else {
                        $riwayat->status_pengemudi  = 0;
                    }

                    $riwayat->save();
                }

                if ($request->proses == 'pengemudi-baru') {
                    RiwayatKendaraan::where('kendaraan_id', $id)->update([
                        'status_pengguna'  => 2,
                        'status_pengemudi' => 2
                    ]);

                    $riwayat   = new RiwayatKendaraan();
                    $riwayat->kendaraan_id      = $id;
                    $riwayat->tanggal_pengguna  = Carbon::now();
                    $riwayat->pengguna          = $request->pengguna;
                    $riwayat->jabatan           = $request->jabatan;
                    $riwayat->pengemudi         = $request->pengemudi;
                    $riwayat->status_pengemudi  = 1;

                    if ($request->pengemudi != null) {
                        $riwayat->status_pengguna  = 1;
                    } else {
                        $riwayat->status_pengguna  = 0;
                    }

                    $riwayat->save();
                }
            }

            return redirect('unit-kerja/aadb/kendaraan/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Kendaraan');
        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatKendaraan::where('id_riwayat_kendaraan', $request->id_riwayat_kendaraan)->update([
                'tanggal_pengguna'  => $request->tanggal_pengguna,
                'pengguna'          => $request->pengguna,
                'jabatan'           => $request->jabatan,
                'pengemudi'         => $request->pengemudi
            ]);

            $riwayat = RiwayatKendaraan::where('id_riwayat_kendaraan', $request->id_riwayat_kendaraan)->first();
            if ($riwayat->status_pengguna == 1) {
                Kendaraan::where('id_kendaraan', $riwayat->kendaraan_id)->update([
                    'pengguna'          => $request->pengguna,
                    'jabatan'           => $request->jabatan,
                    'pengemudi'         => $request->pengemudi
                ]);
            }

            return redirect('unit-kerja/aadb/kendaraan/detail/' . $id)->with('success', 'Berhasil Mengubah Informasi Kendaraan');
        } elseif ($aksi == 'hapus-riwayat') {
            $riwayat   = RiwayatKendaraan::where('id_riwayat_kendaraan', $id)->first();
            RiwayatKendaraan::where('id_riwayat_kendaraan', $id)->delete();

            Kendaraan::where('pengguna', 'like', '%' . $riwayat->pengguna . '%')->update([
                'pengguna'  => null,
                'jabatan'   => null,
                'pengemudi' => null
            ]);

            return redirect('unit-kerja/aadb/kendaraan/detail/' . $riwayat->kendaraan_id)->with('success', 'Berhasil Menghapus Riwayat Pengguna Kendaraan');
        } elseif ($aksi == 'detail-json') {
            $result = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->where('id_kendaraan', $request->kendaraanId)
                ->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
                ->get();

            return response()->json($result);
        } elseif ($aksi == 'servis-record') {

            JadwalServis::where('id_jadwal_servis', $id)->update([
                'km_terakhir' => $request->km_terakhir,
                'km_servis' => $request->km_servis,
                'km_ganti_oli' => $request->km_ganti_oli
            ]);

            return redirect('unit-kerja/aadb/dashboard')->with('success', 'Berhasil menyimpan servis record');
        }
    }

    public function SubmissionAadb(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {

            $pengajuan  = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->where('status_proses_id', $id)
                ->get();

            return view('v_super_user.apk_aadb.daftar_pengajuan', compact('pengajuan'));
        } elseif ($aksi == 'detail') {

            $pengajuan  = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->where('id_form_usulan', $id)
                ->get();

            return view('v_super_user.apk_aadb.daftar_pengajuan', compact('pengajuan'));
        } elseif ($aksi == 'daftar') {
            $pengajuan = UsulanAadb::with('usulanKendaraan')
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->get();

            return view('v_super_user.apk_aadb.daftar_pengajuan', compact('pengajuan'));
        } elseif ($aksi == 'proses') {
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $usulan = new UsulanAadb();
            $usulan->id_form_usulan      = $idFormUsulan;
            $usulan->pegawai_id          = Auth::user()->pegawai_id;
            $usulan->kode_form           = 'AADB_001';
            $usulan->jenis_form          = $request->jenis_form;
            $usulan->total_pengajuan     = $request->total_pengajuan;
            $usulan->tanggal_usulan      = $request->tanggal_usulan;
            $usulan->rencana_pengguna    = $request->rencana_pengguna;
            $usulan->otp_usulan_pengusul = $request->kode_otp_usulan;
            $usulan->no_surat_usulan     = $request->no_surat_usulan;
            $usulan->save();

            if ($id == 'pengadaan') {
                $totalUsulan    = UsulanKendaraan::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $usulanPengadaan = new UsulanKendaraan();
                $usulanPengadaan->id_form_usulan_pengadaan  = $idUsulan;
                $usulanPengadaan->form_usulan_id            = $idFormUsulan;
                $usulanPengadaan->jenis_aadb                = $request->jenis_aadb;
                $usulanPengadaan->jenis_kendaraan_id        = $request->jenis_kendaraan;
                $usulanPengadaan->merk_tipe_kendaraan       = $request->merk_kendaraan . ' ' . $request->tipe_kendaraan;
                $usulanPengadaan->tahun_kendaraan           = $request->tahun_kendaraan;
                $usulanPengadaan->save();
            } elseif ($id == 'servis') {
                $totalUsulan    = UsulanServis::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan      = $request->kendaraan_id;
                foreach ($kendaraan as $i => $kendaraan_id) {
                    $usulanServis   = new UsulanServis();
                    $usulanServis->id_form_usulan_servis    = $idUsulan + $i;
                    $usulanServis->form_usulan_id           = $idFormUsulan;
                    $usulanServis->kendaraan_id             = $kendaraan_id;
                    $usulanServis->kilometer_terakhir       = $request->kilometer_terakhir[$i];
                    $usulanServis->tgl_servis_terakhir      = $request->tgl_servis_terakhir[$i];
                    $usulanServis->jatuh_tempo_servis       = $request->jatuh_tempo_servis[$i];
                    $usulanServis->tgl_ganti_oli_terakhir   = $request->tgl_ganti_oli_terakhir[$i];
                    $usulanServis->jatuh_tempo_ganti_oli    = $request->jatuh_tempo_ganti_oli[$i];
                    $usulanServis->save();
                }
            } elseif ($id == 'perpanjangan-stnk') {
                $totalUsulan    = UsulanPerpanjanganSTNK::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan = $request->kendaraan_id;
                foreach ($kendaraan as $i => $kendaraan_id) {
                    $usulanPerpanjangan   = new UsulanPerpanjanganSTNK();
                    $usulanPerpanjangan->id_form_usulan_perpanjangan_stnk  = $idUsulan + $i;
                    $usulanPerpanjangan->form_usulan_id                    = $idFormUsulan;
                    $usulanPerpanjangan->kendaraan_id                      = $kendaraan_id;
                    $usulanPerpanjangan->mb_stnk_lama                      = $request->mb_stnk[$i];
                    $usulanPerpanjangan->save();
                }
            } elseif ($id == 'voucher-bbm') {
                $totalUsulan    = UsulanVoucherBBM::count();
                $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
                $kendaraan = $request->kendaraan_id;
                foreach ($kendaraan as $i => $kendaraan_id) {
                    $totalVoucher = ($request->voucher_25[$i] * 25000) + ($request->voucher_50[$i] * 50000) + ($request->voucher_100[$i] * 100000);
                    $usulanVoucherBBM   = new UsulanVoucherBBM();
                    $usulanVoucherBBM->id_form_usulan_voucher_bbm   = $idUsulan + $i;
                    $usulanVoucherBBM->form_usulan_id               = $idFormUsulan;
                    $usulanVoucherBBM->kendaraan_id                 = $kendaraan_id;
                    $usulanVoucherBBM->voucher_25                   = $request->voucher_25[$i];
                    $usulanVoucherBBM->voucher_50                   = $request->voucher_50[$i];
                    $usulanVoucherBBM->voucher_100                  = $request->voucher_100[$i];
                    $usulanVoucherBBM->total_biaya                  = $totalVoucher;
                    $usulanVoucherBBM->bulan_pengadaan              = $request->bulan_pengadaan;
                    $total[$i] = +$totalVoucher;
                    $usulanVoucherBBM->save();
                }

                UsulanAadb::where('id_form_usulan')->update(['total_biaya' => array_sum($total)]);
            }

            return redirect('unit-kerja/verif/usulan-aadb/' . $idFormUsulan);
            // return redirect('unit-kerja/aadb/surat/surat-usulan/'. $idFormUsulan);

        } elseif ($aksi == 'proses-pembatalan') {
            UsulanAadb::where('id_form_usulan', $id)->delete();
            UsulanServis::where('form_usulan_id', $id)->delete();
            UsulanPerpanjanganSTNK::where('form_usulan_id', $id)->delete();
            UsulanVoucherBBM::where('form_usulan_id', $id)->delete();
            return redirect('unit-kerja/aadb/dashboard')->with('failed', 'Berhasil membatalkan usulan');
        } else {
            $totalUsulan    = UsulanAadb::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $jenisKendaraan = JenisKendaraan::get();
            $kendaraan      = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
                ->orderBy('jenis_kendaraan', 'ASC')
                ->get();
            return view('v_user.apk_aadb.usulan', compact('idUsulan', 'aksi', 'jenisKendaraan', 'kendaraan'));
        }
    }

    public function ChartDataAadb()
    {
        $dataKendaraan = Kendaraan::join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->join('aadb_tbl_jenis_kendaraan', 'jenis_kendaraan_id', 'id_jenis_kendaraan')
            ->where('aadb_tbl_kendaraan.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
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
            ->join('aadb_tbl_jenis_kendaraan', 'jenis_kendaraan_id', 'id_jenis_kendaraan')
            ->where('aadb_tbl_kendaraan.unit_kerja_id', Auth::user()->pegawai->unit_kerja_id);

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

        // dd($dataSearch);
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

    public function Select2Aadb(Request $request, $aksi)
    {
        if ($aksi == 'kendaraan') {
            $search = $request->search;

            if ($search == '') {
                $kendaraan  = Kendaraan::select('id_kendaraan', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_tipe_kendaraan, " / ", pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
                    ->get();
            } else {
                $kendaraan  = Kendaraan::select('id_kendaraan', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_tipe_kendaraan, " / ", pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->where('merk_tipe_kendaraan', 'like', '%' . $search . '%')
                    ->where('unit_kerja_id', Auth::user()->pegawai->unit_kerja_id)
                    ->get();
            }

            $response = array();
            foreach ($kendaraan as $data) {
                $response[] = array(
                    "id"    =>  $data->id_kendaraan,
                    "text"  =>  $data->nama_kendaraan
                );
            }

            return response()->json($response);
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

    // ===============================================
    //               RUMAH DINAS NEGARA
    // ===============================================

    public function OfficialResidence(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $rumah      = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah', 'kondisi_rumah_id')->get();
            return view('v_user.apk_rdn.daftar_rumah', compact('rumah'));
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

            return view('v_user.apk_rdn.detail_rumah', compact('pegawai', 'rumah', 'penghuni', 'kondisi'));
        }
    }

    // ===============================================
    //                      ATK
    // ===============================================

    public function Atk(Request $request)
    {
        $googleChartData = $this->ChartDataAtk();
        $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->where('atk_tbl_form_usulan.pegawai_id', Auth::user()->pegawai_id)
            ->orderBy('tanggal_usulan', 'DESC')
            ->orderBy('status_pengajuan_id', 'ASC')
            ->orderBy('status_proses_id', 'ASC')
            ->get();

        $atk  = UsulanAtkDetail::select('atk_id', 'merk_atk', 'tanggal_usulan', 'atk_tbl.satuan', 'jenis_form', DB::raw('sum(jumlah_pengajuan) as total_atk'))
            ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
            ->join('atk_tbl', 'id_atk', 'atk_id')
            ->join('atk_tbl_kelompok_sub_kategori', 'id_kategori_atk', 'kategori_atk_id')
            ->where('pegawai_id', Auth::user()->pegawai_id)
            ->groupBy('atk_id', 'merk_atk', 'tanggal_usulan', 'atk_tbl.satuan', 'jenis_form')
            ->orderBy('tanggal_usulan', 'DESC')
            ->get();

        return view('v_user.apk_atk.index', compact('googleChartData', 'usulan', 'atk'));
    }

    public function OfficeStationery(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $atk = ATK::with('KategoriATK')->get();
            return view('v_user.apk_atk.daftar_atk', compact('atk'));
        }
    }

    public function SubmissionAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'status') {
            $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->where('status_proses_id', $id)
                ->where('atk_tbl_form_usulan.pegawai_id', Auth::user()->pegawai_id)
                ->get();

            return view('v_user.apk_atk.daftar_pengajuan', compact('usulan'));
        } elseif ($aksi == 'daftar') {
            $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan', 'id_status_pengajuan', 'status_pengajuan_id')
                ->join('tbl_status_proses', 'id_status_proses', 'status_proses_id')
                ->orderBy('tanggal_usulan', 'DESC')
                ->orderBy('status_pengajuan_id', 'ASC')
                ->orderBy('status_proses_id', 'ASC')
                ->where('atk_tbl_form_usulan.pegawai_id', Auth::user()->pegawai_id)
                ->get();

            return view('v_user.apk_atk.daftar_pengajuan', compact('usulan'));
        } elseif ($aksi == 'proses') {
            // dd($request->all());
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $usulan = new UsulanAtk();
            $usulan->id_form_usulan     = $idFormUsulan;
            $usulan->pegawai_id         = Auth::user()->pegawai_id;
            $usulan->jenis_form         = $id;
            $usulan->total_pengajuan    = count($request->atk_id);
            $usulan->no_surat_usulan    = $request->no_surat_usulan;
            $usulan->tanggal_usulan     = $request->tanggal_usulan;
            $usulan->rencana_pengguna   = $request->rencana_pengguna;
            $usulan->save();

            // Daftar barang
            $daftar = $request->atk_id;
            foreach ($daftar as $i => $dataAtk) {
                $idDetail = UsulanAtkDetail::count();
                $detail   = new UsulanAtkDetail();
                $detail->id_form_usulan_detail = $idDetail + 1;
                $detail->form_usulan_id        = $idFormUsulan;

                if ($dataAtk == 'lain-lain') {
                    // Input barang lain
                    $total  = Atk::where('kategori_atk_id', $request->kategori_atk_id[$i])->count();
                    $atk_id = $request->kategori_atk_id[$i] . str_pad($total + 1, 5, 0, STR_PAD_LEFT);
                    $detail->atk_id   = $atk_id;
                    $detail->atk_lain = strtoupper($request->barang_lain[$i]);
                    $cekAtk = Atk::where('merk_atk', 'like', '%' . $request->barang_lain[$i] . '%')->count();
                    if ($cekAtk == 0) {
                        $atkLain = new Atk();
                        $atkLain->id_atk          = $atk_id;
                        $atkLain->kategori_atk_id = $request->kategori_atk_id[$i];
                        $atkLain->merk_atk        = strtoupper($request->barang_lain[$i]);
                        $atkLain->total_atk       = 0;
                        $atkLain->satuan          = strtoupper($request->satuan[$i]);
                        $atkLain->save();
                    }
                } else {
                    // Input barang yang sudah ada
                    $detail->atk_id = $request->atk_id[$i];
                }

                $detail->jumlah_pengajuan      = $request->jumlah[$i];
                $detail->satuan_detail         = strtoupper($request->satuan[$i]);
                $detail->keterangan            = $request->keterangan[$i];
                $detail->save();
            }

            return redirect('unit-kerja/verif/usulan-atk/' . $idFormUsulan);
        } elseif ($aksi == 'preview-pengadaan') {
            $idUsulan = $request->id_usulan;
            $noSurat  = $request->no_surat_usulan;
            $tanggal  = $request->tanggal_usulan;
            $rencana  = $request->rencana_pengguna;

            if ($request->file_atk == null) {
                $fileAtk = null;
            } else {
                $fileAtk = Excel::toArray(new ImportAtk(), $request->file_atk);
            }

            if ($request->file_alkom == null) {
                $fileAlkom = null;
            } else {
                $fileAlkom = Excel::toArray(new ImportAlkom(), $request->file_alkom);
            }

            if ($request->file_atk == null && $request->file_alkom == null) {
                return redirect('unit-kerja/atk/usulan/pengadaan/baru')->with('failed', 'Anda belum mengunggah file kebutuhan Alkom atau Oldat');
            }

            return view('v_user.apk_atk.preview', compact('fileAtk', 'fileAlkom', 'idUsulan', 'noSurat', 'tanggal', 'rencana'));
        } elseif ($aksi == 'proses-pengadaan') {
            $id_usulan = Carbon::now()->format('dmy'). $request->id_usulan;
            if ($request->atk_barang != null) {
                $totalAtk = count($request->atk_barang);
                $atk = $request->atk_barang;
                foreach ($atk as $i => $dataAtk) {
                    $jumlahUsulan = UsulanAtkPengadaan::count();
                    $pengadaanAtk = new UsulanAtkPengadaan();
                    $pengadaanAtk->id_form_usulan_pengadaan = $jumlahUsulan . Carbon::now()->format('dmy') . rand(000, 999);
                    $pengadaanAtk->form_usulan_id = $id_usulan;
                    $pengadaanAtk->jenis_barang = 'ATK';
                    $pengadaanAtk->nama_barang = strtoupper($request->atk_barang[$i]);
                    $pengadaanAtk->spesifikasi = strtoupper($request->atk_spesifikasi[$i]);
                    $pengadaanAtk->jumlah = $request->atk_jumlah[$i];
                    $pengadaanAtk->satuan = strtoupper($request->atk_satuan[$i]);
                    $pengadaanAtk->tanggal = Carbon::now();
                    $pengadaanAtk->status = 'proses';
                    $pengadaanAtk->save();
                }
            } else {
                $totalAtk = 0;
            }

            if ($request->alkom_barang != null) {
                $totalAlkom = count($request->alkom_barang);
                $alkom = $request->alkom_barang;
                foreach ($alkom as $i => $dataAtk) {
                    if ($request->alkom_jumlah != 0) {
                        $jumlahUsulan = UsulanAtkPengadaan::count();
                        $pengadaanAtk = new UsulanAtkPengadaan();
                        $pengadaanAtk->id_form_usulan_pengadaan = $jumlahUsulan . Carbon::now()->format('dmy') . rand(000, 999);
                        $pengadaanAtk->form_usulan_id = $id_usulan;
                        $pengadaanAtk->jenis_barang = 'ALKOM';
                        $pengadaanAtk->nama_barang = strtoupper($request->alkom_barang[$i]);
                        $pengadaanAtk->spesifikasi = strtoupper($request->alkom_spesifikasi[$i]);
                        $pengadaanAtk->jumlah = $request->alkom_jumlah[$i];
                        $pengadaanAtk->satuan = strtoupper($request->alkom_satuan[$i]);
                        $pengadaanAtk->tanggal = Carbon::now();
                        $pengadaanAtk->status = 'proses';
                        $pengadaanAtk->save();
                    }
                }
            } else {
                $totalAlkom = 0;
            }

            if ($request->atk_barang == null && $request->alkom_barang == null) {
                $totalAtk = count($request->barang);
                $barang = $request->barang;
                foreach ($barang as $i => $dataAtk) {
                    if ($request->jumlah != 0) {
                        $jumlahUsulan = UsulanAtkPengadaan::count();
                        $pengadaanAtk = new UsulanAtkPengadaan();
                        $pengadaanAtk->id_form_usulan_pengadaan = $jumlahUsulan . Carbon::now()->format('dmy') . rand(000, 999);
                        $pengadaanAtk->form_usulan_id = $id_usulan;
                        $pengadaanAtk->jenis_barang = strtoupper($request->jenis_barang[$i]);
                        $pengadaanAtk->nama_barang = strtoupper($request->barang[$i]);
                        $pengadaanAtk->spesifikasi = strtoupper($request->spesifikasi[$i]);
                        $pengadaanAtk->jumlah = $request->jumlah[$i];
                        $pengadaanAtk->satuan = strtoupper($request->satuan[$i]);
                        $pengadaanAtk->tanggal = Carbon::now();
                        $pengadaanAtk->status = 'proses';
                        $pengadaanAtk->save();
                    }
                }
            }

            $usulan = new UsulanAtk();
            $usulan->id_form_usulan     = $id_usulan;
            $usulan->pegawai_id         = Auth::user()->pegawai_id;
            $usulan->jenis_form         = 'pengadaan';
            $usulan->total_pengajuan    = $totalAtk + $totalAlkom;
            $usulan->no_surat_usulan    = $request->no_surat_usulan;
            $usulan->tanggal_usulan     = $request->tanggal_usulan;
            $usulan->rencana_pengguna   = $request->rencana_pengguna;
            $usulan->save();

            return redirect('unit-kerja/verif/usulan-atk/' . $id_usulan);
        } elseif ($aksi == 'proses-diterima') {

            $detailId = $request->detail_form_id;
            foreach ($detailId as $i => $id_form_usulan_detail) {
                UsulanAtkDetail::where('id_form_usulan_detail', $id_form_usulan_detail)
                    ->update([
                        'jumlah_pengajuan' => $request->jumlah_pengajuan[$i]
                    ]);
            }


            return redirect('unit-kerja/verif/usulan-atk/' . $id)->with('success', 'Pembelian barang telah selesai dilakukan');
        } elseif ($aksi == 'proses-ditolak') {

            UsulanAtk::where('id_form_usulan', $id)->update(['status_pengajuan_id' => 2, 'status_proses_id' => 5]);
            return redirect('unit-kerja/atk/usulan/daftar/seluruh-usulan')->with('failed', 'Usulan Pengajuan Ditolak');
        } elseif ($aksi == 'persetujuan') {
            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->first();

            return view('v_user/apk_atk/proses_persetujuan', compact('usulan'));
        } elseif ($aksi == 'proses-pembatalan') {
            UsulanAtkDetail::where('form_usulan_id', $id)->delete();
            UsulanAtk::where('id_form_usulan', $id)->delete();
            return redirect('unit-kerja/atk/dashboard')->with('failed', 'Berhasil membatalkan usulan');
        } else {
            $totalUsulan    = UsulanAtk::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $kelompokAtk    = KelompokAtk::get();
            return view('v_user.apk_atk.usulan', compact('idUsulan', 'aksi', 'kelompokAtk'));
        }
    }

    public function Select2Atk(Request $request, $aksi, $id)
    {

        $search = $request->search;
        if ($aksi == 1) {
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
            if ($search == '') {
                $atk  = Atk::select('id_atk as id', 'kategori_atk_id', 'merk_atk as nama')
                    ->orderby('id_atk', 'asc')
                    ->where('kategori_atk_id', $id)
                    ->get();
            } else {
                $atk  = Atk::select('id_atk', 'kategori_atk_id', 'merk_atk')
                    ->orderby('id_atk', 'asc')
                    ->where('kategori_atk_id', $id)
                    ->where('id_atk', 'like', '%' . $search . '%')
                    ->orWhere('merk_atk', 'like', '%' . $search . '%')
                    ->get();
            }
        } elseif ($aksi == 5) {
            $atk  = Atk::select('id_atk as id', 'total_atk as stok', 'satuan')
                ->orderby('id_atk', 'asc')
                ->groupBy('id', 'stok', 'satuan')
                ->where('id_atk', $id)
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
                $atk  = Atk::select('id_atk as id', 'kategori_atk_id', 'merk_atk as nama')
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
        $dataAtk = Atk::join('atk_tbl_kelompok_sub_kategori', 'id_kategori_atk', 'kategori_atk_id')
            ->join('atk_tbl_kelompok_sub_jenis', 'id_jenis_atk', 'jenis_atk_id')
            ->orderBy('total_atk', 'DESC')
            ->get();

        // $stok = $dataAtk->select(DB::raw('sum(total_atk) as stok'))->groupBy('total_atk');
        $totalAtk = Atk::select('id_kategori_atk', 'kategori_atk', DB::raw('sum(total_atk) as stok'))
            ->join('atk_tbl_kelompok_sub_kategori', 'id_kategori_atk', 'kategori_atk_id')
            ->groupBy('id_kategori_atk', 'kategori_atk')
            ->get();

        foreach ($totalAtk as $data) {
            $dataArray[] = $data->kategori_atk;
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
}
