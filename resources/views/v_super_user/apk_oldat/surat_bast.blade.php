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
    <div class="container">
        <div class="row">
            <div class="col-md-12 form-group">
                <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if (Auth::user()->pegawai->jabatan_id == 2 && $bast->status_proses_id == 4)
                <a href="{{ url('super-user/verif/usulan-oldat/'. $bast->id_form_usulan) }}" class="btn btn-success" title="Konfirmasi BAST" onclick="return confirm('Konfirmasi BAST')">
                    <i class="fas fa-file-signature"></i>
                </a>
                @endif
                @if($bast->status_proses_id == 5)
                <a href="{{ url('super-user/oldat/surat/print-surat-bast/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
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
                                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>{{ $bast->unit_kerja.' '.$bast->unit_utama }}</b></h5>
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
                                <div class="form-group row mb-4">
                                    <div class="col-md-12 text-uppercase text-center">{{ $bast->no_surat_bast }}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2">Pengusul</label>
                                    <div class="col-sm-10">:{{ $bast->nama_pegawai }}</div>
                                    <label class="col-sm-2">Jabatan</label>
                                    <div class="col-sm-10">: {{ $bast->keterangan_pegawai.' '.$bast->tim_kerja }}</div>
                                    <label class="col-sm-2">Unit Kerja</label>
                                    <div class="col-sm-10">: {{ $bast->unit_kerja }}</div>
                                    <label class="col-sm-2">Tanggal Bast</label>
                                    <div class="col-sm-10">: {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                    @if($bast->jenis_form == 'pengadaan')
                                    <div class="col-md-2"><label>Rencana Pengguna </label></div>
                                    <div class="col-md-10">: {{ $bast->rencana_pengguna }}</div>
                                    @else
                                    <div class="col-md-2"><label>Biaya Perbaikan </label></div>
                                    <div class="col-md-10">: Rp {{ number_format($bast->total_biaya, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                                <p class="text-justify mt-4">
                                    Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                                    dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                                    berfungsi normal sebagaimana mestinya.
                                </p>
                            </div>
                            <div class="col-12 table-responsiv mb-5">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td style="width: 15%;">Jenis Barang</td>
                                            <td style="width: 20%;">Merk Barang</td>
                                            @if($bast->jenis_form == 'pengadaan')
                                            <td style="width: 40%;">Spesifikasi</td>
                                            <td>Jumlah</td>
                                            <td>Satuan</td>
                                            <td style="width: 25%;">Tahun Perolehan </td>
                                            @else
                                            <td style="width: 25%;">Pengguna</td>
                                            <td style="width: 25%;">Unit Kerja</td>
                                            <td style="width: 25%;">Tahun Perolehan</td>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if($bast->jenis_form == 'pengadaan')
                                        @foreach($bast->barang as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_tipe_barang }}</td>
                                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                            <td>{{ $dataBarang->jumlah_barang }}</td>
                                            <td>{{ $dataBarang->satuan_barang }}</td>
                                            <td>Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($bast->detailPerbaikan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_tipe_barang }}</td>
                                            <td>{{ $dataBarang->nama_pegawai }}</td>
                                            <td>{{ $dataBarang->unit_kerja }}</td>
                                            <td>{{ $dataBarang->tahun_perolehan }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen (PPK)</label>
                                    <label class="col-sm-4">Yang Menerima, <br> Pengusul</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">Mengetahui, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_ppk) !!}</label>
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_kabag) !!}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Marten Avero, Skm</label>
                                    <label class="col-sm-4">{{ $bast->nama_pegawai }}</label>
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">{{ $pimpinan->nama_pegawai }}</label>
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
