<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\BarangExport;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use App\Models\Pegawai;
use App\Models\RiwayatBarang;

use Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('v_admin_user.index');
    }

    // ====================================================
    //                    BARANG
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
            ->get();
            return view('v_admin_user.apk_oldat.daftar_barang', compact('kategoriBarang', 'kondisiBarang','pegawai', 'barang'));
        } elseif ($aksi == 'detail') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $pegawai        = Pegawai::orderBy('nama_pegawai','ASC')->get();
            $barang         = Barang::join('oldat_tbl_kategori_barang', 'oldat_tbl_kategori_barang.id_kategori_barang', 'oldat_tbl_barang.kategori_barang_id')
            ->leftjoin('tbl_pegawai', 'tbl_pegawai.id_pegawai', 'oldat_tbl_barang.pegawai_id')
            ->where('id_barang', $id)->first();
            return view('v_admin_user.apk_oldat.detail_barang', compact('kategoriBarang','kondisiBarang','pegawai','barang'));
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
            $riwayat->kondisi_barang_id = $request->input('id_kondisi_barang');
            $riwayat->save();

            return redirect('admin-user/oldat/barang/detail/'. $id)->with('success', 'Berhasil Mengubah Informasi Barang');
        }elseif ($aksi == 'download') {
            return Excel::download(new BarangExport(), 'data_pengadaan_barang.xlsx');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('admin-user/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    // ====================================================
    //                 KATEGORI BARANG
    // ====================================================

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
