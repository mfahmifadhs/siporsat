@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Berita Acara Serah Terima (BAST)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usulan Pengajuan AADB</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($cekForm->jenis_form == 1)

<section class="content">
    <div class="container">
        <div class="col-md-12 form-group">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @elseif ($message = Session::get('failed'))
            <div class="alert alert-danger">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Berita Acara Serah Terima (BAST) Pengadaan AADB</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses/pengadaan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <input type="hidden" name="total_pengajuan" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <select name="jenis_aadb" class="form-control" required>
                                <option value="">{{ $usulan->jenis_form_usulan }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select name="jenis_kendaraan" class="form-control" required>
                                <option value="{{ $usulan->jenis_kendaraan_id }}">{{ $usulan->jenis_kendaraan }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_kendaraan" placeholder="Contoh : Toyota, Honda" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tipe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tipe_kendaraan" placeholder="Contoh: CRV, HRV" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" placeholder="Tahun Kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Penggunaan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" placeholder="Contoh: Untuk Operasional" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP" placeholder="Masukan Kode OTP" required>
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan pengadaan kendaraan ?')" disabled>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endif

@endsection
