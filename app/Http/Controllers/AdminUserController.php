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
use App\Models\User;
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
    //                        Rumah Dinas
    // ====================================================

    public function Rdn(Request $request, $aksi)
    {
        return view('v_admin_user.apk_rdn.index');
    }

    public function OfficialResidence(Request $request, $aksi, $id)
    {
        if ($aksi == 'daftar') {
            $rumah = RumahDinas::join('rdn_tbl_kondisi_rumah', 'id_kondisi_rumah','kondisi_rumah_id')->get();

            return view('v_admin_user.apk_rdn.daftar_rumah', compact('rumah'));

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
}
