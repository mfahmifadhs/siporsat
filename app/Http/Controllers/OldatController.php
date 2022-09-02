<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Imports\BarangImport;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OldatController extends Controller
{
    public function index()
    {
        return view('v_super_admin.apk_oldat.index');
    }

    // ====================================================
    //                    BARANG
    // ====================================================

    public function showItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $kategoriBarang = KategoriBarang::get();
            $kondisiBarang  = KondisiBarang::get();
            $barang         = Barang::join('oldat_tbl_kategori_barang','oldat_tbl_kategori_barang.id_kategori_barang','oldat_tbl_barang.kategori_barang_id')
            ->join('oldat_tbl_kondisi_barang','oldat_tbl_kondisi_barang.id_kondisi_barang','oldat_tbl_barang.kondisi_barang_id')
            ->get();
            return view('v_super_admin.apk_oldat.daftar_barang', compact('kategoriBarang', 'kondisiBarang', 'barang'));
        } elseif ($aksi == 'upload') {
            Excel::import(new BarangImport(), $request->upload);
            return redirect('super-admin/oldat/barang/data/semua')->with('success', 'Berhasil Mengupload Data Barang');
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id)->update([
                'kategori_barang' => strtolower($request->kategori_barang)
            ]);
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Mengubah Kategori Barang');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }

    // ====================================================
    //                 KATEGORI BARANG
    // ====================================================

    public function showCategoryItem(Request $request, $aksi, $id)
    {
        if ($aksi == 'data') {
            $kategoriBarang = KategoriBarang::get();
            return view('v_super_admin.apk_oldat.daftar_kategori_barang', compact('kategoriBarang'));
        } elseif ($aksi == 'proses-tambah') {
            $cekData        = KategoriBarang::get()->count();
            $kategoriBarang = new KategoriBarang();
            $kategoriBarang->id_kategori_barang   = $cekData + 1;
            $kategoriBarang->kategori_barang      = strtolower($request->input('kategori_barang'));
            $kategoriBarang->save();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menambahkan Kategori Barang');
        } elseif ($aksi == 'proses-ubah') {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id)->update([
                'kategori_barang' => strtolower($request->kategori_barang)
            ]);
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Mengubah Kategori Barang');
        } else {
            $kategoriBarang = KategoriBarang::where('id_kategori_barang', $id);
            $kategoriBarang->delete();
            return redirect('super-admin/oldat/kategori-barang/data/semua')->with('success', 'Berhasil Menghapus Kategori Barang');
        }
    }
}
