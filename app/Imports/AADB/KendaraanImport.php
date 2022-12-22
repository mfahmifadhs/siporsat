<?php

namespace App\Imports\AADB;

use App\Models\AADB\JenisKendaraan;
use App\Models\AADB\Kendaraan;
use App\Models\AADB\KendaraanSewa;
use App\Models\AADB\KondisiKendaraan;
use App\Models\AADB\RiwayatKendaraan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class KendaraanImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dump($row[7]);
        $idKendaraan        = random_int(1000000, 9999999);
        // $idKondisiKendaraan = KondisiKendaraan::select('id_kondisi_kendaraan')->where('kondisi_kendaraan','like', '%'. $row[13] . '%')->first();
        // $idJenisKendaraan   = JenisKendaraan::select('id_jenis_kendaraan')->where('jenis_kendaraan','like', '%'. $row[2] . '%')->first();

        // Kendaraan::where('id_kendaraan', $row[1])->update([
        //     'merk_tipe_kendaraan'       => $row[3],
        //     'tahun_kendaraan'           => $row[4],
        //     'nup_barang'                => $row[5],
        //     'no_plat_kendaraan'         => $row[6],
        //     'mb_stnk_plat_kendaraan'    => $row[7],
        //     'no_bpkb'                   => $row[8],
        //     'no_rangka'                 => $row[9],
        //     'no_mesin'                  => $row[10],
        //     'pengguna'                  => $row[11]
        // ]);

    }

    public function startRow(): int
    {
        return 3    ;
    }
}
