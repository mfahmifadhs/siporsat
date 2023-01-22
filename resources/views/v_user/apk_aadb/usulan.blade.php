@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Pengajuan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('unit-kerja/aadb/dashboard') }}">Dashboard</a></li>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Pengadaan Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('unit-kerja/aadb/usulan/proses/pengadaan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_form" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <select name="jenis_aadb" class="form-control" required>
                                <option value="sewa">Sewa</option>
                                <!-- <option value="bmn">BMN</option> -->
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Pengajuan (*)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="total_pengajuan" placeholder="Jumlah Pengajuan Kendaraan" required>
                        </div>
                    </div> -->
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-8 text-muted float-left mt-2">Informasi Kendaraan</label>
                        <label class="col-sm-4 text-muted text-right">
                            <a id="btn-total" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Tambah List Baru
                            </a>
                        </label>
                    </div>
                    <hr style="border: 0.5px solid grey;">

                    <div id="section-kendaraan">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis*</label>
                            <div class="col-sm-4">
                                <select name="kendaraan_id[]" class="form-control" required>
                                    @foreach($jenisKendaraan as $dataJenisKendaraan)
                                    <option value="{{ $dataJenisKendaraan->id_jenis_kendaraan }}">{{ $dataJenisKendaraan->jenis_kendaraan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe*</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="merk_tipe_kendaraan[]" placeholder="Contoh : Toyota Avanza" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah*</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="jumlah_pengajuan[]" value="1" min="1" required>
                            </div>
                            <label class="col-sm-2 col-form-label">Tahun*</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="tahun_kendaraan[]" placeholder="Tahun Kendaraan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select name="kualifikasi[]" class="form-control" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah data sudah benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT</button>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Servis Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('unit-kerja/aadb/usulan/proses/servis') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_form" value="2">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <label class="col-form-label text-danger" style="font-size:13px;">
                        Mohon untuk memilih kendaraan secara berurutan.
                    </label>
                    <div id="section-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-2 mb-1">
                            <div class="col-md-6">
                                <label class="text-muted">Informasi Kendaraan 1</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <a id="btn-total" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> list baru
                                </a>
                            </div>
                        </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-3 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-9">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan1" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                                <span class="text-danger" style="font-size:12px;">
                                    Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kilometer Kendaraan*</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kilometer_terakhir[]" placeholder="Kilometer Kendaraan Terakhir" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis*</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_servis_terakhir[]">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)*</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_servis[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli*</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_ganti_oli_terakhir[]">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)*</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_ganti_oli[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan Servis*</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan_servis[]" class="form-control" placeholder="Contoh: Servis Rutin, Perbaikan Mesin, Dsb" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah data sudah benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT
                            </button>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Perpanjangan STNK</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('unit-kerja/aadb/usulan/proses/perpanjangan-stnk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_form" value="3">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <label class="col-form-label text-danger" style="font-size:13px;">
                        Mohon untuk memilih kendaraan secara berurutan.
                    </label>
                    <div id="section-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-2 mb-1">
                            <div class="col-md-6">
                                <label class="text-muted">Informasi Kendaraan 1</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <a id="btn-total" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> list baru
                                </a>
                            </div>
                        </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan1" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                                <span class="text-danger" style="font-size:12px;">
                                    Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Masa Berlaku STNK*</label>
                            <span id="mb-stnk1" class="col-sm-10"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
                        </div>
                    </div>
                    <div class="form-group row pt-5 text-right">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah data sudah benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT
                            </button>
                        </div>
                    </div>
                </form>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Pengadaan Voucher BBM </h3>
            </div>
            <form action="{{ url('unit-kerja/aadb/usulan/proses/voucher-bbm') }}" method="POST">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="jenis_form" value="4">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-10">
                            <input type="month" class="form-control" name="bulan_pengadaan" value="{{ \Carbon\carbon::now()->isoFormat('Y-MM') }}" required>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-sm-12">
                            <div class="alert alert-info alert-dismissible" style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3"></div>
                                </div>
                                <h6>
                                    <i class="icon fas fa-info-circle fa-1x"></i>
                                    Pengajuan Voucher BBM untuk bulan berikutnya, maksimal tanggal 20 bulan berjalan
                                </h6>
                                <small> 1. Kendaraan Jabatan : Rp 2.000.000, 2. Kendaraan Operasional : Rp 1.500.000, 3. Kendaraan Bermotor : Rp 200.000</small>
                            </div>
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="text-center pb-4" style="width: 1%;">No</th>
                                        <th style="width: 15%;" class="pb-4">Jenis AADB</th>
                                        <th style="width: 20%;" class="pb-4">No. Plat</th>
                                        <th class=" pb-4">Kendaraan</th>
                                        <th style="width: 10%;" class=" pb-4">Kualifikasi</th>
                                        <th class="text-center" style="width: 20%;">
                                            Pilih Kendaraan <br>
                                            <input type="checkbox" id="selectAll" style="scale: 1.7;">
                                        </th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach ($kendaraan as $i => $dataAadb)
                                    <tr class="text-uppercase">
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td class="text-uppercase">
                                            <input type="hidden" name="kendaraan_id[]" value="{{ $dataAadb->id_kendaraan }}">
                                            {{ $dataAadb->jenis_aadb }}
                                        </td>
                                        <td class="">{{ $dataAadb->no_plat_kendaraan }}</td>
                                        <td class="">{{ $dataAadb->merk_tipe_kendaraan }}</td>
                                        <td class="">{{ $dataAadb->kualifikasi }}</td>
                                        <td class="text-center">
                                            <input type="hidden" value="false" name="status_pengajuan[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- <label class="col-form-label text-danger" style="font-size:13px;">
                        Mohon untuk memilih kendaraan secara berurutan.
                    </label>
                    <div id="section-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-2 mb-1">
                            <div class="col-md-6">
                                <label class="text-muted">Informasi Kendaraan 1</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <a id="btn-total" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus-circle"></i> list baru
                                </a>
                            </div>
                        </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan1" data-idtarget="1" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                            </div>
                            <span class="text-danger" style="font-size:12px;">
                                Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                            </span>
                        </div>
                    </div> -->
                </div>
                <div class="card-footer text-right">
                    @if (count($kendaraan) != 0)
                    <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Buat pengajuan voucher bbm?')">
                        <i class="fa fa-paper-plane"></i> SUBMIT
                    </button>
                    @endif
                </div>
            </form>
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
        let button = document.getElementById("btnSubmit");
        $(".kualifikasi").select2()
        let aadbData = []

        // Menyimpan data aadb dalam Array
        $(document).on('change', '.kualifikasi', function() {
            aadbData = $('.kendaraan').map(function() {
                return this.value
            }).get()
        })

        // Daftar Kualifikasi
        $(document).on('change', '.kualifikasi', function() {
            let kualifikasi = $(this).val()
            let target = $(this).data('idtarget')
            console.log(target)
            if (kualifikasi) {
                $.ajax({
                    type: "GET",
                    url: "/unit-kerja/aadb/select2/kendaraan",
                    dataType: 'JSON',
                    data: {
                        "data": kualifikasi,
                        "kendaraan": aadbData
                    },
                    success: function(res) {
                        if (res.length != 0) {
                            $("#kendaraan" + target).empty();
                            $("#kendaraan" + target).select2();
                            $("#kendaraan" + target).append('<option value="">-- Pilih Kendaraan --</option>');
                            $.each(res, function(index, row) {
                                $("#kendaraan" + target).append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                                button.disabled = false

                            })

                        } else {
                            button.disabled = true
                            $("#kendaraan" + target).empty();
                            $("#kendaraan" + target).append('<option value="">-- Tidak ada kendaraan --</option>');
                        }
                    }
                })
            } else {
                $("#kendaraan").empty();
            }
        })

        // More Item
        $('#btn-total').click(function() {
            let i
            let aksi = "{{ $aksi }}"
            console.log(aksi)
            if (aksi == 'pengadaan') {
                ++j
                $("#section-kendaraan").append(
                    `<div class="row-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-2 mb-1">
                            <div class="col-md-6">
                                <label class="text-muted">Informasi Kendaraan ` + j + `</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-danger btn-sm remove-list">
                                    <i class="fas fa-minus-circle"></i> hapus list
                                </a>
                            </div>
                        </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Jenis*</label>
                            <div class="col-sm-4">
                                <select name="kendaraan_id[]" class="form-control" required>
                                    @foreach($jenisKendaraan as $dataJenisKendaraan)
                                    <option value="{{ $dataJenisKendaraan->id_jenis_kendaraan }}">{{ $dataJenisKendaraan->jenis_kendaraan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe*</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="merk_tipe_kendaraan[]" placeholder="Contoh : Toyota Avanza" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah*</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="jumlah_pengajuan[]" value="1" min="1" required>
                            </div>
                            <label class="col-sm-2 col-form-label">Tahun*</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="tahun_kendaraan[]" placeholder="Tahun Kendaraan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select name="kualifikasi[]" class="form-control" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    `
                )
            } else if (aksi == 'voucher-bbm') {
                ++j
                $("#section-kendaraan").append(
                    `<div class="row-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                            <div class="form-group row mt-2 mb-1">
                                <div class="col-md-6">
                                    <label class="text-muted">Informasi Kendaraan ` + j + `</label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="btn btn-danger btn-sm remove-list">
                                        <i class="fas fa-minus-circle"></i> hapus list
                                    </a>
                                </div>
                            </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-3">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan` + j + `" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                                <span class="text-danger" style="font-size:12px;">
                                    Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                                </span>
                            </div>
                        </div>
                    </div>`
                )

            } else if (aksi == 'perpanjangan-stnk') {
                ++j
                $("#section-kendaraan").append(
                    `<div class="row-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-2 mb-1">
                            <div class="col-md-6">
                                <label class="text-muted">Informasi Kendaraan ` + j + `</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="btn btn-danger btn-sm remove-list">
                                    <i class="fas fa-minus-circle"></i> hapus list
                                </a>
                            </div>
                        </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-2 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-10">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan` + j + `" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                                <span class="text-danger" style="font-size:12px;">
                                    Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb_stnk" class="col-sm-10">
                                <span id="mb-stnk` + j + `"><input type="text" class="form-control" placeholder="Masa Berlaku STNK" readonly></span>
                            </span>
                        </div>
                    </div>`
                )

            } else if (aksi == 'servis') {
                ++j
                $("#section-kendaraan").append(
                    `<div class="row-kendaraan">
                        <hr style="border: 0.1px solid grey;" class="m-0">
                            <div class="form-group row mt-2 mb-1">
                                <div class="col-md-6">
                                    <label class="text-muted">Informasi Kendaraan ` + j + `</label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a class="btn btn-danger btn-sm remove-list">
                                        <i class="fas fa-minus-circle"></i> hapus list
                                    </a>
                                </div>
                            </div>
                        <hr style="border: 0.1px solid grey;" class="m-0">
                        <div class="form-group row mt-3">
                            <label class="col-sm-3 col-form-label">Kualifikasi*</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kualifikasi" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi --</option>
                                    <option value="jabatan">Kendaraan Jabatan</option>
                                    <option value="operasional">Kendaraan Operasional</option>
                                    <option value="bermotor">Kendaraan Bermotor</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Pilih Kendaraan*</label>
                            <div class="col-sm-9">
                                <select name="kendaraan_id[]" class="form-control text-capitalize kendaraan" id="kendaraan` + j + `" data-idtarget="` + j + `" required>
                                    <option value="">-- Pilih Kualifikasi Dahulu --</option>
                                </select>
                                <span class="text-danger" style="font-size:12px;">
                                    Jika kendaraan tidak muncul, mohon untuk melengkapi no plat, dan pengguna kendaraan dahulu pada halaman detail kendaraan.
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="kilometer_terakhir[]" placeholder="Kilometer Kendaraan Terakhir" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_servis_terakhir[]">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_servis[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="tgl_ganti_oli_terakhir[]">
                            </div>
                            <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="jatuh_tempo_ganti_oli[]" placeholder="Contoh: 50000">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Keterangan Servis*</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan_servis[]" class="form-control" placeholder="Contoh: Servis Rutin, Perbaikan Mesin, Dsb" required></textarea>
                            </div>
                        </div>
                    </div>`
                )
            }


            $(".kualifikasi").select2();

            $(document).on('click', '.remove-list', function() {
                $(this).parents('.row-kendaraan').remove();
            })
        })

        // Masa Berlaku STNK
        $(document).on('change', '.kendaraan', function() {
            let target = $(this).data('idtarget')
            let kendaraanId = $(this).val()
            if (kendaraanId) {
                $.ajax({
                    type: "GET",
                    url: "/unit-kerja/aadb/kendaraan/detail-json/kendaraanId?kendaraanId=" + kendaraanId,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#mb-stnk" + target).empty()
                            $.each(res, function(index, row) {
                                $("#mb-stnk" + target).append(
                                    '<input type="text" name="mb_stnk[]" class="form-control" value="' + row.mb_stnk_plat_kendaraan + '" readonly>'
                                )
                            })
                        }
                    }
                })
            }
        })



        $('#selectAll').click(function() {
            if ($(this).prop('checked')) {
                $('.confirm-check').prop('checked', true);
            } else {
                $('.confirm-check').prop('checked', false);
            }
        })
    })

    $(document).on('change', '.select2-kendaraan', function() {
        let itemCategoryId = $(this).val();
        let target = $(this).data('idtarget');

    })
</script>
@endsection

@endsection
