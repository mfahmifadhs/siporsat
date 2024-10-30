<?php

namespace App\Http\Controllers;

use App\Models\GDN\BidangKerusakan;
use App\Models\GDN\UsulanGdn;
use App\Models\GDN\UsulanGdnDetail;
use App\Models\UnitKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class GdnController extends Controller
{
    public function show(Request $request, $aksi, $id)
    {
        if ($aksi == 'verifikasi') {
            $aksi = 'status_pengajuan_id';
        }

        if ($aksi == 'proses') {
            $aksi = 'status_proses_id';
        }

        $usulan   = UsulanGdn::count();
        $listUker = UnitKerja::orderBy('unit_kerja', 'ASC')->get();

        $uker    = $request->get('uker_id');
        $proses  = $request->get('proses_id');
        $tanggal = $request->get('tanggal');
        $bulan   = $request->get('bulan'  );
        $tahun   = $request->get('tahun');

        return view('v_admin_user.apk_gdn.daftar_pengajuan', compact('usulan', 'listUker', 'uker', 'proses', 'tanggal', 'bulan', 'tahun', 'aksi', 'id'));
    }

    public function detail($id)
    {
        $usulan = UsulanGdn::where('id_form_usulan', $id)->first();
        return view('v_admin_user.apk_gdn.detail', compact('usulan', 'id'));
    }

    public function select(Request $request)
    {
        $aksi    = $request->aksi;
        $id      = $request->id;
        $data    = UsulanGdn::with('user', 'pegawai.unitKerja', 'detailUsulanGdn')->orderBy('tanggal_usulan', 'DESC');
        $no = 1;
        $response = [];

        if ($request->uker || $request->proses || $request->tanggal || $request->bulan || $request->tahun) {
            if ($request->uker) {
                $res = $data->whereHas('pegawai', function ($query) use ($request) {
                    $query->where('unit_kerja_id', $request->uker);
                });
            }

            if ($request->proses) {
                $res = $data->where('status_proses_id', $request->proses);
            }

            if ($request->tanggal) {
                $res = $data->where(DB::raw("DATE_FORMAT(tanggal_usulan, '%d')"), $request->tanggal);
            }

            if ($request->bulan) {
                $res = $data->where(DB::raw("DATE_FORMAT(tanggal_usulan, '%m')"), $request->bulan);
            }

            if ($request->tahun) {
                $res = $data->where(DB::raw("DATE_FORMAT(tanggal_usulan, '%Y')"), $request->tahun);
            }

            $result = $res->get();
        } else if ($aksi == 'status_proses_id') {
            $result = $data->where($aksi, $id)->get();
        } else if ($aksi == 'status_pengajuan_id') {
            $result = $data->where($aksi, $id)->get();
        } else {
            $result = $data->get();
        }

        foreach ($result as $row) {

            if ($row->status_pengajuan_id == 1) {
                $status = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> setuju</span>';
            } else if ($row->status_pengajuan_id == 2) {
                $status = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> tolak</span>';
            } else {
                $status = '<span class="badge badge-warning"><i class="fas fa-clock"></i> pending</span>';
            }

            if ($row->status_proses_id >= 2 && $row->status_proses_id < 5) {
                $proses = '<span class="badge badge-warning"><i class="fas fa-clock"></i> proses</span>';
            } else if ($row->status_proses_id == 5) {
                $proses = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> selesai</span>';
            } else {
                $proses = '';
            }

            $aksi = '
                <a href="' . route('gdn.detail', $row->id_form_usulan) . '"><i class="fas fa-info-circle"></i></a>
                <a href="' . route('gdn.edit', $row->id_form_usulan) . '"><i class="fas fa-edit"></i></a>
            ';

            $response[] = [
                'no'        => $no,
                'id'        => $row->id_form_usulan,
                'aksi'      => $aksi,
                'tanggal'   => Carbon::parse($row->tanggal_usulan)->isoFormat('HH:mm | DD MMM Y'),
                'uker'      => $row->pegawai?->unitKerja->unit_kerja,
                'nosurat'   => $row->no_surat_usulan,
                'pekerjaan' => $row->detailUsulanGdn->pluck('lokasi_bangunan')->map(function ($item) {
                    return Str::limit($item, 50);
                }),
                'deskripsi' => $row->detailUsulanGdn->pluck('lokasi_spesifik')->map(function ($item) {
                    return Str::limit($item, 50);
                }),
                'status'     => $status.'<br>'.$proses
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function filter($aksi, $id)
    {
        $status = $aksi == 'status' ? 'status_pengajuan_id' : '';
        $data    = UsulanGdn::with('user', 'pegawai.unitKerja', 'detailUsulanGdn');

        if ($status) {
            $result = $data->where('status_pengajuan_id', $id)->get();
        }

        dd($result);
    }

    public function edit($id)
    {
        $usulan = UsulanGdn::where('id_form_usulan', $id)->orderBy('id_form_usulan', 'DESC')->first();
        return view('v_admin_user.apk_gdn.edit', compact('id', 'usulan'));
    }

    public function update(Request $request, $id)
    {
        UsulanGdn::where('id_form_usulan', $id)->update([
            'tanggal_usulan' => $request->tanggal_usulan
        ]);

        $detailId = $request->id_detail;
        foreach ($detailId as $i => $id_detail) {
            if ($id_detail) {
                UsulanGdnDetail::where('id_form_usulan_detail', $id_detail)->update([
                    'bid_kerusakan_id' => $request->bid_kerusakan_id[$i],
                    'lokasi_bangunan'  => $request->lokasi_bangunan[$i],
                    'lokasi_spesifik'  => $request->lokasi_spesifik[$i],
                    'keterangan'       => $request->keterangan[$i]
                ]);
            } else {
                $tambah = new UsulanGdnDetail();
                $tambah->form_usulan_id   = $id;
                $tambah->bid_kerusakan_id = $request->bid_kerusakan_id[$i];
                $tambah->lokasi_bangunan  = $request->lokasi_bangunan[$i];
                $tambah->lokasi_spesifik  = $request->lokasi_spesifik[$i];
                $tambah->keterangan       = $request->keterangan[$i];
                $tambah->save();
            }
        }

        return redirect('admin-user/surat/usulan-gdn/' . $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteDetail($id)
    {
        $detail = UsulanGdnDetail::where('id_form_usulan_detail', $id)->first();
        UsulanGdnDetail::where('id_form_usulan_detail', $id)->delete();
        return redirect('admin-user/surat/usulan-gdn/' . $detail->form_usulan_id)->with('success', 'Berhasil Menghapus Pekerjaan');
    }

    public function JsGdn($aksi, $id)
    {
        $gdn  = BidangKerusakan::where('jenis_bid_kerusakan', $id)->get();

        $response = array();
        foreach ($gdn as $data) {
            $response[] = array(
                "id"     =>  $data->id_bid_kerusakan,
                "text"   =>  $data->bid_kerusakan
            );
        }

        return response()->json($response);
    }
}
