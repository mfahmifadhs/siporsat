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
        <div class="row text-capitalize">
            <div class="col-sm-6">
                <h4>Surat Usulan Pengajuan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/'.$modul.'/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Surat Usulan</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                <a href="{{ url('admin-user/'.$modul.'/dashboard') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                <a href="{{ url('admin-user/cetak-surat/usulan-'. $modul.'/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
                @endif

                @if($modul == 'atk' && $form->jenis_form == 'pengadaan' && $usulan->status_pengajuan_id == null)
                @if($usulan->otp_usulan_kabag == null || $usulan->otp_usulan_pimpinan == null)
                <form action="{{ url('admin-user/atk/usulan/preview-pengadaan/preview') }}" class="btn btn-primary">
                    <input type="hidden" name="id_form_usulan" value="{{ $form->id_form_usulan }}">
                    <button class="btn btn-navbar btn-xs" type="submit">
                        <i class="fas fa-edit fa-1x" style="color: white;"></i>
                    </button>
                </form>
                @endif
                @endif
            </div>

            @if ($usulan->status_pengajuan_id == 2)
            <div class="col-md-12 mb-2">
                <div class="border border-danger">
                    <b class="text-danger p-2">USULAN DITOLAK : {{ $usulan->keterangan }}</b>
                </div>
            </div>
            @endif
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
                                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>{{ $usulan->unit_utama }}</b></h5>
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
                                <div class="form-group row mb-3 text-center">
                                    <div class="col-md-12 text-uppercase">
                                        usulan pengajuan <br>
                                        nomor surat :
                                        @if ($usulan->status_pengajuan_id == 1)
                                        {{ $usulan->no_surat_usulan }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Tanggal Usulan</div>
                                    <div class="col-md-10">: {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Pengusul</div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Jabatan</div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Unit Kerja</div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->unit_kerja)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Total Pengajuan</div>
                                    <div class="col-md-9">: {{ $usulan->total_pengajuan }}</div>
                                </div>
                                @if($usulan->rencana_pengguna != null)
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Rencana Pengguna</div>
                                    <div class="col-md-10">: {{ $usulan->rencana_pengguna }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="col-12 mt-4 mb-5">
                                @if ($modul == 'oldat')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            @if($usulan->jenis_form == 'pengadaan')
                                            <th>Spesifikasi</th>
                                            <th>Jumlah</th>
                                            <th>Estimasi Biaya</th>
                                            @else
                                            <th>Tahun Perolehan</th>
                                            <th>Keterangan Kerusakan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if($usulan->jenis_form == 'pengadaan')
                                        @foreach($usulan->detailPengadaan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori_barang_id }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_barang }}</td>
                                            <td>{!! nl2br(e($dataBarang->spesifikasi_barang)) !!}</td>
                                            <td>{{ $dataBarang->jumlah_barang.' '.$dataBarang->satuan_barang }}</td>
                                            <td>Rp {{ number_format($dataBarang->estimasi_biaya, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($usulan->detailPerbaikan as $dataBarang)
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
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if ($form->jenis_form == 'pengadaan')
                                        @foreach($usulan->pengadaanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td>{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                                            <td>{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                                            <td>
                                                {{ $dataAtk->status }}
                                                @if ($dataAtk->keterangan != null)
                                                ({{ $dataAtk->keterangan }})
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($usulan->permintaanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td>{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                                            <td>{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                                            <td>
                                                {{ $dataAtk->status }}
                                                @if ($dataAtk->keterangan != null)
                                                ({{ $dataAtk->keterangan }})
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                @elseif ($modul == 'gdn')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 1%;">No</th>
                                            <th style="width: 20%;">Bidang Kerusakan</th>
                                            <th style="width: 20%;">Lokasi Perbaikan</th>
                                            <th>Lokasi Spesifik</th>
                                            <th style="width: 20%;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->detailUsulanGdn as $dataGdn)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataGdn->bid_kerusakan }}</td>
                                            <td class="text-uppercase">{{ $dataGdn->lokasi_bangunan }}</td>
                                            <td>{!! nl2br(e($dataGdn->lokasi_spesifik)) !!}</td>
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
                                        @foreach($usulan->detailUsulanUkt as $dataUkt)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                            <td>{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</td>
                                            <td>{!! nl2br(e($dataUkt->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif ($modul == 'aadb')
                                @if($usulan->jenis_form == '1')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis AADB</th>
                                            <th>Jenis Kendaraan</th>
                                            <th>Merk/Tipe</th>
                                            <th>Kualifikasi</th>
                                            <th>Jumlah</th>
                                            <th>Tahun</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->usulanKendaraan as $dataKendaraan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                            <td>{{ ucfirst(strtolower($dataKendaraan->jenis_kendaraan)) }}</td>
                                            <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
                                            <td>Kendaraan {{ $dataKendaraan->kualifikasi }}</td>
                                            <td>{{ $dataKendaraan->jumlah_pengajuan }} UNIT</td>
                                            <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($usulan->jenis_form == '2')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:5%;">No</th>
                                            <th style="width:10%;">No. Plat</th>
                                            <th style="width:20%;">Kendaraan</th>
                                            <th>Kilometer</th>
                                            <th style="width:15%;">Jadwal Servis</th>
                                            <th style="width:15%;">Jadwal Ganti Oli</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->usulanServis as $dataServis)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataServis->no_plat_kendaraan }}</td>
                                            <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
                                            <td>{{ (int) $dataServis->kilometer_terakhir }} Km</td>
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
                                @elseif($usulan->jenis_form == '3')
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
                                        @foreach($usulan->usulanSTNK as $dataSTNK)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                            <td>{{ $dataSTNK->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataSTNK->pengguna }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($usulan->jenis_form == '4')
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
                                        @foreach($usulan->usulanVoucher as $dataVoucher)
                                        @if($dataVoucher->status_pengajuan == 'true')
                                        <tr class="text-uppercase">
                                            <td>{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                            <td>{{ $dataVoucher->jenis_aadb }}</td>
                                            <td>{{ $dataVoucher->no_plat_kendaraan }}</td>
                                            <td>{{ $dataVoucher->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataVoucher->kualifikasi }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                @endif
                            </div>
                            <div class="col-md-12 text-capitalize">
                                <div class="row text-center">
                                    @if ($usulan->otp_usulan_pengusul != null)
                                    <label class="col-sm-6">Yang Mengusulkan, <br> {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</label>
                                    @endif
                                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                                    <label class="col-sm-6">Disetujui Oleh, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="row text-center">
                                    @if ($usulan->otp_usulan_pengusul != null)
                                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan-'.$modul.'/'.$usulan->otp_usulan_pengusul) !!}</label>
                                    @endif
                                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan-'.$modul.'/'.$usulan->otp_usulan_kabag) !!}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="row text-center">
                                    @if ($usulan->otp_usulan_pengusul != null)
                                    <label class="col-sm-6">{{ $usulan->nama_pegawai }}</label>
                                    @endif
                                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null )
                                    <label class="col-sm-6">{{ $pimpinan->nama_pegawai }}</label>
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