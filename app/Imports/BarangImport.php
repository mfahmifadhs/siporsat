<?php

namespace App\Imports;

use App\Models\OLDAT\Barang;
use App\Models\OLDAT\KategoriBarang;
use App\Models\OLDAT\KondisiBarang;
use Carbon\Carbon;
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
        $idKondisiBarang  = KondisiBarang::select('id_kondisi_barang')->where('kondisi_barang','like', '%'. $row[7] . '%')->first();

        Barang::where('id_barang', $row[0])->update([
            'nup_barang'        => $row[3],
            'merk_tipe_barang'  => $row[4],
            'nilai_perolehan'   => $row[5],
            'pengguna_barang'   => $row[8]
        ]);
    }

    public function startRow(): int
    {
        return 3;
    }
}
