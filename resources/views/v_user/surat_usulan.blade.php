@extends('v_user.layout.app')

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
                    <li class="breadcrumb-item"><a href="{{ url('unit-kerja/'.$modul.'/dashboard') }}">Dashboard</a></li>
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
                <a href="{{ url('unit-kerja/'.$modul.'/dashboard') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                <a href="{{ url('unit-kerja/cetak-surat/usulan-'. $modul.'/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
                @endif

                @if($modul == 'atk' && $form->jenis_form == 'pengadaan' && $usulan->status_pengajuan_id == null)
                @if($usulan->otp_usulan_kabag == null || $usulan->otp_usulan_pimpinan == null)
                <form action="{{ url('unit-kerja/atk/usulan/preview-pengadaan/preview') }}" class="btn btn-primary">
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
                                        nomor surat : {{ $usulan->no_surat_usulan }}
                                    </div>
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
                                            @if ($form->jenis_form == 'pengadaan')
                                            <th>Jenis Barang</th>
                                            @endif
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            @if ($form->jenis_form == 'pengadaan')
                                            <th>Keterangan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if ($form->jenis_form == 'pengadaan')
                                        @foreach($usulan->pengadaanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td>{{ $dataAtk->jumlah }}</td>
                                            <td>{{ $dataAtk->satuan }}</td>
                                            <td>{{ $dataAtk->status.' '.$dataAtk->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($usulan->detailUsulanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->kategori_atk }}</td>
                                            @if ($dataAtk->atk_lain != null)
                                            <td>{{ $dataAtk->atk_lain }}</td>
                                            @else
                                            <td>{{ $dataAtk->merk_atk }}</td>
                                            @endif
                                            <td>{{ $dataAtk->jumlah_pengajuan }}</td>
                                            @if ($dataAtk->atk_lain != null)
                                            <td>{{ $dataAtk->satuan_detail }}</td>
                                            @else
                                            <td>{{ $dataAtk->satuan }}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @endif
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
                                        @foreach($usulan->detailUsulanGdn as $dataGdn)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataGdn->lokasi_bangunan }}</td>
                                            <td>{{ $dataGdn->lokasi_spesifik }}</td>
                                            <td>{{ $dataGdn->bid_kerusakan }}</td>
                                            <td>{{ $dataGdn->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif ($modul == 'ukt')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Lokasi Pekerjaan</th>
                                            <th>Spesifikasi Pekerjaan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-uppercase">
                                        @foreach($usulan->detailUsulanUkt as $dataUkt)
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
                                @if($usulan->jenis_form == '1')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis AADB</th>
                                            <th>Jenis Kendaraan</th>
                                            <th>Merk/Tipe</th>
                                            <th>Tahun Perolehan</th>
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
                                            <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @elseif($usulan->jenis_form == '2')
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kendaraan</th>
                                            <th>Kilometer Terakhir</th>
                                            <th>Servis Terakhir</th>
                                            <th>Jatuh Tempo Servis</th>
                                            <th>Ganti Oli Terakhir</th>
                                            <th>Jatuh Tempo Ganti Oli</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->usulanServis as $dataServis)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $dataServis->no_plat_kendaraan }} <br>
                                                {{ $dataServis->merk_tipe_kendaraan }}
                                                @if ($dataServis->pengguna != null)
                                                <br>{{ $dataServis->pengguna }}
                                                @endif
                                            </td>
                                            <td>{{ $dataServis->kilometer_terakhir }} KM</td>
                                            <td>{{ $dataServis->tgl_servis_terakhir }}</td>
                                            <td>{{ $dataServis->jatuh_tempo_servis }} KM</td>
                                            <td>{{ $dataServis->tgl_ganti_oli_terakhir }}</td>
                                            <td>{{ $dataServis->jatuh_tempo_ganti_oli }} KM</td>
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
                                            <th>Kendaraan</th>
                                            <th>Masa Berlaku STNK</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->usulanSTNK as $dataSTNK)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $dataSTNK->no_plat_kendaraan }} <br>
                                                {{ $dataSTNK->merk_tipe_kendaraan }}
                                                @if ($dataSTNK->pengguna != null)
                                                <br>{{ $dataSTNK->pengguna }}
                                                @endif
                                            </td>
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
                                            <th>Kendaraan</th>
                                            <th>Bulan Pengadaan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->usulanVoucher as $dataVoucher)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $dataVoucher->no_plat_kendaraan }} <br>
                                                {{ $dataVoucher->merk_tipe_kendaraan }}
                                                @if ($dataVoucher->pengguna != null)
                                                <br>{{ $dataVoucher->pengguna }}
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                        </tr>
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
                            <div class="col-md-12 mt-4 text-capitalize">
                                <div class="row text-center">
                                    @if ($usulan->otp_usulan_pengusul != null)
                                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.app/surat/usulan-'.$modul.'/'.$usulan->otp_usulan_pengusul) !!}</label>
                                    @endif
                                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.app/surat/usulan-'.$modul.'/'.$usulan->otp_usulan_kabag) !!}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mt-4 text-capitalize">
                                <div class="row text-center">
                                    @if ($usulan->otp_usulan_pengusul != null)
                                    <label class="col-sm-6">{{ ucfirst(strtolower($usulan->nama_pegawai)) }}</label>
                                    @endif
                                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null )
                                    <label class="col-sm-6">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
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
