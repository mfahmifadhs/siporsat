<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PegawaiExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        return Pegawai::select('id_pegawai','nip_pegawai','nama_pegawai','nohp_pegawai','jabatan','tim_kerja','unit_kerja','keterangan_pegawai')
                ->leftjoin('tbl_pegawai_jabatan', 'tbl_pegawai_jabatan.id_jabatan', 'tbl_pegawai.jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'tbl_tim_kerja.id_tim_kerja', 'tbl_pegawai.tim_kerja_id')
                ->leftjoin('tbl_unit_kerja', 'tbl_unit_kerja.id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->get();
    }

    public function headings(): array
    {
        return ["No", "NIP", "Nama Pegawai", "No Hp", "Jabatan", "Tim Kerja", "Unit Kerja","Keterangan Posisi"];
    }
}
