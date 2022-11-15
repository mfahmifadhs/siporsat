<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\BarangExport;
use App\Imports\AADB\KendaraanImport;
use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\KondisiKendaraan;
use App\Models\AADB\RiwayatKendaraan;
use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use App\Models\OLDAT\RiwayatBarang;
use App\Models\Pegawai;
use App\Models\RDN\KondisiRumah;
use App\Models\RDN\PenghuniRumah;
use App\Models\atk\ATK;
use App\Models\atk\JenisATK;
use App\Models\atk\KategoriATK;
use App\Models\atk\KelompokATK;
use App\Models\atk\StokAtk;
use App\Models\atk\SubKelompokATK;
use App\Models\atk\UsulanAtk;
use App\Models\atk\UsulanAtkDetail;
use App\Models\atk\UsulanAtkLampiran;
use App\Models\RDN\RumahDinas;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Auth;
use Google2FA;

class AdminUserController extends Controller
{
    public function index()
    {
        $atk = UsulanAtk::join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan','id_status_pengajuan','status_pengajuan_id')
                ->join('tbl_status_proses','id_status_proses','status_proses_id')
                ->get();
        return view('v_admin_user.index', compact('atk'));
    }

    public function Profile(Request $request,$aksi, $id)
    {
        $user = User::where('id', Auth::user()->id)
            ->join('tbl_level','id_level','level_id')
            ->join('tbl_pegawai','id_pegawai','pegawai_id')
            ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
            ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
            ->join('tbl_unit_kerja','id_unit_kerja','tbl_pegawai.unit_kerja_id')
            ->first();

        if ($aksi == 'user') {
            $google2fa  = app('pragmarx.google2fa');
            $secretkey  = $google2fa->generateSecretKey();
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $registration_data = Auth::user()->username,
                $registration_data = $secretkey
            );

            return view('v_admin_user.profil', compact('user', 'QR_Image','secretkey'));
        } elseif ($aksi == 'reset-autentikasi') {

            User::where('id', $id)->update(['status_google2fa' => null]);
            return redirect ('admin-user/profil/user/'. Auth::user()->id)->with('success', 'Berhasil mereset autentikasi 2fa');

        } else {
            User::where('id',$id)->first();
            User::where('id',$id)->update([
                'google2fa_secret' => encrypt($request->secretkey),
                'status_google2fa' => 1
            ]);

            return redirect('admin-user/dashboard');
        }
    }

    public function Verification(Request $request, $aksi, $id)
    {
        if ($id == 'cek')
        {
            if (Auth::user()->sess_modul == 'atk')
            {
                $usulan = UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->first();
                if ($usulan->status_proses_id == 4) {
                    UsulanAtk::where('id_form_usulan', Auth::user()->sess_form_id)->update([
                        'otp_bast_pengusul' => $request->one_time_password,
                        'status_proses_id'  => 5
                    ]);
                    Google2FA::logout();

                    return redirect('admin-user/atk/surat/surat-bast/'. Auth::user()->sess_form_id);
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
            $rumah      = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')->get();
            return view('v_admin_user.apk_rdn.daftar_rumah', compact('rumah'));

        }elseif ($aksi == 'detail') {
            $pegawai  = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')->get();
            $penghuni = PenghuniRumah::leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                ->where('rumah_dinas_id', $id)
                ->orderBy('id_penghuni','DESC')
                ->first();
            $rumah    = RumahDinas::where('id_rumah_dinas', $id)
                ->join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')
                ->first();
            $kondisi  = KondisiRumah::get();

            return view('v_admin_user.apk_rdn.detail_rumah', compact('pegawai','rumah','penghuni','kondisi'));
        }elseif ($aksi == 'proses-ubah') {
            $cekFoto  = Validator::make($request->all(), [
                'foto_rumah'    => 'mimes: jpg,png,jpeg|max:4096',
            ]);

            if ($cekFoto->fails()) {
                return redirect('admin-user/rdn/rumah-dinas/detail/'. $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            }else{
                if($request->foto_rumah == null) {
                    $fotoRumah = $request->foto_lama;
                } else {
                    $dataRumah = RumahDinas::where('id_rumah_dinas', $id)->first();

                    if ($request->hasfile('foto_rumah')){
                        if($dataRumah->foto_rumah != ''  && $dataRumah->foto_rumah != null){
                            $file_old = public_path().'\gambar\rumah_dinas\\' . $dataRumah->foto_rumah;
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
                foreach($statusPenghuni as $dataPenghuni)
                {
                    if ($dataPenghuni->status_penghuni == 1) {
                        PenghuniRumah::where('rumah_dinas_id', $id)->update([ 'status_penghuni' => 2 ]);
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

            return redirect('admin-user/rdn/rumah-dinas/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Rumah Dinas');
        }
    }

    // ===============================================
    //             ALAT TULIS KANTOR (ATK)
    // ===============================================

    public function OfficeStationery(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kategoriAtk    = KategoriATK::get();
            $jenisAtk       = JenisATK::get();
            $subkelompokAtk = SubKelompokATK::get();
            $kelompokAtk    = KelompokATK::get();
            $atk = ATK::get();
            return view('v_admin_user.apk_atk.daftar_atk', compact('kategoriAtk','jenisAtk','subkelompokAtk','kelompokAtk','atk'));
        } elseif ($aksi == 'tambah-atk') {
            $kategori = 'atk';
            return view('v_admin_user.apk_atk.tambah_atk', compact('kategori', 'id'));
        } elseif ($aksi == 'proses-tambah-barang') {
            $kategori   = $request->barang;
            foreach ($kategori as $i => $kategoriAtk)
            {
                $total      = KategoriATK::where('jenis_atk_id', $request->jenis_atk)->count();
                $idKategori = $request->jenis_atk.str_pad($total + 1, 4, 0, STR_PAD_LEFT);
                $kategori   = new KategoriATK();
                $kategori->id_kategori_atk = $idKategori;
                $kategori->jenis_atk_id    = $request->jenis_atk;
                $kategori->kategori_atk    = strtoupper($request->barang[$i]);
                $kategori->save();
            }
            return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success','Berhasil menambah barang');

        } elseif ($aksi == 'proses-tambah-detail') {
            $kategori   = $request->barang;
            foreach ($kategori as $i => $kategoriAtk)
            {
                $total  = ATK::where('kategori_atk_id', $request->kategori_atk)->count();
                $idAtk  = $request->kategori_atk.str_pad($total + 1, 5, 0, STR_PAD_LEFT);
                $atk   = new ATK();
                $atk->id_atk           = $idAtk;
                $atk->kategori_atk_id  = $request->kategori_atk;
                $atk->merk_atk         = strtoupper($request->barang[$i]);
                $atk->total_atk        = 0;
                $atk->satuan           = strtoupper($request->satuan[$i]);
                $atk->save();
            }
            return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success','Berhasil menambah barang');
        } elseif ($aksi == 'detail-kategori') {
            $kategoriAtk    = KategoriATK::get();
            $jenisAtk       = JenisATK::get();
            $subkelompokAtk = SubKelompokATK::get();
            $kelompokAtk    = KelompokATK::get();
            return view('v_admin_user.apk_atk.kategori_atk', compact('id','kelompokAtk','subkelompokAtk','jenisAtk','kategoriAtk'));
        } elseif ($aksi == 'edit-atk') {
            if ($request->atk == 'merk_atk') {
                ATK::where('id_atk', $request->id_atk)->update([ 'merk_atk' => strtoupper($request->merk_atk) ]);
                return redirect('admin-user/atk/barang/daftar/seluruh-barang')->with('success', 'Berhasil mengubah informasi barang');
            } elseif ($request->atk == 'kategori_atk') {
                KategoriATK::where('id_kategori_atk', $request->id_kategori_atk)->update([ 'kategori_atk' => strtoupper($request->kategori_atk) ]);
                return redirect('admin-user/atk/barang/detail-kategori/kategori')->with('success', 'Berhasil mengubah informasi kategori barang');
            } elseif ($request->atk == 'jenis_atk') {
                JenisATK::where('id_jenis_atk', $request->id_jenis_atk)->update([ 'jenis_atk' => strtoupper($request->jenis_atk) ]);
                return redirect('admin-user/atk/barang/detail-kategori/jenis')->with('success', 'Berhasil mengubah informasi jenis barang');
            } elseif ($request->atk == 'subkelompok_atk') {
                SubKelompokATK::where('id_subkelompok_atk', $request->id_subkelompok_atk)->update([ 'subkelompok_atk' => strtoupper($request->subkelompok_atk) ]);
                return redirect('admin-user/atk/barang/detail-kategori/kelompok')->with('success', 'Berhasil mengubah informasi kelompok barang');
            }
        }
    }

    public function LetterAtk(Request $request, $aksi, $id)
    {
        if ($aksi == 'surat-usulan') {

            if(Auth::user()->pegawai->unit_kerja_id == 465930) {
                $ppk = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '5')->where('unit_kerja_id',465930)->first();
            } else {
                $ppk = null;
            }

            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();

            return view('v_admin_user/apk_atk/surat_usulan', compact('ppk','usulan'));

        } elseif ($aksi == 'surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 465930) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',465930)->first();
            } else {
                $pimpinan = null;
            }

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->join('tbl_unit_utama','id_unit_utama','unit_utama_id')
                ->first();

            return view('v_admin_user/apk_atk/surat_bast', compact('pimpinan','bast','id'));

        } elseif ($aksi == 'print-surat-usulan') {
            if(Auth::user()->pegawai->unit_kerja_id == 465930) {
                $ppk = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '5')->where('unit_kerja_id',465930)->first();
            } else {
                $ppk = null;
            }

            $usulan = UsulanAtk::where('otp_usulan_pengusul', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->get();

            return view('v_admin_user/apk_atk/print_surat_usulan', compact('ppk','usulan'));


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
                $pengadaanBaru->jenis_kendaraan_id      = $request->jenis_kendaraan_id ;
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

                if($request->jenis_aadb == 'sewa') {
                    $cekPengadaanSewa = KendaraanSewa::count();
                    $pengadaanSewa  = new KendaraanSewa();
                    $pengadaanSewa->id_kendaraan_sewa = $cekPengadaanSewa + 1;
                    $pengadaanSewa->kendaraan_id = $request->id_kendaraan;
                    $pengadaanSewa->mulai_sewa   = $request->mulai_sewa;
                    $pengadaanSewa->penyedia     = $request->penyedia;
                    $pengadaanSewa->save();
                }

                UsulanAadb::where('id_form_usulan', $id)->update([ 'status_proses' => 'selesai' ]);

            } elseif ($cekForm->jenis_form == 2) {

            } elseif ($cekForm->jenis_form == 3) {

            } else {

            }

            return redirect('super-user/aadb/usulan/bast/'. $id);

        } elseif ($aksi == 'print-surat-bast') {
            if(Auth::user()->pegawai->unit_kerja_id == 465930) {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                    ->where('jabatan_id', '2')->where('unit_kerja_id',465930)->first();
            } else {
                $pimpinan = null;
            }

            $bast = UsulanAtk::where('id_form_usulan', $id)
                ->join('aadb_tbl_jenis_form_usulan','id_jenis_form_usulan','jenis_form')
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->join('tbl_unit_utama','id_unit_utama','unit_utama_id')
                ->first();

            return view('v_admin_user/apk_atk/print_surat_bast', compact('pimpinan','bast','id'));

        }
    }

    public function SubmissionAtk(Request $request, $aksi, $id)
    {
        if($aksi == 'input') {
            $totalUsulan  = UsulanAtk::where('no_surat_bast','!=', null)->count();
            $idBast       = str_pad($totalUsulan + 1, 4, 0, STR_PAD_LEFT);
            $usulan = UsulanAtk::where('id_form_usulan', $id)
                ->join('tbl_pegawai','id_pegawai','pegawai_id')
                ->join('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->leftjoin('tbl_status_pengajuan','id_status_pengajuan','status_pengajuan_id')
                ->join('tbl_status_proses','id_status_proses','status_proses_id')
                ->first();

            return view('v_admin_user.apk_atk.pgudang_input', compact('id','idBast', 'usulan'));
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
            foreach ($atk as $i => $atk_id)
            {
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
            return redirect('admin-user/verif/usulan-atk/'. $request->form_id);
            // return redirect('admin-user/atk/surat/surat-bast/'. $id)->with('success', 'Pembelian barang telah selesai dilakukan');

        } elseif ($aksi == 'proses-input' && $id == 'distribusi') {
            // Update form usulan
            UsulanAtk::where('id_form_usulan', $request->form_id)->update([
                'no_surat_bast'     => $request->no_surat_bast,
                'tanggal_bast'      => Carbon::now()
            ]);

            $atk = $request->atk_id;
            foreach ($atk as $i => $atk_id)
            {
                // Riwayat stok barang
                $totalStok  = StokAtk::count();
                $idStok     = str_pad($totalStok + ($i + 1), 6, 0, STR_PAD_LEFT);
                $stok       = new StokAtk();
                $stok->id_stok        = $idStok;
                $stok->atk_id         = $atk_id;
                $stok->form_usulan_id = $request->form_id;
                $stok->stok_atk       = $request->jumlah[$i];
                $stok->satuan         = $request->satuan[$i];
                $stok->save();

                // Update Stok Barang
                $stokAtk = Atk::where('id_atk', $atk_id)->first();
                Atk::where('id_atk', $atk_id)->update([
                    'total_atk' => $stokAtk->total_atk - $request->jumlah[$i]
                ]);
            }
            return redirect('admin-user/verif/usulan-atk/'. $request->form_id);
            // return redirect('admin-user/atk/surat/surat-bast/'. $id)->with('success', 'Pembelian barang telah selesai dilakukan');

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
            $idAtk         = array($id.str_pad($totalKategori + 1, 5, 0, STR_PAD_LEFT));
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

    // ====================================================
    //                        AADB
    // ====================================================

    public function Aadb(Request $request, $aksi)
    {
        return view('v_admin_user.apk_aadb.index');
    }

    public function Vehicle(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $kendaraan = Kendaraan::join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->join('aadb_tbl_kondisi_kendaraan','id_kondisi_kendaraan','kondisi_kendaraan_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->orderBy('jenis_aadb','ASC')
                ->get();

            return view('v_admin_user.apk_aadb.daftar_kendaraan', compact('kendaraan'));

        } elseif ($aksi == 'detail') {
            $unitKerja = UnitKerja::get();
            $jenis     = JenisKendaraan::get();
            $kondisi   = KondisiKendaraan::get();
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->first();

            return view('v_admin_user.apk_aadb.detail_kendaraan', compact('unitKerja','jenis','kondisi','kendaraan'));

        } elseif ($aksi == 'upload') {
            Excel::import(new KendaraanImport(), $request->upload);
            return redirect('admin-user/aadb/kendaraan/')->with('success', 'Berhasil Mengupload Data Kendaraan');
        }
    }

    // ====================================================
    //                        OLDAT
    // ====================================================

    public function showItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
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
            return view('v_admin_user.apk_oldat.daftar_barang', compact('barang'));

        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai','ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang','like', $id)
                ->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang','id_barang','barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->join('tbl_pegawai','tbl_pegawai.id_pegawai','oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->where('barang_id', $id)
                ->get();

            return view('v_admin_user.apk_oldat.detail_barang', compact('kategoriBarang','kondisiBarang','pegawai','barang','riwayat'));

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
                return redirect('admin-user/oldat/barang/detail/'. $id)->with('failed', 'Format foto tidak sesuai, mohon cek kembali');
            }else{
                if($request->foto_barang == null) {
                    $fotoBarang = $request->foto_lama;
                } else {
                    $dataBarang = Barang::where('id_barang', $id)->first();

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

            return redirect('admin-user/oldat/barang/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Barang');

        } elseif ($aksi == 'ubah-riwayat') {
            RiwayatBarang::where('id_riwayat_barang', $request->id_riwayat_barang)->update([
                'tanggal_pengguna'     => $request->tanggal_pengguna,
                'keperluan_penggunaan' => $request->keperluan_penggunaan
            ]);

            return redirect('admin-user/oldat/barang/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Barang');

        } elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
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
        $result   = Pegawai::where('id_pegawai', $request->penghuni)->pluck('keterangan_pegawai','id_pegawai');
        return response()->json($result);
    }


}
