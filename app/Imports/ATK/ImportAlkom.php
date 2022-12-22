<?php

namespace App\Imports\ATK;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportAlkom implements ToModel, WithStartRow
{
    public function collection(Collection $rows)
    {

    }

    public function model(array $row)
    {
        dd($row);
        // $idKondisiBarang  = KondisiBarang::select('id_kondisi_barang')->where('kondisi_barang','like', '%'. $row[7] . '%')->first();

        // Barang::where('id_barang', $row[0])->update([
        //     'nup_barang'        => $row[3],
        //     'merk_tipe_barang'  => $row[4],
        //     'nilai_perolehan'   => $row[5],
        //     'pengguna_barang'   => $row[8]
        // ]);
    }

    public function startRow(): int
    {
        return 4;
    }
}
