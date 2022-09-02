<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\KondisiBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BarangImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $idKategoriBarang = KategoriBarang::select('id_kategori_barang')->where('kategori_barang','like', '%'. $row[2] . '%')->first();
        $idKondisiBarang  = KondisiBarang::select('id_kondisi_barang')->where('kondisi_barang','like', '%'. $row[9] . '%')->first();
        $data             = Barang::where('kode_barang', $row[1])->where('nup_barang', $row[4])->first();
        if ($data == '' && $row[1] != null) {
            Barang::create([
                'id_barang'             => random_int(100000, 999999),
                'kategori_barang_id'    => $idKategoriBarang->id_kategori_barang,
                'kode_barang'           => $row[1],
                'nup_barang'            => $row[4],
                'spesifikasi_barang'    => $row[5],
                'jumlah_barang'         => $row[6],
                'satuan_barang'         => $row[7],
                'kondisi_barang_id'     => $idKondisiBarang->id_kondisi_barang,
                'nilai_perolehan'       => $row[8],
                'tahun_perolehan'       => $row[3]
            ]);
        }
    }

    public function startRow(): int
    {
        return 5;
    }
}
