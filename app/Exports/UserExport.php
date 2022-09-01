<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Level;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return User::select('id','level','unit_kerja','nama','jabatan','username','password_teks')
        ->join('tbl_level','tbl_level.id_level','users.level_id')
        ->join('tbl_unit_kerja','tbl_unit_kerja.id_unit_kerja','users.unit_kerja_id')->get();
    }

    public function headings(): array
    {
        return ["No", "Level", "Unit Kerja", "Nama", "Jabatan", "Username", "Password"];
    }
}
