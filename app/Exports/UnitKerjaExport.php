<?php

namespace App\Exports;

use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnitKerjaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return UnitKerja::select('id_unit_kerja','unit_utama','unit_kerja')
        ->join('tbl_unit_utama','tbl_unit_utama.id_unit_utama','tbl_unit_kerja.unit_utama_id')
        ->get();
    }

    public function headings(): array
    {
        return ["No", "Unit Utama", "Unit Kerja"];
    }
}
