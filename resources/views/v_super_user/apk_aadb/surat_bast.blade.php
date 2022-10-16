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
            <div class="col-sm-6 text-capitalize">
                <h1>berita acara serah terima {{ $bast->jenis_form_usulan }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">BAST</li>
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
                <a href="{{ url('super-user/aadb/usulan/daftar/seluruh-usulan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($bast->status_proses_id == 5)
                <a href="{{ url('super-user/aadb/surat/print-surat-bast/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
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
                        <div class="col-md-12 form-group text-capitalize">
                            <div class="form-group row mb-4">
                                <div class="col-md-12">berita acara serah terima (BAST) {{ $bast->jenis_form_usulan }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Pengusul</div>
                                <div class="col-md-10">: {{ $bast->nama_pegawai }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Jabatan</div>
                                <div class="col-md-9">: {{ $bast->jabatan.' '.$bast->keterangan_pegawai }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Unit Kerja</div>
                                <div class="col-md-9">: {{ $bast->unit_kerja }}</div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Tanggal Usulan</div>
                                <div class="col-md-9">: {{ \Carbon\Carbon::parse($bast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                            </div>
                            @if($bast->rencana_pengguna != null)
                            <div class="form-group row mb-0">
                                <div class="col-md-2">Rencana Pengguna</div>
                                <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="col-12 table-responsive mt-4">
                            @if($bast->jenis_form == '1')
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if($jenisAadb->jenis_aadb == 'bmn')
                                        <th>Kode Barang</th>
                                        @endif
                                        <th>Jenis AADB</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>No. Plat</th>
                                        <th>Merk</th>
                                        <th>Tipe</th>
                                        <th>Tahun Perolehan</th>
                                        @if($jenisAadb->jenis_aadb == 'sewa')
                                        <th>Mulai Sewa</th>
                                        <th>Penyedia</th>
                                        @endif
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($kendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        @if($dataKendaraan->jenis_aadb == 'bmn')
                                            <td>{{ $dataKendaraan->kode_barang }}</td>
                                        @endif
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>{{ $dataKendaraan->jenis_kendaraan }}</td>
                                        <td class="text-uppercase">{{ $dataKendaraan->no_plat_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->merk_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                        @if($dataKendaraan->jenis_aadb == 'sewa')
                                            @foreach($dataKendaraan->kendaraanSewa as $dataSewa)
                                                <td>{{ $dataSewa->mulai_sewa }}</td>
                                                <td>{{ $dataSewa->penyedia }}</td>
                                            @endforeach
                                        @endif

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif($bast->jenis_form == '2')
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
                                    @foreach($bast->usulanServis as $dataServis)
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
                            @elseif($bast->jenis_form == '3')
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
                                    @foreach($bast->usulanSTNK as $dataSTNK)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataSTNK->merk_kendaraan.' '.$dataSTNK->tipe_kendaraan }}</td>
                                        <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @elseif($bast->jenis_form == '4')
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
                                    @foreach($bast->usulanVoucher as $dataVoucher)
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
                                <p style="margin-top: 8vh;">
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
