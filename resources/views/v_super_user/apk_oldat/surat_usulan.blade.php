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
        <div class="row mb-2">
            <div class="col-sm-6 text-capitalize">
                <h1>surat usulan pengajuan {{ $usulan->jenis_form }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Pengajuan Barang</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="form-group row">
            <div class="col-md-12">

                <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}" class="btn btn-primary print">
                    <i class="fas fa-home"></i>
                </a>
                <a href="{{ url('super-user/oldat/surat/print-surat-usulan/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
            </div>
        </div>
        <div class="row" style="background-color: white;">
            <div class="col-md-12 form-group p-4">
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
                        <div class="form-group row mb-3">
                            <div class="col-md-12">pengajuan usulan {{ $usulan->jenis_form }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-2">Pengusul</div>
                            <div class="col-md-10">: {{ $usulan->nama_pegawai }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-2">Jabatan</div>
                            <div class="col-md-10">: {{ $usulan->jabatan.' '.$usulan->keterangan_pegawai }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-2">Unit Kerja</div>
                            <div class="col-md-10">: {{ $usulan->unit_kerja }}</div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-2">Tanggal Usulan</div>
                            <div class="col-md-10">: {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                        </div>
                        @if($usulan->rencana_pengguna != null)
                        <div class="form-group row mb-0">
                            <div class="col-md-2">Rencana Pengguna</div>
                            <div class="col-md-10">: {{ $usulan->rencana_pengguna }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Barang</th>
                                    <th>Spesifikasi</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    @if($usulan->jenis_form == 'pengadaan')
                                    <th>Estimasi Biaya</th>
                                    @else
                                    <th>Nilai Perolehan</th>
                                    @endif
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @if($usulan->jenis_form == 'pengadaan')
                                @foreach($usulan->detailPengadaan as $dataBarang)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $dataBarang->kategori_barang }}</td>
                                    <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                    <td>{{ $dataBarang->jumlah_barang }}</td>
                                    <td>{{ $dataBarang->satuan_barang }}</td>
                                    <td>Rp {{ number_format($dataBarang->estimasi_biaya, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                @else
                                @foreach($usulan->detailPerbaikan as $dataBarang)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $dataBarang->kategori_barang }}</td>
                                    <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                    <td>{{ $dataBarang->jumlah_barang }}</td>
                                    <td>{{ $dataBarang->satuan_barang }}</td>
                                    <td>Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7 form-group" style="margin-top: 15vh;">
                        <div class="text-center text-capitalize">
                            <label>Yang Mengusulkan, <br> {{ $usulan->jabatan.' '.$usulan->tim_kerja }}</label>
                            <p>{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$usulan->otp_usulan_pengusul) !!}</p>
                            <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                        </div>
                    </div>
                    <div class="col-md-5 form-group" style="margin-top: 15vh;">
                        <div class="text-center text-capitalize">
                            <label>Disetujui Oleh, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                            <p>{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$usulan->otp_usulan_kabag) !!}</p>
                            <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
