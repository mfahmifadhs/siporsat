<?php

namespace App\Exports;

use App\Models\ATK\UsulanAtkPengadaan;
use App\Models\Barang;
use App\Models\KondisiBarang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use App\Models\TimKerja;
use App\Models\UnitKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AtkExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $id;

    function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return UsulanAtkPengadaan::select(
            'unit_kerja',
            'tanggal',
            'jenis_barang',
            'nama_barang',
            'spesifikasi',
            'jumlah_disetujui',
            'jumlah_pemakaian',
            'satuan',
            'status',
            'atk_tbl_form_usulan_pengadaan.keterangan'
        )
            ->join('atk_tbl_form_usulan', 'id_form_usulan', 'form_usulan_id')
            ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
            ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
            ->where('form_usulan_id', $this->id)
            ->get();
    }

    public function headings(): array
    {
        return ["UNIT KERJA", "TANGGAL", "JENIS BARANG", "NAMA BARANG", "SPESIFIKASI", "JUMLAH PENGADAAN", "JUMLAH DISTRIBUSI", "SATUAN", "STATUS", "KETERANGAN"];
    }
}
