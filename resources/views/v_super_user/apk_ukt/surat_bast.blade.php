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

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/ukt/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Surat Pengajuan Usulan Gedung/Bangunan</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content text-capitalize">
    <div class="container">
        <div class="row">
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
            <div class="col-md-12 form-group">
                <a href="{{ url('super-user/ukt/dashboard') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if (Auth::user()->pegawai->jabatan_id == 2 && $bast->status_proses_id == 4)
                <a href="{{ url('super-user/verif/usulan-ukt/'. $bast->id_form_usulan) }}" class="btn btn-success" title="Konfirmasi BAST" onclick="return confirm('Konfirmasi BAST')">
                    <i class="fas fa-file-signature"></i>
                </a>
                @endif

                @if($bast->otp_bast_kabag != null)
                <a href="{{ url('super-user/ukt/surat/print-surat-bast/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
                @endif
            </div>
            <div class="col-md-12 form-group ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h2 class="page-header ml-4">
                                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                                </h2>
                            </div>
                            <div class="col-md-8 text-center">
                                <h2 class="page-header">
                                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>{{ $bast->unit_utama }}</b></h5>
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
                            <div class="col-md-12 form-group">
                                <div class="form-group row mb-3 text-center">
                                    <div class="col-md-12 text-uppercase">
                                        berita acara serah terima <br>
                                        nomor surat : {{ $bast->no_surat_bast }}
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Tanggal</div>
                                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Pengusul</div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Jabatan</div>
                                    <div class="col-md-9">: {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Unit Kerja</div>
                                    <div class="col-md-9">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Total Pengajuan</div>
                                    <div class="col-md-9">: {{ $bast->total_pengajuan }} pekerjaan</div>
                                </div>
                            </div>
                            <div class="col-12 mt-4 mb-5">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%;">No</th>
                                            <th style="width: 20%;">Pekerjaan</th>
                                            <th>Spesifikasi Pekerjaan</th>
                                            <th style="width: 15%;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->detailUsulanUkt as $dataUkt)
                                        <tr class="text-uppercase">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                            <td>{!! $dataUkt->spesifikasi_pekerjaan !!}</td>
                                            <td>{{ $dataUkt->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen</label>
                                    <label class="col-sm-4">Yang Menerima, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">Mengetahui, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_ppk) !!}</label>
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_pengusul) !!}</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_kabag) !!}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Marten Avero, Skm</label>
                                    <label class="col-sm-4">{{ ucfirst(strtolower($bast->nama_pegawai)) }}</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
