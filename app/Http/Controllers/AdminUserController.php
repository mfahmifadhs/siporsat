<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\BarangExport;
use App\Imports\AADB\KendaraanImport;
use App\Models\AADB\Kendaraan;
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
use App\Models\atk\SubKelompokATK;
use App\Models\RDN\RumahDinas;
use Carbon\Carbon;
use Validator;
use Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('v_admin_user.index');
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
        } elseif ($aksi == 'tambah') {
            return view('v_admin_user.apk_atk.tambah_atk');
        }
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
            $kendaraan = Kendaraan::where('id_kendaraan', $id)
                ->join('aadb_tbl_jenis_kendaraan','id_jenis_kendaraan','jenis_kendaraan_id')
                ->first();

            $pengguna = RiwayatKendaraan::where('kendaraan_id', $id)->get();

            return view('v_admin_user.apk_aadb.detail_kendaraan', compact('kendaraan','pengguna'));

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
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::get();
            $barang         = Barang::join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
                ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->orderBy('tahun_perolehan','DESC')
                ->get();

            return view('v_admin_user.apk_oldat.daftar_barang', compact('kategoriBarang', 'kondisiBarang','pegawai', 'barang'));

        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai','ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
                ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
                ->where('id_barang', $id)->first();

            $riwayat        = RiwayatBarang::join('oldat_tbl_barang','id_barang','barang_id')
                ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_riwayat_barang.kondisi_barang_id')
                ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                ->join('tbl_pegawai','tbl_pegawai.id_pegawai','oldat_tbl_riwayat_barang.pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan','id_jabatan','jabatan_id')
                ->leftjoin('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_pegawai.unit_kerja_id')
                ->where('barang_id', $id)->get();

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
                if($request->foto_barang = null) {
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

            $cekBarang = RiwayatBarang::count();
            $riwayat   = new RiwayatBarang();
            $riwayat->id_riwayat_barang = $cekBarang + 1;
            $riwayat->barang_id         = $id;
            $riwayat->pegawai_id        = $request->input('id_pegawai');
            $riwayat->tanggal_pengguna  = Carbon::now();
            $riwayat->kondisi_barang_id = $request->input('id_kondisi_barang');
            $riwayat->save();

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
