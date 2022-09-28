@extends('v_super_user.layout.app')

@section('css')
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0mm;
        /* this affects the margin in the printer settings */
        margin-top: -22vh;
        margin-left: -1.8vh;
    }

    .header-confirm .header-text-confirm {
        padding-top: 8vh;
        line-height: 2vh;
    }

    .header-confirm img {
        margin-top: 3vh;
        height: 2vh;
        width: 2vh;
    }

    .print,
    .pdf,
    .logo-header,
    .nav-right {
        display: none;
    }

    nav,
    footer {
        display: none;
    }
</style>
@endsection

@section('content')

@foreach($usulan as $dataUsulan)
<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Surat Pengajuan Usulan {{ $dataUsulan->jenis_form_usulan }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Surat Pengajuan Usulan {{ $dataUsulan->jenis_form_usulan }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group" style="margin-right: 15%;margin-left: 15%;">
                <a href="{{ url('super-user/aadb/usulan-pengadaan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="{{ url('super-user/aadb/usulan/print-surat-usulan/'. $dataUsulan->kode_otp_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
            </div>
            <div class="col-md-12 form-group ">
                <div style="background-color: white;margin-right: 15%;margin-left: 15%;padding:2%;">
                    <div class="row">
                        <div class="col-md-2">
                            <h2 class="page-header ml-4">
                                <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                            </h2>
                        </div>
                        <div class="col-md-8 text-center">
                            <h2 class="page-header">
                                <h5 style="font-size: 24px;text-transform:uppercase;"><b>surat pengajuan usulan</b></h5>
                                <h5 style="font-size: 24px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                                <p style="font-size: 16px;"><i>Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Blok A, 2nd Floor, Jakarta 12950<br>Telp.: (62-21) 5201587, 5201591 Fax. (62-21) 5201591</i></p>
                            </h2>
                        </div>
                        <div class="col-md-2">
                            <h2 class="page-header">
                                <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
                            </h2>
                        </div>
                        <div class="col-md-12">
                            <hr style="border-width: medium;border-color: black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group text-capitalize">
                            <div class="form-group row mb-4">
                                <div class="col-md-12">pengajuan usulan {{ $dataUsulan->jenis_form_usulan }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Pengusul</div>
                                <div class="col-md-10">: {{ $dataUsulan->nama_pegawai }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Jabatan</div>
                                <div class="col-md-9">: {{ $dataUsulan->jabatan.' '.$dataUsulan->keterangan_pegawai }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Unit Kerja</div>
                                <div class="col-md-9">: {{ $dataUsulan->unit_kerja }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Tanggal Usulan</div>
                                <div class="col-md-9">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                            </div>
                            @if($dataUsulan->rencana_pengguna != null)
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Rencana Pengguna</div>
                                <div class="col-md-9">: {{ $dataUsulan->rencana_pengguna }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 table-responsive mt-4">
                            @if($dataUsulan->jenis_form == '1')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis AADB</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Merk</th>
                                        <th>Tipe</th>
                                        <th>Tahun Perolehan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($dataUsulan->usulanKendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>{{ $dataKendaraan->jenis_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->merk_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif($dataUsulan->jenis_form == '2')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>Kilometer Terakhir</th>
                                        <th>Tanggal Servis Terakhir</th>
                                        <th>Jatuh Tempo Servis</th>
                                        <th>Tanggal Ganti Oli Terakhir</th>
                                        <th>Jatuh Tempo Ganti Oli</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($dataUsulan->usulanServis as $dataServis)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataServis->merk_kendaraan.' '.$dataServis->tipe_kendaraan }}</td>
                                        <td>{{ $dataServis->kilometer_terakhir }}</td>
                                        <td>{{ $dataServis->tgl_servis_terakhir }}</td>
                                        <td>{{ $dataServis->jatuh_tempo_servis }}</td>
                                        <td>{{ $dataServis->tgl_ganti_oli_terakhir }}</td>
                                        <td>{{ $dataServis->jatuh_tempo_ganti_oli }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif($dataUsulan->jenis_form == '3')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>No. Plat</th>
                                        <th>Masa Berlaku STNK</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($dataUsulan->usulanSTNK as $dataSTNK)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataSTNK->merk_kendaraan.' '.$dataSTNK->tipe_kendaraan }}</td>
                                        <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif($dataUsulan->jenis_form == '4')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>Jenis BBM</th>
                                        <th>Harga Perliter</th>
                                        <th>Kebutuhan BBM</th>
                                        <th>Total</th>
                                        <th>Bulan Pengadaan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($dataUsulan->usulanVoucher as $dataVoucher)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataVoucher->merk_kendaraan.' '.$dataVoucher->tipe_kendaraan }}</td>
                                        <td>{{ $dataVoucher->jenis_bbm }}</td>
                                        <td>Rp {{ number_format($dataVoucher->harga_perliter, 0, ',', '.') }}</td>
                                        <td>{{ $dataVoucher->jumlah_kebutuhan }} L</td>
                                        <td>Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                        <div class="col-md-6 form-group" style="margin-top: 10%;">
                            <div style="margin-left:30%;text-transform:capitalize;">
                                <label>Pengusul, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                                <p style="margin-top: 13%;margin-left:17%;">
                                    {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://www.siporsat-kemenkes.com/bast/'.$dataUsulan->kode_otp_bast) !!}
                                </p>
                                <div style="margin-top: 5%;">
                                    <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                                </div>
                                </p>
                            </div>
                        </div>
                        <!-- <div class="col-md-6 form-group" style="margin-top: 10%;">
                            <div style="margin-right:20%;margin-left:10%;text-transform:capitalize;">
                                <label>yang menerima, <br> pengusul</label>
                                <p style="margin-top: 13%;margin-left:17%;">
                                    {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://milkshake.app/'.$dataUsulan->kode_otp_bast) !!}
                                </p>
                                <div style="margin-top: 5%;">
                                    <label class="text-underline">{{ $dataUsulan->nama_pegawai }}</label>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endforeach


@endsection
