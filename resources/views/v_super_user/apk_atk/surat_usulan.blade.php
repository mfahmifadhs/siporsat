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
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
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
                <a href="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="{{ url('super-user/atk/surat/print-surat-usulan/'. $dataUsulan->otp_usulan_pengusul) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
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
                                <div class="col-md-12 text-uppercase text-center">{{ $dataUsulan->otp_usulan_pengusul }}</div>
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
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Total Pengajuan</div>
                                <div class="col-md-9">: {{ $dataUsulan->total_pengajuan }} kendaraan</div>
                            </div>
                            @if($dataUsulan->rencana_pengguna != null)
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Rencana Pengguna</div>
                                <div class="col-md-9">: {{ $dataUsulan->rencana_pengguna }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 table-responsive mt-4">
                            @if ($dataUsulan->jenis_form == 'pengadaan')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis AADB</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($dataUsulan->detailUsulanAtk as $dataAtk)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataAtk->kategori_atk }}</td>
                                        <td>{{ $dataAtk->merk_atk }}</td>
                                        <td>{{ $dataAtk->jumlah_pengajuan }}</td>
                                        <td>{{ $dataAtk->satuan }}</td>
                                        <td>{{ $dataAtk->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                        <div class="col-md-7 form-group" style="margin-top: 15vh;">
                            <div class="text-center text-capitalize">
                                <label>Yang Mengusulkan, <br> {{ $dataUsulan->jabatan.' '.$dataUsulan->tim_kerja }}</label>
                                <p>{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$dataUsulan->otp_usulan_pengusul) !!}</p>
                                <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                            </div>
                        </div>
                        <!-- <div class="col-md-5 form-group" style="margin-top: 15vh;">
                            <div class="text-center text-capitalize">
                                <label>Disetujui Oleh, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                                <p>{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$dataUsulan->otp_bast_kabag) !!}</p>
                                <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
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
