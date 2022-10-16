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
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/pengajuan/daftar/semua-pengajuan') }}">Daftar Pengajuan Barang</a></li>
                    <li class="breadcrumb-item active">BAST {{ $bast->kode_otp_bast }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group">
                <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($bast->status_proses_id == 5)
                <a href="{{ url('super-user/oldat/surat/print-surat-bast/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
                @endif
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
                            <div class="form-group row mb-3 text-capitalize">
                                <div class="col-md-12">berita acara serah terima {{ $bast->jenis_form }} barang</div>
                            </div>
                            <p class="m-0 text-capitalize">
                                Pengusul <span style="margin-left: 9%;"> : {{ $bast->nama_pegawai }} </span> <br>
                                Jabatan <span style="margin-left: 9.8%;"> : {{ $bast->jabatan.' '.$bast->tim_kerja }}</span> <br>
                                Unit Kerja <span style="margin-left: 8.5%;"> : {{ $bast->unit_kerja }}</span> <br>
                                Tanggal Usulan <span style="margin-left: 4.7%;"> : {{ \Carbon\Carbon::parse($bast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</span> <br>
                                Rencana Pengguna <span style="margin-left: 2%;"> : {{ $bast->rencana_pengguna }}</span> <br>
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
                                    @if($bast->jenis_form == 'pengadaan')
                                    @foreach($bast->barang as $dataBarang)
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
                                    @else
                                    @foreach($bast->detailPerbaikan as $dataBarang)
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
                        <div class="col-md-4 form-group" style="margin-top: 15vh;">
                            <div class="text-center text-capitalize">
                                <label>Yang Menyerahkan, <br> Pejabat Pembuat Komitmen (PPK)</label>
                                <p style="margin-top: 8vh;">
                                    {!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_ppk) !!}
                                </p>
                                <label class="text-underline">Marten Avero</label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group" style="margin-top: 15vh;">
                            <div class="text-center text-capitalize">
                                <label>Yang Menerima, <br> {{ $bast->jabatan.' '.$bast->tim_kerja }}</label>
                                <p style="margin-top: 5vh;">
                                    {!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}
                                </p>
                                <label class="text-underline">{{ $bast->nama_pegawai }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 form-group" style="margin-top: 15vh;">
                            <div class="text-center text-capitalize">
                                <label>Mengetahui, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                                <p style="margin-top: 8vh;">
                                    {!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_kabag) !!}
                                </p>
                                <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
