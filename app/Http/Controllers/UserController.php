<?php

namespace App\Http\Controllers;

use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\UsulanAadb;
use App\Models\atk\Atk;
use App\Models\atk\JenisAtk;
use App\Models\atk\KategoriAtk;
use App\Models\atk\KelompokAtk;
use App\Models\atk\StokAtk;
use App\Models\atk\SubKelompokAtk;
use App\Models\atk\UsulanAtk;
use App\Models\atk\UsulanAtkDetail;
use App\Models\gdn\BidangKerusakan;
use App\Models\gdn\UsulanGdn;
use App\Models\gdn\UsulanGdnDetail;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\FormUsulan;
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
            } elseif ($aksi == 'usulan-atk') {
                User::where('id', Auth::user()->id)->update([
                    'sess_modul'   => 'atk',
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

            return view('v_user/surat_usulan', compact('modul', 'usulan', 'pimpinan'));
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

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_user/surat_bast', compact('pimpinan', 'bast', 'modul'));
        }
    }

    public function PrintLetter(Request $request, $modul, $id)
    {
        if ($modul == 'usulan-gdn') {
            $form = UsulanGdn::where('id_form_usulan', $id)->first();
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
            $form = UsulanAtk::where('id_form_usulan', $id)->first();
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

            return view('v_user/print_surat_usulan', compact('modul', 'usulan', 'pimpinan'));
        } elseif ($modul == 'usulan-aadb') {
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

            return view('v_user/print_surat_bast', compact('pimpinan', 'bast', 'id','modul'));
        } elseif ($modul == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('id_form_usulan', $id)
                ->first();

            return view('v_user/print_surat_bast', compact('pimpinan', 'bast', 'id','modul'));
        }
    }

    // ===============================================
    //               GEDUNG DAN BANGUNAN
    // ===============================================

    public function Gdn(Request $request)
    {
        $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            // ->where('pegawai_id', Auth::user()->pegawai_id)
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

    public function Oldat (Request $request)
    {
        $googleChartData = $this->ChartDataOldat();
        $usulan  = FormUsulan::with('detailPengadaan')->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->orderBy('tanggal_usulan', 'DESC')
            ->where('pegawai_id', Auth::user()->id)
            ->get();


        return view('v_user.apk_oldat.index', compact('googleChartData', 'usulan'));
    }

    public function Items(Request $request, $aksi, $id)
    {
        if ($aksi == 'detail') {
            $barang = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', 'like', '%'.$id.'%')
                ->first();
            $riwayat = RiwayatBarang::join('oldat_tbl_barang', 'id_barang', 'barang_id')
                ->join('oldat_tbl_kondisi_barang', 'oldat_tbl_kondisi_barang.id_kondisi_barang', 'oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->where('barang_id', 'like', '%'.$id.'%')
                ->get();

            return view('v_user.apk_oldat.detail_barang', compact('barang', 'riwayat'));
        } else {

        }
    }

    public function ChartDataOldat()
    {
        $char = '"';
        $dataBarang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang', 'satuan_barang', 'nilai_perolehan', 'tahun_perolehan',
                'kondisi_barang', 'nama_pegawai', \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"), 'unit_kerja')
                ->join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','oldat_tbl_barang.unit_kerja_id')
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
        $dataBarang = Barang::select('id_barang','kode_barang','kategori_barang','nup_barang','jumlah_barang', 'satuan_barang', 'nilai_perolehan', 'tahun_perolehan',
                'kondisi_barang', 'nama_pegawai', \DB::raw("REPLACE(merk_tipe_barang, '$char', '&#x22;') as barang"), 'unit_kerja')
                ->join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja','id_unit_kerja','oldat_tbl_barang.unit_kerja_id')
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
        $unitKerja       = UnitKerja::get();
        $jenisKendaraan  = JenisKendaraan::get();
        $merk            = Kendaraan::select('merk_tipe_kendaraan')->groupBy('merk_tipe_kendaraan')->get();
        $tahun           = Kendaraan::select('tahun_kendaraan')->groupBy('tahun_kendaraan')->get();
        $pengguna        = Kendaraan::select('pengguna')->groupBy('pengguna')->get();
        $kendaraan       = Kendaraan::orderBy('jenis_aadb', 'ASC')
            ->join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->get();
        $stnk      = Kendaraan::join('aadb_tbl_jenis_kendaraan', 'id_jenis_kendaraan', 'jenis_kendaraan_id')
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
            ->get();

        return view('v_user.apk_aadb.index', compact(
            'unitKerja', 'jenisKendaraan', 'merk','tahun','pengguna','googleChartData','kendaraan','usulan','stnk'
        ));
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
                    ->get();
            } else {
                $kendaraan  = Kendaraan::select('id_kendaraan', DB::raw('CONCAT(no_plat_kendaraan," / ",merk_tipe_kendaraan, " / ", pengguna) AS nama_kendaraan'))
                    ->orderby('nama_kendaraan', 'asc')
                    ->where('merk_tipe_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('no_plat_kendaraan', 'like', '%' . $search . '%')
                    ->orWhere('pengguna', 'like', '%' . $search . '%')
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
        $usulan = UsulanAtk::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            // ->where('pegawai_id', Auth::user()->pegawai_id)
            ->get();
        $googleChartData = $this->ChartDataAtk();

        return view('v_user.apk_atk.index', compact('googleChartData', 'usulan'));
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
                ->get();

            return view('v_user.apk_atk.daftar_pengajuan', compact('usulan'));
        } elseif ($aksi == 'proses') {
            $idFormUsulan = Carbon::now()->format('dmy') . $request->id_usulan;
            $usulan = new UsulanAtk();
            $usulan->id_form_usulan     = $idFormUsulan;
            $usulan->pegawai_id         = Auth::user()->pegawai_id;
            $usulan->jenis_form         = $id;
            $usulan->total_pengajuan    = $request->total_pengajuan;
            $usulan->no_surat_usulan    = $request->no_surat_usulan;
            $usulan->tanggal_usulan     = $request->tanggal_usulan;
            $usulan->rencana_pengguna   = $request->rencana_pengguna;
            $usulan->save();

            $atk   = $request->atk_id;
            foreach ($atk as $i => $atk_id) {
                $idDetail = UsulanAtkDetail::count();
                $detail = new UsulanAtkDetail();
                $detail->id_form_usulan_detail = $idDetail + 1;
                $detail->form_usulan_id        = $idFormUsulan;
                $detail->atk_id                = $atk_id;
                $detail->jumlah_pengajuan      = $request->jumlah[$i];
                $detail->satuan                = $request->satuan[$i];
                $detail->keterangan            = $request->keterangan[$i];
                $detail->save();
            }
            return redirect('unit-kerja/verif/usulan-atk/' . $idFormUsulan);
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
        $dataAtk = SubKelompokAtk::join('atk_tbl_kelompok_sub_jenis', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->join('atk_tbl_kelompok_sub_kategori', 'id_jenis_atk', 'jenis_atk_id')
            ->join('atk_tbl', 'id_kategori_atk', 'kategori_atk_id');

        $dataChart['atk'] = $dataAtk->get();
        $stok = $dataAtk->select(DB::raw('sum(total_atk) as stok'))->groupBy('total_atk');
        $dataJenisAtk = KategoriAtk::get();
        foreach ($dataJenisAtk as $data) {
            $dataArray[] = $data->kategori_atk;
            $totalStok =  $stok->where('id_kategori_atk', $data->id_kategori_atk)->get();
            $dataArray[] = $totalStok[0]->stok;
            $dataChart['all'][] = $dataArray;
            unset($dataArray);
        }


        $chart = json_encode($dataChart);
        // dd($chart);
        return $chart;
    }

    public function SearchChartDataAtk(Request $request)
    {
        $dataAtk = SubKelompokAtk::join('atk_tbl_kelompok_sub_jenis', 'id_subkelompok_atk', 'subkelompok_atk_id')
            ->join('atk_tbl_kelompok_sub_kategori', 'id_jenis_atk', 'jenis_atk_id')
            ->join('atk_tbl', 'id_kategori_atk', 'kategori_atk_id');

        if ($request->hasAny(['kategori', 'jenis', 'nama', 'merk'])) {
            if ($request->kategori) {
                $dataSearchAtk = $dataAtk->where('id_subkelompok_atk', $request->kategori);
            }
            if ($request->jenis) {
                $dataSearchAtk = $dataAtk->where('id_jenis_atk', $request->jenis);
            }
            if ($request->nama) {
                $dataSearchAtk = $dataAtk->where('id_kategori_atk', $request->nama);
            }
            if ($request->merk) {
                $dataSearchAtk = $dataAtk->where('id_atk', $request->merk);
            }

            $resultSearchAtk = $dataSearchAtk->get();
            // dd($resultSearchAtk);
        } else {
            $resultSearchAtk = $dataAtk->get();
        }

        foreach ($resultSearchAtk as $data) {
            $stok = $dataAtk->select(DB::raw('sum(total_atk) as stok'))->groupBy('total_atk');
            $totalStok =  $stok->where('id_kategori_atk', $data->id_kategori_atk)->get();
            $dataArray[] = $data->kategori_atk;
            $dataArray[] = $totalStok[0]->stok;
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
