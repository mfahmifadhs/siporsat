<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $usulan->id_form_usulan }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.min.css') }}">
    <style>
        @media print {
            .pagebreak {
                page-break-after: always;
            }

            .table-data {
                border: 1px solid;
                font-size: 20px;
            }

            .table-data th,
            .table-data td {
                border: 1px solid;
            }

            .table-data thead th,
            .table-data thead td {
                border: 1px solid;
            }
        }
    </style>
</head>

<body>
    <div class="">
        <div class="row">
            <div class="col-md-2">
                <h2 class="page-header ml-4">
                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                </h2>
            </div>
            <div class="col-md-8 text-center">
                <h2 class="page-header">
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</b></h5>
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $usulan->unit_utama }}</b></h5>
                    <p style="font-size: 18px;"><i>
                            Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Jakarta 12950 <br>
                            Telepon : (021) 5201590</i>
                    </p>
                </h2>
            </div>
            <div class="col-md-2">
                <h2 class="page-header">
                    <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
                </h2>
            </div>
            <div class="col-md-12">
                <hr style="border-width: medium;border-color: black;">
                <hr style="border-width: 1px;border-color: black;margin-top: -11px;">
            </div>
        </div>
        <div class="row" style="font-size: 20px;">
            <div class="col-md-9">
                <div class="form-group row">
                    <div class="col-md-2">Nomor</div>
                    <div class="col-md-10 text-uppercase">: {{ $usulan->no_surat_usulan }}</div>
                    <div class="col-md-2">Hal</div>
                    <div class="col-md-10 text-capitalize">:
                        @if ($modul == 'usulan-oldat')
                        {{ $usulan->jenis_form }} barang
                        @elseif ($modul == 'usulan-aadb')
                        {{ ucfirst(strtolower($usulan->jenis_form_usulan)) }} kendaraan
                        @elseif ($modul == 'usulan-atk')
                        {{ $usulan->jenis_form }} ATK
                        @elseif ($modul == 'usulan-gdn')
                        pemeliharaan gedung dan bangunan
                        @elseif ($modul == 'usulan-ukt')
                        permintaan kerumahtanggaan
                        @endif
                    </div>
                    <div class="col-md-2 mt-4">Pengusul</div>
                    <div class="col-md-10 text-capitalize mt-4">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }} </div>
                    <div class="col-md-2">Jabatan</div>
                    <div class="col-md-10">: {{ $usulan->keterangan_pegawai }} </div>
                    <div class="col-md-2">Unit Kerja</div>
                    <div class="col-md-10 text-capitalize">: {{ ucfirst(strtolower($usulan->unit_kerja)) }} </div>
                    <div class="col-md-2">Jumlah</div>
                    <div class="col-md-10 text-capitalize">:
                        {{ $usulan->total_pengajuan }}
                        @if ($modul == 'usulan-oldat' || $modul == 'usulan-atk')
                        barang
                        @elseif ($modul == 'usulan-aadb')
                        kendaraan
                        @elseif ($modul == 'usulan-gdn' || $modul == 'usulan-ukt')
                        pekerjaan
                        @endif
                    </div>
                    @if($usulan->rencana_pengguna != null)
                    <div class="col-md-2">Keterangan</div>
                    <div class="col-md-10 text-capitalize">:
                        {{ $usulan->rencana_pengguna }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="col-md-12">{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
            </div>
            <div class="col-12 table-responsive mt-4 mb-5">
                @if ($modul == 'usulan-oldat')
                <table class="table table-data m-0">
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
                @elseif ($modul == 'usulan-atk')
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width: 15%;">Nama Barang</th>
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
                @elseif ($modul == 'usulan-gdn')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-md-1 divTheadtd text-center p-2">No</div>
                        <div class="col-md-3 divTheadtd p-2">Bidang Kerusakan</div>
                        <div class="col-md-3 divTheadtd p-2">Lokasi Perbaikan</div>
                        <div class="col-md-3 divTheadtd p-2">Lokasi Spesifik</div>
                        <div class="col-md-2 p-2">Keterangan</div>
                    </div>
                    @foreach($usulan->detailUsulanGdn as $i => $dataGdn)
                    <div class="row divTbody">
                        <div class="col-md-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</div>
                        <div class="col-md-3 divTbodytd">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</div>
                        <div class="col-md-2 divTbodytd">{!! nl2br(e($dataGdn->keterangan )) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'usulan-ukt')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-md-1 divTheadtd text-center p-2">No</div>
                        <div class="col-md-3 divTheadtd p-2">Pekerjaan</div>
                        <div class="col-md-5 divTheadtd p-2">Spesifikasi Pekerjaan</div>
                        <div class="col-md-3 p-2">Keterangan</div>
                    </div>
                    @foreach($usulan->detailUsulanUkt as $i => $dataUkt)
                    <div class="row divTbody">
                        <div class="col-md-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</div>
                        <div class="col-md-5 divTbodytd">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                        <div class="col-md-3 divTbodytd">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'usulan-aadb')
                @if($usulan->jenis_form == '1')
                <table class="table table-data m-0 small text-capitalize">
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
                            <td>{{ ucfirst(strtolower($dataKendaraan->jenis_aadb)) }}</td>
                            <td>{{ ucfirst(strtolower($dataKendaraan->jenis_kendaraan)) }}</td>
                            <td>{{ ucfirst(strtolower($dataKendaraan->merk_tipe_kendaraan)) }}</td>
                            <td>Kendaraan {{ ucfirst(strtolower($dataKendaraan->kualifikasi)) }}</td>
                            <td>{{ $dataKendaraan->jumlah_pengajuan }} UNIT</td>
                            <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @elseif($usulan->jenis_form == '2')
                <table class="table table-data m-0 small text-capitalize">
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
                            <td class="text-uppercase">{{ $dataServis->no_plat_kendaraan }}</td>
                            <td>{{ ucfirst(strtolower($dataServis->merk_tipe_kendaraan)) }}</td>
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
                @elseif($usulan->jenis_form == '3')
                <table class="table table-data m-0 small text-capitalize">
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
                            <td class="text-uppercase">{{ $dataSTNK->no_plat_kendaraan }}</td>
                            <td>{{ ucfirst(strtolower($dataSTNK->merk_tipe_kendaraan)) }}</td>
                            <td>{{ ucfirst(strtolower($dataSTNK->pengguna)) }}</td>
                            <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @elseif($usulan->jenis_form == '4')
                <table class="table table-data m-0 small text-capitalize">
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
                    <tbody>
                        @foreach($usulan->usulanVoucher as $dataVoucher)
                        @if($dataVoucher->status_pengajuan == 'true')
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                            <td>{{ ucfirst(strtolower($dataVoucher->jenis_aadb)) }}</td>
                            <td class="text-uppercase">{{ $dataVoucher->no_plat_kendaraan }}</td>
                            <td>{{ ucfirst(strtolower($dataVoucher->merk_tipe_kendaraan)) }}</td>
                            <td>{{ ucfirst(strtolower($dataVoucher->kualifikasi)) }}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @endif
                @endif
            </div>
            <div class="col-md-12 mt-5">
                <div class="col-md-12 text-capitalize">
                    <div class="row text-center">
                        <label class="col-sm-6">Yang Mengusulkan, <br> {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</label>
                        @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                        <label class="col-sm-6">Disetujui Oleh, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row text-center">
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_pengusul) !!}</label>
                        @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_kabag) !!}</label>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row text-center">
                        <label class="col-sm-6">{{ $usulan->nama_pegawai }}</label>
                        @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null )
                        <label class="col-sm-6">{{ $pimpinan->nama_pegawai }}</label>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
