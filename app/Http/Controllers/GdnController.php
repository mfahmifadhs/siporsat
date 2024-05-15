<?php

namespace App\Http\Controllers;

use App\Models\gdn\BidangKerusakan;
use App\Models\gdn\UsulanGdn;
use App\Models\gdn\UsulanGdnDetail;
use Illuminate\Http\Request;

class GdnController extends Controller
{

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
