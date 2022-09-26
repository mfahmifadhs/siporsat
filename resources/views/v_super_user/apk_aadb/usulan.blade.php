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

@if ($aksi == 'pengadaan')

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
                <h3 class="card-title">Usulan Pengajuan Pengadaan Kendaraan </h3>
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
                                <option value="bmn">BMN</option>
                                <option value="sewa">Sewa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select name="jenis_kendaraan" class="form-control" required>
                                @foreach($jenisKendaraan as $dataJenisKendaraan)
                                <option value="{{ $dataJenisKendaraan->id_jenis_kendaraan }}">{{ $dataJenisKendaraan->jenis_kendaraan }}</option>
                                @endforeach
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

@elseif ($aksi == 'servis')

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
                                @foreach($kendaraan as $dataKendaraan)
                                <option value="{{ $dataKendaraan->id_kendaraan }}">
                                    {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                </option>
                                @endforeach
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

@elseif ($aksi == 'perpanjangan-stnk')

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
                    <input type="hidden" name="jenis_form" value="4">
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
                        <div class="row">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-4">
                                <select name="kendaraan_id" class="form-control text-capitalize">
                                    <option value="">-- Pilih kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb_stnk" class="col-sm-3"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
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

@elseif ($aksi == 'voucher-bbm')

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
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
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
    // Jumlah Kendaraan
    $(function() {
        let j = 1
        // More Item
        $('#btn-total').click(function() {
            let i
            let total = ($('#jumlahKendaraan').val()) - 1
            let aksi = "{{ $aksi }}"
            if (aksi == 'voucher-bbm') {
                $(".section-kendaraan").empty()
                for (i = 1; i <= total; i++) {
                    ++j
                    $("#section-kendaraan").append(
                        `<div class="row section-kendaraan">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-4">
                                <select name="kendaraan_id[]" class="form-control text-capitalize">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <label class="col-sm-2 col-form-label form-group">Harga BBM /Liter</label>
                            <div class="col-sm-3">
                                <input type="number" class="form-control hargaBbm` + j + `" name="harga_perliter[]" data-idtarget="` + j + `" placeholder="Disesuaikan dengan jenis BBM dan harga terbaru">
                            </div>

                            <label class="col-sm-3 col-form-label form-group">Jumlah Kebutuhan BBM /Liter</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control kebutuhanBbm" name="jumlah_kebutuhan[]" data-idtarget="` + j + `" minlength="1" value="1">
                            </div>

                            <label class="col-sm-2 col-form-label form-group">Jenis BBM</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jenis_bbm[]" required>
                            </div>

                            <label class="col-sm-3 col-form-label form-group">Total Biaya</label>
                            <div class="col-sm-9">
                                <span id="totalBiaya` + j + `"><input type="number" class="form-control" readonly></span>
                            </div>
                        </div>`
                    )
                }
            }
        })

        $(document).on('change', '.kebutuhanBbm', function() {
            let target = $(this).data('idtarget')
            let hargaBbm = $('.hargaBbm' + target).val()
            let kebutuhanBbm = $(this).val()
            let total = hargaBbm * kebutuhanBbm
            console.log(hargaBbm)

            $("#totalBiaya" + target).empty();
            $("#totalBiaya" + target).append(
                '<input type="number" class="form-control" name="total_biaya[]" value="' + total + '" readonly>'
            )

        })
    })

    // Kode OTP
    $(function() {
        let j = 1;
        let id = "{{ $aksi }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "{{ $aksi }}"
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
