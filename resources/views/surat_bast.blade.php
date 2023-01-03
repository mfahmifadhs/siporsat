<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/select2/css/select2.min.css') }}">
</head>

<body>

    <!-- Content Header -->
    <section class="content-header">
        <div class="container">
            <div class="row text-capitalize">
                <div class="col-sm-6">
                    <h4>Berita Acara Serah Terima</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Berita Acara Serah Terima {{ $modul }}</li>
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
                <div class="col-md-12 form-group ">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 col-2">
                                    <h2 class="page-header mr-4">
                                        <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}" style="width: 250%;">
                                    </h2>
                                </div>
                                <div class="col-md-8 col-8 text-center">
                                    <h2 class="page-header">
                                        <h5 style="font-size: 10px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                                        <h5 style="font-size: 10px;text-transform:uppercase;"><b>{{ $bast->unit_utama }}</b></h5>
                                        <p style="font-size: 7px;"><i>Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Blok A, 2nd Floor, Jakarta 12950<br>Telp.: (62-21) 5201587, 5201591 Fax. (62-21) 5201591</i></p>
                                    </h2>
                                </div>
                                <div class="col-md-2 col-2">
                                    <h2 class="page-header">
                                        <img src="{{ asset('dist_admin/img/logo-germas.png') }}" class="img-fluid">
                                    </h2>
                                </div>
                                <div class="col-md-12">
                                    <hr style="border-width: thinc;border-color: black;margin-top:-2%;">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group text-capitalize" style="font-size: 8px;">
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Nomor Surat</div>
                                        <div class="col-md-10 col-9 text-uppercase">: {{ $bast->no_surat_usulan }}</div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Pengusul</div>
                                        <div class="col-md-10 col-9">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Jabatan</div>
                                        <div class="col-md-10 col-9">: {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Unit Kerja</div>
                                        <div class="col-md-10 col-9">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Tanggal Usulan</div>
                                        <div class="col-md-10 col-9">: {{ \Carbon\Carbon::parse($bast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Total Pengajuan</div>
                                        <div class="col-md-10 col-9">: {{ $bast->total_pengajuan }} ruangan</div>
                                    </div>
                                    @if($bast->rencana_pengguna != null)
                                    <div class="form-group row mb-0">
                                        <div class="col-md-2 col-3">Rencana Pengguna</div>
                                        <div class="col-md-10 col-9">: {{ $bast->rencana_pengguna }}</div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-12 mb-5">
                                    @if ($modul == 'oldat')
                                    <table class="table table-bordered m-0">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Barang</th>
                                                @if ($bast->jenis_form == 'pengadaan')
                                                <th>Keterangan Pengadaan</th>
                                                @else
                                                <th>Keterangan</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @if ($bast->jenis_form == 'pengadaan')
                                            @foreach($bast->detailPengadaan as $dataBarang)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $dataBarang->kategori_barang_id }} <br>
                                                    {{ $dataBarang->kategori_barang }} <br>
                                                    {{ $dataBarang->merk_barang }}
                                                </td>
                                                <td>
                                                    No. Kontrak : {{ $dataBarang->nomor_kontrak }} <br>
                                                    No. Kwitansi : {{ $dataBarang->nomor_kwitansi }} <br>
                                                    Nilai Kwitansi : Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            @foreach($bast->detailPerbaikan as $dataBarang)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>
                                                    {{ $dataBarang->kode_barang.'.'.$dataBarang->nup_barang }} <br>
                                                    {{ $dataBarang->kategori_barang }} <br>
                                                    {{ $dataBarang->merk_tipe_barang }}
                                                </td>
                                                <td>{{ $dataBarang->keterangan_perbaikan }}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    @elseif ($modul == 'atk')
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Merk/Tipe</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->detailUsulanAtk as $dataAtk)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dataAtk->kategori_atk }}</td>
                                                <td>{{ $dataAtk->merk_atk }}</td>
                                                <td>{{ $dataAtk->jumlah_pengajuan }}</td>
                                                <td>{{ $dataAtk->satuan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @elseif ($modul == 'gdn')
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Lokasi Perbaikan</th>
                                                <th>Lokasi Spesifik</th>
                                                <th>Bidang Kerusakan</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->detailUsulanGdn as $dataGdn)
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
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Lokasi Pekerjaan</th>
                                                <th>Spesifikasi Pekerjaan</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($usulan->detailUsulanUkt as $dataUkt)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                                <td>{{ $dataUkt->spesifikasi_pekerjaan }}</td>
                                                <td>{{ $dataUkt->keterangan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @elseif ($modul == 'aadb')
                                    @if($bast->jenis_form == '1')
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis AADB</th>
                                                <th>Jenis Kendaraan</th>
                                                <th>Merk/Tipe</th>
                                                <th>Tahun Perolehan</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->usulanKendaraan as $dataKendaraan)
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
                                    @elseif($bast->jenis_form == '2')
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
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
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->usulanServis as $dataServis)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
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
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Kendaraan</th>
                                                <th>No. Plat</th>
                                                <th>Masa Berlaku STNK</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->usulanSTNK as $dataSTNK)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dataSTNK->merk_tipe_kendaraan }}</td>
                                                <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @elseif($bast->jenis_form == '4')
                                    <table class="table table-bordered m-0 table-responsive">
                                        <thead style="font-size: 9px;">
                                            <tr>
                                                <th>No</th>
                                                <th>Kendaraan</th>
                                                <th>Bulan Pengadaan</th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody style="font-size: 8px;">
                                            @foreach($bast->usulanVoucher as $dataVoucher)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $dataVoucher->merk_tipe_kendaraan }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                    @endif
                                </div>
                                <div class="col-md-12 col-12 text-capitalize" style="font-size: 7px;">
                                    <div class="row text-center">
                                        <label class="col-sm-4 col-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen</label>
                                        <label class="col-sm-4 col-4">Yang Menerima, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                                        <label class="col-sm-4 col-4">Mengetahui, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 text-capitalize" style="font-size: 7px;">
                                    <div class="row text-center">
                                        <label class="col-sm-4 col-4">{!! QrCode::size(30)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                                        <label class="col-sm-4 col-4">{!! QrCode::size(30)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                                        <label class="col-sm-4 col-4">{!! QrCode::size(30)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 text-capitalize" style="font-size: 7px;">
                                    <div class="row text-center">
                                        <label class="col-sm-4 col-4">Marten Avero, Skm</label>
                                        <label class="col-sm-4 col-4">{{ ucfirst(strtolower($bast->nama_pegawai)) }}</label>
                                        <label class="col-sm-4 col-4">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>

</html>
