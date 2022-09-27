<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\PegawaiJabatan;
use App\Models\TimKerja;
use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PegawaiImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        if ($row[5] != null || $row[6] != null) {
            $timKerja    = TimKerja::select('id_tim_kerja')->where('tim_kerja','like', '%'. $row[5] . '%')->first();
            $unitKerja   = UnitKerja::select('id_unit_kerja')->where('unit_kerja','like', '%'. $row[6] . '%')->first();
            $idTimKerja  = $timKerja->id_tim_kerja;
            $idUnitKerja = $timKerja->id_unit_kerja;
        } else {
            $idTimKerja  = $row[5];
            $idUnitKerja = $row[6];
        }

        $data        = Pegawai::where('id_pegawai', $row[0])->first();

        if ($data == '' && $row[0] != null) {
            Pegawai::create([
                'id_pegawai'            => $row[0],
                'nip_pegawai'           => $row[1],
                'nama_pegawai'          => $row[2],
                'nohp_pegawai'          => $row[3],
                'jabatan_id'            => $row[4],
                'tim_kerja_id'          => $idTimKerja,
                'unit_kerja_id'         => $idUnitKerja,
                'keterangan_pegawai'    => $row[7]
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
