<?php

namespace App\Http\Controllers;

use App\Models\AADB\UsulanAadb;
use App\Models\atk\UsulanAtk;
use App\Models\gdn\BidangKerusakan;
use App\Models\gdn\UsulanGdn;
use App\Models\gdn\UsulanGdnDetail;
use App\Models\OLDAT\FormUsulan;
use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google2FA;

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
        $user = User::where('id', Auth::user()->id)->first();
        if ($aksi == 'user') {
            $google2fa  = app('pragmarx.google2fa');
            $secretkey  = $google2fa->generateSecretKey();
            $QR_Image   = $google2fa->getQRCodeInline(
                config('app.name'),
                $registration_data = Auth::user()->username,
                $registration_data = $secretkey
            );

            return view('v_user.profil', compact('QR_Image', 'secretkey'));
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

                    return redirect('unit-kerja/atk/surat/surat-usulan/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == '1') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pimpinan' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/atk/surat/surat-usulan/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == '2') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'status_proses_id'    => 4
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/atk/usulan/daftar/seluruh-usulan')->with('success','Berhasil Memproses Usulan');

                } elseif ($usulan->status_proses_id == '4') {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'status_proses_id'    => 5
                    ]);
                    Google2FA::logout();

                    return redirect('unit-kerja/atk/surat/surat-bast/' . Auth::user()->sess_form_id);
                }
            } elseif (Auth::user()->sess_modul == 'oldat') {

                $usulan = FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == null) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_pengusul' => $request->one_time_password,
                        'status_proses_id'    => 1
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/oldat/surat/surat-usulan/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 1) {
                    FormUsulan::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_kabag' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/oldat/surat/surat-usulan/' . Auth::user()->sess_form_id);
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
                    return redirect('unit-kerja/aadb/surat/surat-usulan/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 1) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_usulan_kabag' => $request->one_time_password,
                        'status_pengajuan_id' => 1,
                        'status_proses_id'    => 2
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/aadb/surat/surat-usulan/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 2) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_ppk' => $request->one_time_password,
                        'status_proses_id'    => 4
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/aadb/surat/surat-bast/' . Auth::user()->sess_form_id);
                } elseif ($usulan->status_proses_id == 4) {
                    UsulanAadb::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_kabag'    => $request->one_time_password,
                        'status_proses_id'  => 5
                    ]);
                    Google2FA::logout();
                    return redirect('unit-kerja/aadb/surat/surat-bast/' . Auth::user()->sess_form_id);
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
            }
        }
    }

    public function Letter(Request $request, $aksi, $id)
    {
        if ($aksi == 'usulan-gdn') {
            $form = UsulanGdn::where('id_form_usulan', $id)->first();
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')
                ->where('unit_kerja_id', 1)
                ->first();

            $usulan = UsulanGdn::where('id_form_usulan', $id)
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->first();

            return view('v_user/surat_usulan', compact('usulan','pimpinan'));
        }
    }

    // ===============================================
    //               GEDUNG DAN BANGUNAN
    // ===============================================

    public function Building()
    {
        $usulan = UsulanGdn::get();
        return view('v_user.apk_gdn.index', compact('usulan'));
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
            foreach ($detail as $i => $detailUsulan)
            {
                $detail = new UsulanGdnDetail();
                $detail->id_form_usulan_detail  = ($request->id_usulan + 1) + $i;
                $detail->form_usulan_id   = $idFormUsulan;
                $detail->bid_kerusakan_id = $request->bid_kerusakan_id[$i];
                $detail->lokasi_bangunan  = $detailUsulan;
                $detail->lokasi_spesifik  = $request->lokasi_spesifik[$i];
                $detail->keterangan       = $request->keterangan[$i];
                $detail->save();
            }

            UsulanGdn::where('id_form_usulan', $idFormUsulan)->update(['total_pengajuan' => count($request->lokasi_bangunan)]);
            return redirect('unit-kerja/verif/usulan-gdn/' . $idFormUsulan);

        } else {
            $totalUsulan    = UsulanGdn::count();
            $idUsulan       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $bidKerusakan   = BidangKerusakan::get();
            return view('v_user.apk_gdn.usulan', compact('idUsulan', 'aksi','bidKerusakan'));
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


}
