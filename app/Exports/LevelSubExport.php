<?php

namespace App\Exports;

use App\Models\LevelSub;
use App\Models\Level;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LevelSubExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return LevelSub::select('id_sub_level','aplikasi','level','tim_kerja')
        ->join('tbl_aplikasi','tbl_aplikasi.id_aplikasi','tbl_level_sub.aplikasi_id')
        ->join('tbl_level','tbl_level.id_level','tbl_level_sub.level_id')
        ->join('tbl_tim_kerja','tbl_tim_kerja.id_tim_kerja','tbl_level_sub.tim_kerja_id')
        ->get();
    }

    public function headings(): array
    {
        return ["No", "Aplikasi", "Level", "Tim Kerja"];
    }
}
