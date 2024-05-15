<?php

namespace App\Http\Controllers;

use App\Models\UKT\UsulanUkt;
use App\Models\UKT\UsulanUktDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;

class UktController extends Controller
{

    public function edit($id)
    {
        $usulan = UsulanUkt::where('id_form_usulan', $id)->orderBy('id_form_usulan', 'DESC')->first();
        return view('v_admin_user.apk_ukt.edit', compact('id', 'usulan'));
    }

    public function update(Request $request, $id)
    {
        UsulanUkt::where('id_form_usulan', $id)->update([
            'tanggal_usulan' => $request->tanggal_usulan
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

        return redirect('admin-user/surat/usulan-ukt/'.$id)->with('success', 'Berhasil Menyimpan Perubahan');
    }

    public function deleteDetail($id)
    {
        $detail = UsulanuktDetail::where('id_form_usulan_detail', $id)->first();
        UsulanUktDetail::where('id_form_usulan_detail', $id)->delete();
        return redirect('admin-user/surat/usulan-ukt/'. $detail->form_usulan_id)->with('success', 'Berhasil Menghapus Pekerjaan');
    }
}
