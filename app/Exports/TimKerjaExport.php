<?php

namespace App\Exports;

use App\Models\Timkerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimKerjaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Timkerja::select('id_tim_kerja','unit_kerja','tim_kerja')
        ->join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','tbl_tim_kerja.unit_kerja_id')
        ->get();
    }

    public function headings(): array
    {
        return ["No", "Unit Kerja", "Tim Kerja"];
    }
}
