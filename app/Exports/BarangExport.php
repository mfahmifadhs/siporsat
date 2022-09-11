<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\KondisiBarang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\TimKerja;
use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BarangExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Barang::select('kode_barang','nup_barang','kategori_barang','spesifikasi_barang','jumlah_barang','satuan_barang',
                               'kondisi_barang','nama_pegawai','tim_kerja','unit_kerja')
                        ->join('oldat_tbl_kategori_barang','id_kategori_barang','kategori_barang_id')
                        ->join('oldat_tbl_kondisi_barang','id_kondisi_barang','kondisi_barang_id')
                        ->join('tbl_unit_kerja','id_unit_kerja','unit_kerja_id')
                        ->leftjoin('tbl_pegawai','id_pegawai','pegawai_id')
                        ->leftjoin('tbl_tim_kerja','id_tim_kerja','tim_kerja_id')
                        ->get();
    }

    public function headings(): array
    {
        return ["Kode Barang", "NUP", "Nama Barang", "Spesifikasi", "Jumlah", "Satuan", "Kondisi","Pengguna","Tim Kerja","Unit Kerja"];
    }
}
