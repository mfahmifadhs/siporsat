@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Pengajuan AADB</h1>
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

@if ($aksi == 1)

@foreach($pengajuan->usulanKendaraan as $dataPengajuan)
<section class="content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Pengadaan Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/ppk/aadb/pengajuan/proses/pengadaan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_form_usulan" value="{{ $pengajuan->id_form_usulan }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <input type="hidden" name="total_pengajuan" value="1">
                    <input type="hidden" name="id_kendaraan" value="{{ \Carbon\Carbon::now()->isoFormat('DDMMYY').rand(100,999) }}">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="jenis_aadb" value="{{ $dataPengajuan->jenis_aadb }}" readonly>
                        </div>
                    </div>
                    @if($dataPengajuan->jenis_aadb == 'sewa')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Mulai Sewa (*)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="mulai_sewa" placeholder="Tahun Mulai Sewa" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Penyedia (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="penyedia" placeholder="Perusahaan Penyedia Sewa Kendaraan" required>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-12 form-group text-muted">Informasi Kendaraan</label>
                        <label class="col-sm-2 col-form-label">Kode Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_barang" placeholder="Mohon Masukan Kode Barang Kendaraan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_kendaraan" readonly>
                                <option value="{{ $dataPengajuan->id_jenis_kendaraan }}">{{ $dataPengajuan->jenis_kendaraan }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_kendaraan" value="{{ $dataPengajuan->merk_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tipe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tipe_kendaraan" value="{{ $dataPengajuan->tipe_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" value="{{ $dataPengajuan->tahun_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat Kendaraan (*)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-uppercase" name="no_plat_kendaraan" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="mb_stnk_plat_kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat RHS (*)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-uppercase" name="no_plat_rhs" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK RHS</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="mb_stnk_plat_rhs" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Penggunaan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" readonly>{{ $pengajuan->rencana_pengguna }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Verifikasi Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="otp_bast_ppk" id="inputOTP" placeholder="Masukan Kode OTP" required>
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
@endforeach

@elseif ($aksi == 2)

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
                <h3 class="card-title">Usulan Pengajuan Servis Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses/servis') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="2">
                    <input type="hidden" name="total_pengajuan" value="1">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Pengajuan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                        <div class="col-sm-9">
                            <select name="kendaraan_id" class="form-control text-capitalize">
                                <option value="">-- Pilih kendaraan yang akan di servis --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kilometer_terakhir" placeholder="Kilometer Kendaraan Terakhir">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_servis_terakhir" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jatuh_tempo_servis" placeholder="Contoh: 50000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tgl_ganti_oli_terakhir" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="jatuh_tempo_ganti_oli" placeholder="Contoh: 50000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP" placeholder="Masukan Kode OTP" required>
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')" disabled>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@elseif ($aksi == 3)

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
                <h3 class="card-title">Usulan Pengajuan Perpanjangan STNK</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses/perpanjangan-stnk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="3">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Pengajuan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Pengajuan</label>
                        <div class="col-sm-8">
                            <input type="number" name="total_pengajuan" id="jumlahKendaraan" class="form-control" value="1" placeholder="Jumlah Kendaraan">
                        </div>
                        <div class="col-sm-1">
                            <a id="btn-total" class="btn btn-primary btn-block">Pilih</a>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Informasi Kendaraan</label>
                        </div>
                    </div>
                    <div id="section-kendaraan">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-4">
                                <select id="select2-kendaraan1" name="kendaraan_id[]" class="form-control text-capitalize kendaraan" data-idtarget="1" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb-stnk1" class="col-sm-3"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Verifikasi</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP" placeholder="Masukan Kode OTP" required>
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')" disabled>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>

@elseif ($aksi == 4)

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
                <h3 class="card-title">Usulan Pengajuan Pengadaan Voucher BBM </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses/voucher-bbm') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="4">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Pengajuan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-9">
                            <input type="month" class="form-control" name="bulan_pengadaan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Kendaraan</label>
                        <div class="col-sm-8">
                            <input type="number" name="total_pengajuan" id="jumlahKendaraan" class="form-control" value="1" placeholder="Jumlah Kendaraan">
                        </div>
                        <div class="col-sm-1">
                            <a id="btn-total" class="btn btn-primary btn-block">Pilih</a>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Informasi Kendaraan</label>
                        </div>
                    </div>
                    <div id="section-kendaraan">
                        <div class="row">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-4">
                                <select name="kendaraan_id[]" class="form-control text-capitalize">
                                    <option value="">-- Pilih Kendaraan --</option>

                                </select>
                            </div>

                            <label class="col-sm-2 col-form-label form-group">Harga BBM /Liter</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control hargaBbm1" name="harga_perliter[]" data-idtarget="1" placeholder="Disesuaikan dengan jenis BBM dan harga terbaru">
                            </div>

                            <label class="col-sm-3 col-form-label form-group">Jumlah Kebutuhan BBM /Liter</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control kebutuhanBbm" name="jumlah_kebutuhan[]" data-idtarget="1" minlength="1" value="1">
                            </div>

                            <label class="col-sm-2 col-form-label form-group">Jenis BBM</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jenis_bbm[]" required>
                            </div>

                            <label class="col-sm-3 col-form-label form-group">Total Biaya</label>
                            <div class="col-sm-9">
                                <span id="totalBiaya1"><input type="number" class="form-control" readonly></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Verifikasi</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP" placeholder="Masukan Kode OTP" required>
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')" disabled>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endif

@section('js')
<script>
    // Kode OTP
    $(function() {
        let j = 1;
        let id = "{{ $aksi }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "Proses Usulan"
            jQuery.ajax({
                url: '/super-user/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasil mengirim kode OTP')
                    resOTP = res
                }
            });
        });
        $(document).on('click', '#btnCheckOTP', function() {
            let inputOTP = $('#inputOTP').val()
            console.log(inputOTP)
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            } else if (inputOTP == resOTP) {
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit').prop('disabled', false)
            } else {
                alert('Kode OTP Salah')
                $('#btnSubmit').prop('disabled', true)
            }
        })
    });
</script>
@endsection

@endsection
