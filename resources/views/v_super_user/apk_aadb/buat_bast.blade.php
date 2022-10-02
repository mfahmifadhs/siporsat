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
                <form action="{{ url('super-user/aadb/usulan/proses-bast/'. $usulan->id_form_usulan) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <input type="hidden" name="total_pengajuan" value="1">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <small>Pastikan seluruh kolom terisi dengan baik dan benar.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    @foreach($data as $dataUsulan)
                    <input type="hidden" name="id_kendaraan" value="{{ rand(1000000,9999999) }}">
                    @if($dataUsulan->jenis_aadb == 'bmn')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_barang" placeholder="Masukan Kode Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <select name="jenis_aadb" class="form-control" readonly>
                                <option value="{{ $dataUsulan->jenis_aadb }}">{{ $dataUsulan->jenis_aadb }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select name="jenis_kendaraan_id" class="form-control" readonly>
                                <option value="{{ $dataUsulan->id_jenis_kendaraan }}">{{ $dataUsulan->jenis_kendaraan }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_kendaraan" value="{{ $dataUsulan->merk_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tipe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tipe_kendaraan" value="{{ $dataUsulan->tipe_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" value="{{ $dataUsulan->tahun_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_plat_kendaraan" placeholder="No Plat Kendaraan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="mb_stnk_plat_kendaraan">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat RHS</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_plat_rhs">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK RHS</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="mb_stnk_plat_rhs">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. BPKB</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_bpkb">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Rangka</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_rangka">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Mesin</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="no_mesin">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kondisi</label>
                        <div class="col-sm-10">
                            <select name="kondisi_kendaraan_id" class="form-control">
                                <option value="1">baik</option>
                                <option value="2">rusak ringan</option>
                                <option value="3">rusak berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Penggunaan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" readonly>{{ $usulan->rencana_pengguna }}</textarea>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-md-10">
                            <label>Apakah semua kendaraan telah diterima dengan baik ?</label>
                            <p>
                                <input type="radio" name="konfirmasi" value="1"> Ya
                                <input type="radio" name="konfirmasi" value="1"> Tidak
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_otp_bast" id="inputOTP" placeholder="Masukan Kode OTP" required>
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

@section('js')
<script>
    // Kode OTP
    $(function() {
        let j = 1;
        let id = "{{ $usulan->jenis_form_usulan }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "{{ $usulan->jenis_form_usulan }}"
            jQuery.ajax({
                url: '/super-user/oldat/sendOTP?tujuan=' + tujuan,
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
