<?php

namespace App\Http\Controllers;

use App\Models\AADB\UsulanAadb;
use App\Models\AADB\UsulanKendaraan;
use App\Models\ATK\UsulanAtk;
use App\Models\GDN\UsulanGdn;
use App\Models\OLDAT\FormUsulan;
use App\Models\Pegawai;
use App\Models\UKT\UsulanUkt;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function Links($modul, $id)
    {
        if ($modul== 'usulan-ukt') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'usulan-gdn') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'usulan-atk') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'usulan-aadb') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'usulan-oldat') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'bast-atk') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'bast-aadb') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'bast-oldat') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'bast-gdn') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');

        } elseif ($modul== 'bast-ukt') {
            return redirect('usulan/'. $modul.'/'.$id)->with('success', 'Dokumen Telah Terverifikasi!');
        }
    }

    public function Letters($modul, $id)
    {
        if ($modul == 'usulan-ukt') {
            $modul = 'ukt';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_usulan_pengusul', $id)
                ->orWhere('otp_usulan_kabag', $id)
                ->first();


            if ($usulan != null) {
                return view('surat_usulan', compact('modul', 'usulan', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul== 'usulan-gdn') {
            $modul = 'gdn';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_usulan_pengusul', $id)
                ->orWhere('otp_usulan_kabag', $id)
                ->first();

            if ($usulan != null) {
                return view('surat_usulan', compact('modul', 'usulan', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul== 'usulan-atk') {
            $modul = 'atk';
            $form  = UsulanAtk::where('otp_usulan_pengusul', $id)->first();

            $usulan = UsulanAtk::with(['detailUsulanAtk'])
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_usulan_pengusul', $id)
                ->orWhere('otp_usulan_pimpinan', $id)
                ->first();

            if ($usulan != null) {
                return view('surat_usulan', compact('form', 'modul', 'usulan', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul== 'usulan-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_usulan_pengusul', $id)
                ->orWhere('otp_usulan_kabag', $id)
                ->first();

            if ($usulan != null) {
                return view('surat_usulan', compact('modul', 'usulan', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul== 'usulan-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $usulan  = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_usulan_pengusul', $id)
                ->orWhere('otp_usulan_kabag', $id)
                ->first();
            if ($usulan != null) {
                return view('surat_usulan', compact('modul', 'usulan', 'pimpinan'))->with('success', 'Dokumen Telah Terverifikasi!');
            } else {
                return redirect('/');
            }
        } elseif ($modul == 'bast-atk') {
            $modul = 'atk';
            $bast = UsulanAtk::with(['detailUsulanAtk'])
                ->join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_bast_pengusul', $id)
                ->first();

            if($bast->jenis_form == 'pengadaan')
            {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '5')->where('unit_kerja_id', 465930)->first();
            } else {
                $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();
            }

            if ($bast != null) {
                return view('surat_bast', compact('pimpinan', 'bast', 'modul'));
            } else {
                return redirect('/');
            }

        } elseif ($modul == 'bast-aadb') {
            $modul = 'aadb';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $form = UsulanAadb::where('otp_bast_ppk', $id)
                ->orWhere('otp_bast_pengusul', $id)
                ->orWhere('otp_bast_kabag', $id)
                ->pluck('id_form_usulan');

            $jenisAadb = UsulanKendaraan::where('form_usulan_id', $form)->first();

            $bast = UsulanAadb::join('aadb_tbl_jenis_form_usulan', 'id_jenis_form_usulan', 'jenis_form')
                ->join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_bast_ppk', $id)
                ->orWhere('otp_bast_pengusul', $id)
                ->orWhere('otp_bast_kabag', $id)
                ->first();

            if ($bast != null) {
                return view('surat_bast', compact('pimpinan', 'bast', 'modul','jenisAadb'));
            } else {
                return redirect('/');
            }

        } elseif ($modul == 'bast-oldat') {
            $modul = 'oldat';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = FormUsulan::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->leftjoin('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->leftjoin('tbl_tim_kerja', 'id_tim_kerja', 'tim_kerja_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'tbl_pegawai.unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_bast_ppk', $id)
                ->orWhere('otp_bast_pengusul', $id)
                ->orWhere('otp_bast_kabag', $id)
                ->first();

            if ($bast != null) {
                return view('surat_bast', compact('modul', 'bast', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul == 'bast-gdn') {
            $modul = 'gdn';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = UsulanGdn::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_bast_ppk', $id)
                ->orWhere('otp_bast_pengusul', $id)
                ->orWhere('otp_bast_kabag', $id)
                ->first();

            if ($bast != null) {
                return view('surat_bast', compact('modul', 'bast', 'pimpinan'));
            } else {
                return redirect('/');
            }

        } elseif ($modul == 'bast-ukt') {
            $modul = 'ukt';
            $pimpinan = Pegawai::join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->where('jabatan_id', '2')->where('unit_kerja_id', 465930)->first();

            $bast = UsulanUkt::join('tbl_pegawai', 'id_pegawai', 'pegawai_id')
                ->join('tbl_pegawai_jabatan', 'id_jabatan', 'jabatan_id')
                ->join('tbl_unit_kerja', 'id_unit_kerja', 'unit_kerja_id')
                ->join('tbl_unit_utama', 'id_unit_utama', 'unit_utama_id')
                ->where('otp_bast_ppk', $id)
                ->orWhere('otp_bast_pengusul', $id)
                ->orWhere('otp_bast_kabag', $id)
                ->first();

            if ($bast != null) {
                return view('surat_bast', compact('modul', 'bast', 'pimpinan'));
            } else {
                return redirect('/');
            }

        }
    }
}

