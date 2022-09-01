<?php

namespace App\Imports;

use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UnitKerjaImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $data    = UnitKerja::where('id_unit_kerja', $row[0])->first();
        if ($data == '') {
            UnitKerja::create([
                'id_unit_kerja'  => $row[0],
                'unit_utama_id'  => $row[1],
                'unit_kerja'     => $row[2]
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
