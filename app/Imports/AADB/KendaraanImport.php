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
        $idKendaraan        = random_int(1000000, 9999999);
        $idKondisiKendaraan = KondisiKendaraan::select('id_kondisi_kendaraan')->where('kondisi_kendaraan','like', '%'. $row[13] . '%')->first();
        $idJenisKendaraan   = JenisKendaraan::select('id_jenis_kendaraan')->where('jenis_kendaraan','like', '%'. $row[2] . '%')->first();
        $data               = Kendaraan::where('no_plat_kendaraan', $row[5])->first();
        if ($data == '' && $row[5] != null) {
            Kendaraan::create([
                'id_kendaraan'              => $idKendaraan,
                'jenis_aadb'                => $row[0],
                'kode_barang'               => $row[1],
                'jenis_kendaraan_id'        => $idJenisKendaraan->id_jenis_kendaraan,
                'merk_kendaraan'            => $row[3],
                'tipe_kendaraan'            => $row[4],
                'no_plat_kendaraan'         => $row[5],
                'mb_stnk_plat_kendaraan'    => Carbon::parse($row[6])->format('Y-m-d'),
                'no_plat_rhs'               => $row[7],
                'mb_stnk_plat_rhs'          => Carbon::parse($row[8])->format('Y-m-d'),
                'no_bpkb'                   => $row[9],
                'no_rangka'                 => $row[10],
                'no_mesin'                  => $row[11],
                'tahun_kendaraan'           => $row[12],
                'kondisi_kendaraan_id'      => $idKondisiKendaraan->id_kondisi_kendaraan,
                'pengguna'                  => $row[14],
                'jabatan'                   => $row[15],
                'pengemudi'                 => $row[16]
            ]);
        }
        $cekRiwayat     = RiwayatKendaraan::count();
        RiwayatKendaraan::create([
            'id_riwayat_kendaraan'          => $cekRiwayat +1,
            'kendaraan_id'                  => $idKendaraan,
            'tanggal_pengguna'              => Carbon::now(),
            'pengguna'                      => $row[14],
            'jabatan'                       => $row[15],
            'pengemudi'                     => $row[16]
        ]);

        $cekSewa        = KendaraanSewa::count();
        if ($row[0] == 'sewa') {
            KendaraanSewa::create([
                'id_kendaraan_sewa'             => $cekSewa + 1,
                'kendaraan_id'                  => $idKendaraan,
                'mulai_sewa'                    => $row[17]
            ]);
        }

    }

    public function startRow(): int
    {
        return 2;
    }
}
