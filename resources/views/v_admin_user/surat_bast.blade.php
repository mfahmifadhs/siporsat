@extends('v_admin_user.layout.app')

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
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/'.$modul.'/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Surat Berita Acara Serah Terima</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

@php
if($modul == 'oldat' && $bast->jenis_form == 'pengadaan') {
$namaPpk = 'Anggriany Aprilia Sampe, ST, MAP';
$jabatanPpk = 'Pejabat Pembuatan Komitmen Belanja Modal';
} else {
$namaPpk = 'Marten Avero, Skm';
$jabatanPpk = 'Pejabat Pembuatan Komitmen Belanja Operasional';
}
@endphp

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 form-group">
                <a href="{{ url('admin-user/'.$modul.'/dashboard') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($bast->otp_usulan_kabag != null || $bast->otp_usulan_pimpinan != null)
                <a href="{{ url('admin-user/cetak-surat/bast-'. $modul.'/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
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
                                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $bast->unit_utama }}</b></h5>
                                    <p style="font-size: 20px;"><i>Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Blok A, 2nd Floor, Jakarta 12950<br>Telp.: (62-21) 5201587, 5201591 Fax. (62-21) 5201591</i></p>
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
                        <div class="row text-capitalize">
                            <div class="col-md-12 form-group">
                                <div class="form-group row mb-3 text-center">
                                    <div class="col-md-12 text-uppercase">
                                        berita acara serah terima <br>
                                        nomor surat : {{ $bast->no_surat_bast }}
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        Pada Hari Ini, Tanggal {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }} bertempat Di Kantor Pusat
                                        Kementerian Kesehatan Republik Indonesia, kami yang bertanda tangan dibawah Ini:
                                    </div>
                                </div>
                                <div class="form-group row mb-0 py-3">
                                    <div class="col-md-2"><span class="ml-5">Nama</span></div>
                                    <div class="col-md-10">: {{ $namaPpk }}</div>
                                    <div class="col-md-2"><span class="ml-5">Jabatan</span></div>
                                    <div class="col-md-10">: {{ $jabatanPpk }}
                                    </div>
                                    <div class="col-md-2"><span class="ml-5">Unit Kerja</span></div>
                                    <div class="col-md-10">: Biro Umum</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        Dalam Berita Acara ini bertindak untuk dan atas nama Biro Umum Sekretariat Jenderal, {{ $jabatanPpk }}
                                        selaku yang menyerahkan, yang selanjutnya disebut <b>PIHAK PERTAMA</b>.
                                    </div>
                                </div>
                                <div class="form-group row mb-0 py-3">
                                    <div class="col-md-2"><span class="ml-5">Nama</span></div>
                                    <div class="col-md-10">: {{ $bast->nama_pegawai }}</div>
                                    <div class="col-md-2"><span class="ml-5">Jabatan</span></div>
                                    <div class="col-md-10">: {{ $bast->keterangan_pegawai }}
                                    </div>
                                    <div class="col-md-2"><span class="ml-5">Unit Kerja</span></div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                </div>
                                <div class="form-group row mb-0 text-justify">
                                    <div class="col-md-12">
                                        Dalam Berita Acara ini bertindak untuk dan atas nama {{ ucfirst(strtolower($bast->unit_kerja)) }}
                                        Selaku Penerima, yang selanjutnya disebut <b>PIHAK KEDUA</b>.
                                    </div>
                                </div>
                                <div class="form-group row mb-0 text-justify">
                                    <div class="col-md-12 mt-4">
                                        Bahwa <b>PIHAK PERTAMA</b> telah menyerahkan barang/pekerjaan dari/kepada <b>PIHAK KEDUA</b>
                                        dengan rincian sebagai berikut:
                                    </div>
                                </div>
                                <!-- <div class="form-group row mb-0">
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
                                    <div class="col-md-9">: {{ $bast->total_pengajuan }}</div>
                                </div>
                                @if($bast->rencana_pengguna != null)
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Rencana Pengguna</div>
                                    <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                                </div>
                                @endif
                                <div class="form-group row mb-0">
                                    <div class="col-md-12 text-justify mt-4">
                                        Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                                        dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                                        berfungsi normal sebagaimana mestinya.
                                    </div>
                                </div> -->
                            </div>
                            <div class="col-12 table-responsiv mb-5">
                                @if ($modul == 'oldat')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            @if($bast->jenis_form == 'pengadaan')
                                            <th>Spesifikasi</th>
                                            <th>Tahun Perolehan </th>
                                            @else
                                            <th>Tahun Perolehan</th>
                                            <th>Keterangan Kerusakan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if($bast->jenis_form == 'pengadaan')
                                        @foreach($bast->detailPengadaan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori_barang_id }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_barang }}</td>
                                            <td>{{ $dataBarang->jumlah_barang.' '.$dataBarang->satuan_barang }}</td>
                                            <td>Rp {{ number_format($dataBarang->estimasi_biaya, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($bast->detailPerbaikan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kode_barang.'.'.$dataBarang->nup_barang }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_tipe_barang }}</td>
                                            <td>{{ $dataBarang->tahun_perolehan }}</td>
                                            <td>{{ $dataBarang->keterangan_perbaikan }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                @elseif ($modul == 'atk')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Jenis Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-center">Diserahkan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->permintaanAtk as $dataAtk)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_disetujui }}</td>
                                            <td class="text-center">{{ $dataAtk->satuan }}</td>
                                            <td class="text-center">
                                                @if ($dataAtk->status_penyerahan == 'true')
                                                ☑️
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif ($modul == 'gdn')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Lokasi Perbaikan</th>
                                            <th>Lokasi Spesifik</th>
                                            <th>Bidang Kerusakan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->detailUsulanGdn as $dataGdn)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataGdn->lokasi_bangunan }}</td>
                                            <td>{!! nl2br(e($dataGdn->lokasi_spesifik)) !!}</td>
                                            <td>{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</td>
                                            <td>{!! nl2br(e($dataGdn->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif ($modul == 'ukt')
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
                                    <tbody class="text-uppercase">
                                        @foreach($bast->detailUsulanUkt as $dataUkt)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                            <td>{!! $dataUkt->spesifikasi_pekerjaan !!}</td>
                                            <td>{{ $dataUkt->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif ($modul == 'aadb')
                                @if($bast->jenis_form == '1')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            @if($jenisAadb->jenis_aadb == 'bmn')
                                            <th>Kode Barang</th>
                                            @endif
                                            <th>Jenis AADB</th>
                                            <th>Nama Kendaraan</th>
                                            <th>Merk/Tipe</th>
                                            @if($jenisAadb->jenis_aadb == 'sewa')
                                            <th>Mulai Sewa</th>
                                            <th>Penyedia</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-capitalize">
                                        @foreach($bast->kendaraan as $dataKendaraan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            @if($dataKendaraan->jenis_aadb == 'bmn')
                                            <td>{{ $dataKendaraan->kode_barang }}</td>
                                            @endif
                                            <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                            <td>{{ ucfirst(strtolower($dataKendaraan->jenis_kendaraan)) }}</td>
                                            <td>
                                                @if($jenisAadb->jenis_aadb == 'bmn')
                                                <span class="text-uppercase">{{ $dataKendaraan->no_plat_kendaraan }}</span> <br>
                                                @endif
                                                {{ $dataKendaraan->merk_tipe_kendaraan.' '.$dataKendaraan->tahun_kendaraan }}
                                            </td>
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
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Kilometer</th>
                                            <th>Jadwal Servis</th>
                                            <th>Jadwal Ganti Oli</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->usulanServis as $dataServis)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataServis->no_plat_kendaraan }}</td>
                                            <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataServis->kilometer_terakhir }} Km</td>
                                            <td>
                                                Terakhir Servis : <br>
                                                {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                                Jatuh Tempo Servis : <br>
                                                {{ (int) $dataServis->jatuh_tempo_servis }} Km
                                            </td>
                                            <td>
                                                Terakhir Ganti Oli : <br>
                                                {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                                Jatuh Tempo Servis : <br>
                                                {{ (int) $dataServis->jatuh_tempo_ganti_oli }} Km
                                            </td>
                                            <td>{{ $dataServis->keterangan_servis }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($bast->jenis_form == '3')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Pengguna</th>
                                            <th>Masa Berlaku STNK</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->usulanSTNK as $dataSTNK)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                            <td>{{ $dataSTNK->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataSTNK->pengguna }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_baru)->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($bast->jenis_form == '4')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan Pengadaan</th>
                                            <th>Jenis AADB</th>
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Kualifikasi</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-capitalize">
                                        @foreach($bast->usulanVoucher as $dataVoucher)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                            <td>{{ $dataVoucher->jenis_aadb }}</td>
                                            <td>{{ $dataVoucher->no_plat_kendaraan }}</td>
                                            <td>{{ $dataVoucher->merk_tipe_kendaraan }}</td>
                                            <td>Kendaraan {{ $dataVoucher->kualifikasi }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen</label>
                                    @if($bast->status_proses_id >= 4)
                                    <label class="col-sm-4">Yang Menerima, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                                    @endif
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">Mengetahui, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-'.$modul.'/'.$bast->otp_bast_ppk) !!}</label>
                                    @if($bast->status_proses_id >= 4)
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-'.$modul.'/'.$bast->otp_bast_pengusul) !!}</label>
                                    @endif
                                    @if($bast->status_proses_id == 5)
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-'.$modul.'/'.$bast->otp_bast_kabag) !!}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Marten Avero, Skm</label>
                                    @if($bast->status_proses_id >= 4)
                                    <label class="col-sm-4">{{ $bast->nama_pegawai }}</label>
                                    @endif
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
