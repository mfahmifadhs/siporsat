<?php

namespace App\Http\Controllers;

use App\Models\UKT\UsulanUkt;
use App\Models\UKT\UsulanUktDetail;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;
use Auth;

class UktController extends Controller
{
    public function index()
    {
        $role   = Auth::user()->level_id;
        $layout = $role == 2 ? 'v_admin_user' : ($role == 3 ? 'v_super_user' : 'v_user');
        $data   = UsulanUkt::orderBy('id_form_usulan', 'DESC');

        if ($role == 4) {
            $usulan = $data->where('user_id', Auth::user()->id)->get();
        } else {
            $usulan = $data->get();
        }


        return view($layout . '.apk_ukt.index', compact('usulan'));
    }

    public function show(Request $request, $aksi, $id)
    {
        $role   = Auth::user()->level_id;
        $url    = $role == 2 ? 'admin-user' : ($role == 3 ? 'super-user' : 'v_user');
        $layout = $role == 2 ? 'v_admin_user' : ($role == 3 ? 'v_super_user' : 'v_user');

        if ($aksi == 'verifikasi') {
            $aksi = 'status_pengajuan_id';
        }

        if ($aksi == 'proses') {
            $aksi = 'status_proses_id';
        }

        $listUker = UnitKerja::orderBy('unit_kerja', 'ASC')->get();
        $data     = UsulanUkt::orderBy('id_form_usulan', 'DESC');

        if ($role == 4) {
            $usulan = $data->where('user_id', Auth::user()->id)->count();
        } else {
            $usulan = $data->count();
        }

        $uker    = $request->get('uker_id');
        $proses  = $request->get('proses_id');
        $tanggal = $request->get('tanggal');
        $bulan   = $request->get('bulan');
        $tahun   = $request->get('tahun');

        return view($layout . '.apk_ukt.daftar_pengajuan', compact('url', 'layout', 'usulan', 'listUker', 'uker', 'proses', 'tanggal', 'bulan', 'tahun', 'aksi', 'id'));
    }

    public function detail($id)
    {
        $role   = Auth::user()->level_id;
        $url    = $role == 2 ? 'admin-user' : ($role == 3 ? 'super-user' : 'v_user');
        $layout = $role == 2 ? 'v_admin_user' : ($role == 3 ? 'v_super_user' : 'v_user');

        $usulan = UsulanUkt::where('id_form_usulan', $id)->first();
        return view($layout . '.apk_ukt.detail', compact('role', 'url', 'layout', 'usulan', 'id'));
    }

    public function select(Request $request)
    {
        $role    = Auth::user()->level_id;
        $jabatan = Auth::user()->pegawai->jabatan_id;
        $url     = $role == 2 ? 'admin-user' : ($role == 3 ? 'super-user' : 'v_user');

        $aksi    = $request->aksi;
        $id      = $request->id;
        $data    = UsulanUkt::with('user', 'pegawai.unitKerja', 'detailUsulanUkt')->orderBy('tanggal_usulan', 'DESC');
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

            $result = $res;
        } else if ($aksi == 'status_proses_id') {
            $result = $data->where($aksi, $id);
        } else if ($aksi == 'status_pengajuan_id') {
            $result = $data->where($aksi, $id);
        } else {
            $result = $data;
        }

        if ($role == 4) {
            $result = $result->where('user_id', Auth::user()->id)->get();
        } else {
            $result = $result->get();
        }

        foreach ($result as $row) {

            if ($row->status_pengajuan_id == 1) {
                $status = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> setuju</span>';
            } else if ($row->status_pengajuan_id == 2) {
                $status = '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> tolak</span>';
            } else if (!$row->otp_usulan_pengusul) {
                $status = '<span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> verif</span>';
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

            $aksi = '';

            if ($jabatan == 2 && $row->status_proses_id == 1) {
                $aksi .= '<a href="' . url($url . '/ukt/usulan/persetujuan/' . $row->id_form_usulan) . '"><i class="fas fa-file-signature"></i></a> ';
            }

            if ($jabatan == 5 && $row->status_proses_id == 2) {
                $aksi .= '<a href="' . url($url . '/ppk/ukt/usulan/perbaikan/' . $row->id_form_usulan) . '"><i class="fas fa-file-signature"></i></a> ';
            }

            $aksi .= '<a href="' . route('ukt.detail', $row->id_form_usulan) . '" alt="Detail"><i class="fas fa-info-circle"></i></a> ';

            if ($role == 2 && $row->status_proses_id <= 2) {
                $aksi .= '<a href="' . route('ukt.edit', $row->id_form_usulan) . '" alt="Edit"><i class="fas fa-edit"></i></a>';
            }

            if (Auth::user()->id == $row->user_id && !$row->otp_usulan_pengusul) {
                $aksi .= '<a href="' . url('unit-kerja/verif/usulan-ukt', $row->id_form_usulan) . '" alt="Verifikasi"><i class="fas fa-file-signature"></i></a>';
            }

            if (Auth::user()->id == $row->user_id && !$row->otp_usulan_kabag && $row->status_pengajuan_id != 2) {
                $aksi .= '<a href="' . route('ukt.delete', $row->id_form_usulan) . '" alt="Hapus" onclick="return confirm(`Apakah anda ingin membatalkan usulan ini ?`)">
                <i class="fas fa-trash-alt ml-1"></i>
                </a>';
            }

            if ($jabatan == 2 && $row->status_proses_id == 4) {
                $aksi .= '<a href="' . url($url . '/verif/usulan-ukt/' . $row->id_form_usulan) . '"><i class="fas fa-file-signature"></i></a> ';
            }

            $response[] = [
                'no'        => $no,
                'id'        => $row->id_form_usulan,
                'aksi'      => $aksi,
                'tanggal'   => Carbon::parse($row->tanggal_usulan)->isoFormat('HH:mm | DD MMM Y'),
                'uker'      => $row->pegawai?->unitKerja->unit_kerja,
                'nosurat'   => $row->no_surat_usulan,
                'pekerjaan' => $row->detailUsulanUkt->pluck('lokasi_pekerjaan')->map(function ($item) {
                    return Str::limit($item, 100);
                }),
                'deskripsi' => $row->detailUsulanUkt->pluck('spesifikasi_pekerjaan')->map(function ($item) {
                    return Str::limit($item, 150);
                }),
                'status'     => $status . '<br>' . $proses
            ];

            $no++;
        }

        return response()->json($response);
    }

    public function edit($id)
    {
        $role   = Auth::user()->level_id;
        $url    = $role == 2 ? 'admin-user' : ($role == 3 ? 'super-user' : '');
        $layout = $role == 2 ? 'v_admin_user' : ($role == 3 ? 'v_super_user' : '');

        $usulan = UsulanUkt::where('id_form_usulan', $id)->orderBy('id_form_usulan', 'DESC')->first();
        return view($layout . '.apk_ukt.edit', compact('role', 'url', 'layout', 'id', 'usulan'));
    }

    public function update(Request $request, $id)
    {
        UsulanUkt::where('id_form_usulan', $id)->update([
            'tanggal_usulan' => $request->tanggal_usulan,
            'no_surat_usulan' => $request->no_surat
        ]);

        $detailId = $request->id_detail;
        foreach ($detailId as $i => $id_detail) {
            if ($id_detail) {
                UsulanUktDetail::where('id_form_usulan_detail', $id_detail)->update([
                    'lokasi_pekerjaan'       => $request->lokasi_pekerjaan[$i],
                    'spesifikasi_pekerjaan'  => $request->spesifikasi_pekerjaan[$i],
                    'keterangan'             => $request->keterangan[$i]
                ]);
            } else {
                $tambah = new UsulanUktDetail();
                $tambah->form_usulan_id        = $id;
                $tambah->lokasi_pekerjaan      = $request->lokasi_pekerjaan[$i];
                $tambah->spesifikasi_pekerjaan = $request->spesifikasi_pekerjaan[$i];
                $tambah->keterangan            = $request->keterangan[$i];
                $tambah->save();
            }
        }

        return redirect('admin-user/surat/usulan-ukt/' . $id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function delete($id)
    {
        UsulanUkt::where('id_form_usulan', $id)->delete();

        return redirect()->route('ukt.show', ['aksi' => 'pengajuan', 'id' => '*'])->with('success', 'Berhasil Menghapus Usulan');
    }

    public function deleteDetail($id)
    {
        $detail = UsulanuktDetail::where('id_form_usulan_detail', $id)->first();
        UsulanUktDetail::where('id_form_usulan_detail', $id)->delete();
        return redirect('admin-user/surat/usulan-ukt/' . $detail->form_usulan_id)->with('success', 'Berhasil Menghapus Pekerjaan');
    }
}
