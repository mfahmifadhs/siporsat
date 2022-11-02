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
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/aadb/pengadaan/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
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
                                <option value="sewa">Sewa</option>
                                <option value="bmn">BMN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Pengajuan (*)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="total_pengajuan" placeholder="Jumlah Pengajuan Kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 text-muted">Informasi Kendaraan</label>
                        <label class="col-sm-2 col-form-label">Jenis (*)</label>
                        <div class="col-sm-10">
                            <select name="jenis_kendaraan" class="form-control" required>
                                @foreach($jenisKendaraan as $dataJenisKendaraan)
                                <option value="{{ $dataJenisKendaraan->id_jenis_kendaraan }}">{{ $dataJenisKendaraan->jenis_kendaraan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_kendaraan" placeholder="Contoh : Toyota, Honda" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tipe (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tipe_kendaraan" placeholder="Contoh: CRV, HRV" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" placeholder="Tahun Kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pengguna (*)</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" placeholder="Contoh: Untuk Operasional" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan pengadaan kendaraan ?')">Submit</button>
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
                        <label class="col-sm-3 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/aadb/pemeliharaan/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
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
                    <div id="section-kendaraan">
                        <div class="form-group row">
                            <label class="col-sm-12 mb-3 text-muted">Informasi Kendaraan</label>
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-9">
                                <select name="kendaraan_id[]" class="form-control text-capitalize select2-kendaraan" data-idtarget="1">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kilometer_terakhir[]" placeholder="Kilometer Kendaraan Terakhir">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_servis_terakhir[]" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_servis[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_ganti_oli_terakhir[]" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_ganti_oli[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
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
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="3">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/aadb/perpanjanganstnk/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Usulan</label>
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
                    <div id="section-kendaraan">
                        <div class="form-group row">
                            <label class="col-sm-12 text-muted">Informasi Kendaraan</label>
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-9">
                                <select name="kendaraan_id[]" class="form-control text-capitalize aadb select2-kendaraan" data-idtarget="1">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb-stnk1" class="col-sm-9"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
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
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="4">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/aadb/voucherbbm/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-10">
                            <input type="month" class="form-control" name="bulan_pengadaan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Kendaraan</label>
                        <div class="col-sm-9">
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
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize select2-kendaraan" data-idtarget="1">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->merk_tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-group">Voucher 25</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v251" name="voucher_25[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                            <label class="col-sm-2 col-form-label form-group">Voucher 50</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v501" name="voucher_50[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                            <label class="col-sm-2 col-form-label form-group">Voucher 100</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v1001" name="voucher_100[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        let j = 1

        // Select-2
        $(".select2-kendaraan").select2({
            ajax: {
                url: "{{ url('super-user/aadb/select2/kendaraan') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        })

        // More Item
        $('#btn-total').click(function() {
            let i
            let total = ($('#jumlahKendaraan').val()) - 1
            let aksi = "{{ $aksi }}"
            if (aksi == 'voucher-bbm') {
                $(".section-kendaraan").empty()
                for (i = 1; i <= total; i++) {
                    ++j
                    console.log(j)
                    $("#section-kendaraan").append(
                        `<div class="section-kendaraan">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize select2-kendaraan` + j + `">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-group">Voucher 25</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v251" name="voucher_25[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                            <label class="col-sm-2 col-form-label form-group">Voucher 50</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v501" name="voucher_50[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                            <label class="col-sm-2 col-form-label form-group">Voucher 100</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control v1001" name="voucher_100[]" data-idtarget="1" placeholder="Jumlah Voucher 25">
                            </div>
                        </div>
                    </div>`
                    )
                    $(".select2-kendaraan" + j).select2({
                        ajax: {
                            url: "{{ url('super-user/aadb/select2/kendaraan') }}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term // search term
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            },
                            cache: true
                        }
                    })
                }
            } else if (aksi == 'perpanjangan-stnk') {
                $(".section-kendaraan").empty()
                for (i = 1; i <= total; i++) {
                    ++j
                    $("#section-kendaraan").append(
                        `<div class="row section-kendaraan">
                            <label class="col-sm-3 col-form-label form-group">Pilih Kendaraan</label>
                            <div class="col-sm-9">
                                <select name="kendaraan_id[]" class="form-control text-capitalize aadb select2-kendaraan` + j + `" data-idtarget="` + j + `">
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <option value="{{ $dataKendaraan->id_kendaraan }}">
                                        {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan.' / pengguna '. $dataKendaraan->pengguna }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-3 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb_stnk" class="col-sm-9">
                                <span id="mb-stnk` + j + `"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
                            </span>
                        </div>`
                    )

                    // Daftar Kendaraan
                    $(".select2-kendaraan" + j).select2({
                        ajax: {
                            url: "{{ url('super-user/aadb/select2/kendaraan') }}",
                            type: "post",
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term // search term
                                };
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            },
                            cache: true
                        }
                    })
                }
            } else if (aksi == 'servis') {
                $(".section-kendaraan").empty()
                for (i = 1; i <= total; i++) {
                    ++j
                    $("#section-kendaraan").append(
                        `<div class="section-kendaraan">
                            <div class="form-group row">
                                <label class="col-sm-12 mb-3 text-muted">Informasi Kendaraan</label>
                                <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                                <div class="col-sm-9">
                                    <select name="kendaraan_id[]" class="form-control text-capitalize select2-kendaraan`+j+`" data-idtarget="`+j+`">
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach($kendaraan as $dataKendaraan)
                                        <option value="{{ $dataKendaraan->id_kendaraan }}">
                                            {{ $dataKendaraan->no_plat_kendaraan.' / '.$dataKendaraan->jenis_kendaraan.' '.$dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kilometer_terakhir[]" placeholder="Kilometer Kendaraan Terakhir">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="tgl_servis_terakhir[]" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                                </div>
                                <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="jatuh_tempo_servis[]" placeholder="Contoh: 50000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" name="tgl_ganti_oli_terakhir[]" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                                </div>
                                <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="jatuh_tempo_ganti_oli[]" placeholder="Contoh: 50000">
                                </div>
                            </div>
                        </div>`
                    )
                }

                // Daftar Kendaraan
                $(".select2-kendaraan" + j).select2({
                    ajax: {
                        url: "{{ url('super-user/aadb/select2/kendaraan') }}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term // search term
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                })
            }
        })

        // Masa Berlaku STNK
        $(document).on('change', '.aadb', function() {
            let target = $(this).data('idtarget')
            let kendaraanId = $(this).val()
            if (kendaraanId) {
                $.ajax({
                    type: "GET",
                    url: "/super-user/aadb/kendaraan/detail-json/kendaraanId?kendaraanId=" + kendaraanId,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#mb-stnk" + target).empty()
                            $.each(res, function(index, row) {
                                $("#mb-stnk" + target).append(
                                    '<input type="date" name="mb_stnk[]" class="form-control" value="' + row.mb_stnk_plat_kendaraan + '" readonly>'
                                )
                            })
                        }
                    }
                })
            }
        })
    })

    $(document).on('change', '.select2-kendaraan', function() {
        let itemCategoryId = $(this).val();
        let target = $(this).data('idtarget');
        console.log(target)

    })

    // Kode OTP
    $(function() {
        let j = 1;
        let id = "{{ $aksi }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "{{ $aksi }}"
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
            $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
        })
    });
</script>
@endsection

@endsection
