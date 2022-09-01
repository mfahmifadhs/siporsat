<?php

namespace App\Imports;

use App\Models\TimKerja;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TimKerjaImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $data    = TimKerja::where('id_tim_kerja', $row[0])->first();
        if ($data == '') {
            TimKerja::create([
                'id_tim_kerja'   => $row[0],
                'unit_kerja_id'  => $row[1],
                'tim_kerja'      => $row[2]
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
