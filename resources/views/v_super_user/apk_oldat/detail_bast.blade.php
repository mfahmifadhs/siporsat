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
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Berita Acara Serah Terima</h1>
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

@foreach($bast as $dataBast)
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group" style="margin-right: 15%;margin-left: 15%;">
                <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                <a href="{{ url('super-user/oldat/surat/pdf-bast/'. $dataBast->kode_otp_bast) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
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
                                <h5 style="font-size: 24px;text-transform:uppercase;"><b>berita acara serah terima</b></h5>
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
                        <div class="col-md-12 form-group">
                            <p class="m-0 text-capitalize">
                                Pengusul <span style="margin-left: 9%;"> : {{ $dataBast->nama_pegawai }} </span> <br>
                                Jabatan <span style="margin-left: 9.8%;"> : {{ $dataBast->jabatan.' '.$dataBast->tim_kerja }}</span> <br>
                                Unit Kerja <span style="margin-left: 8.5%;"> : {{ $dataBast->unit_kerja }}</span> <br>
                                Tanggal Usulan <span style="margin-left: 4.7%;"> : {{ \Carbon\Carbon::parse($dataBast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</span> <br>
                                Rencana Pengguna <span style="margin-left: 2%;"> : {{ $dataBast->rencana_pengguna }}</span> <br>
                            </p>
                            <p class="text-justify mt-4">
                                Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                                dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                                berfungsi normal sebagaimana mestinya.
                            </p>
                        </div>
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>NUP</th>
                                        <th>Jenis Barang</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @if($dataBast->jenis_form == 'pengadaan')
                                        @foreach($dataBast->detailPengadaan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori->  kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_barang }}</td>
                                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                            <td>{{ $dataBarang->jumlah_barang }}</td>
                                            <td>{{ $dataBarang->satuan_barang }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        @foreach($dataBast->detailPerbaikan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kode_barang }}</td>
                                            <td>{{ $dataBarang->nup_barang }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                            <td>{{ $dataBarang->jumlah_barang }}</td>
                                            <td>{{ $dataBarang->satuan_barang }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 form-group" style="margin-top: 10%;">
                            <div style="margin-left:30%;text-transform:capitalize;">
                                <label>yang menyerahkan, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                                <p style="margin-top: 13%;margin-left:17%;">
                                    {!! QrCode::size(100)->generate('https://www.siporsat-kemenkes.com/bast/'.$dataBast->kode_otp_bast) !!}
                                </p>
                                <div style="margin-top: 5%;">
                                    <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                                </div>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 form-group" style="margin-top: 10%;">
                            <div style="margin-right:20%;margin-left:10%;text-transform:capitalize;">
                                <label>yang menerima, <br> {{ $dataBast->jabatan.' '.$dataBast->tim_kerja }}</label>
                                <p style="margin-top: 5% ;">
                                    {!! QrCode::size(100)->generate('https://www.siporsat-kemenkes.app/bast/'.$dataBast->kode_otp_bast) !!}
                                </p>
                                <div style="margin-top: 5%;">
                                    <label class="text-underline">{{ $dataBast->nama_pegawai }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endforeach


@endsection
