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
        }

        .divTable {
            border-top: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            font-size: 16px;
        }

        .divThead {
            border-bottom: 1px solid;
            font-weight: bold;
        }

        .divTbody {
            border-bottom: 1px solid;
            text-transform: capitalize;
        }

        .divTheadtd {
            border-right: 1px solid;
        }

        .divTbodytd {
            padding: 10px;
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
    </style>
</head>

<body style="font-family: Arial;">
    <div class="">
        <div class="row">
            <div class="col-2">
                <h2 class="page-header ml-4">
                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                </h2>
            </div>
            <div class="col-8 text-center">
                <h2 class="page-header">
                    <h5 style="font-size: 26px;text-transform:uppercase;"><b>KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</b></h5>
                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>{{ $usulan->unit_utama }}</b></h5>
                    <p style="font-size: 16px;">
                        <i>
                            @if ($usulan->id_unit_utama == '02407')
                            Jalan Hang Jebat III Blok F3 Kebayoran Baru Jakarta Selatan 12120<br>
                            Telepon : (021) 724 5517 - 7279 7308 Faksimile : (021) 7279 7508<br>
                            Laman www.bppsdmk.depkes.go.id
                            @else
                            Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Jakarta 12950 <br>
                            Telepon : (021) 5201590
                            @endif
                        </i>
                    </p>
                </h2>
            </div>
            <div class="col-2">
                <h2 class="page-header">
                    <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
                </h2>
            </div>
            <div class="col-12" style="margin-top: -15px;">
                <hr style="border-width: medium;border-color: black;">
                <hr style="border-width: 1px;border-color: black;margin-top: -11px;">
            </div>
        </div>
        <div class="row" style="font-size: 20px;">
            <div class="col-8">
                <div class="form-group row">
                    <div class="col-3">Nomor</div>
                    <div class="col-9 text-uppercase">: {{ $usulan->no_surat_usulan }}</div>
                    <div class="col-3">Hal</div>
                    <div class="col-9 text-capitalize">:
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
                </div>
            </div>
            <div class="col-4 text-right">
                <div class="col-12">{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
            </div>
            <div class="col-12">
                <div class="form-group row">
                    <div class="col-2 mt-4">Pengusul</div>
                    <div class="col-10 text-capitalize mt-4">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }} </div>
                    <div class="col-2">Jabatan</div>
                    <div class="col-10">: {{ $usulan->keterangan_pegawai }} </div>
                    <div class="col-2">Unit Kerja</div>
                    <div class="col-10 text-capitalize">: {{ ucfirst(strtolower($usulan->unit_kerja)) }} </div>
                    <div class="col-2">Jumlah</div>
                    <div class="col-10 text-capitalize">:
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
                    <div class="col-2">Keterangan</div>
                    <div class="col-10 text-capitalize">:
                        {{ $usulan->rencana_pengguna }}
                    </div>
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
                        @elseif ($form->jenis_form == 'distribusi')
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
                        @else
                        @foreach($usulan->permintaan2Atk as $dataAtk)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataAtk->deskripsi_barang }}</td>
                            <td>{{ $dataAtk->catatan }}</td>
                            <td>{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan_barang }}</td>
                            <td>{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan_barang }}</td>
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
                        <div class="col-1 divTheadtd text-center p-2">No</div>
                        <div class="col-3 divTheadtd p-2">Bidang Kerusakan</div>
                        <div class="col-3 divTheadtd p-2">Lokasi Perbaikan</div>
                        <div class="col-3 divTheadtd p-2">Lokasi Spesifik</div>
                        <div class="col-2 p-2">Keterangan</div>
                    </div>
                    @foreach($usulan->detailUsulanGdn as $i => $dataGdn)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</div>
                        <div class="col-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</div>
                        <div class="col-3 divTbodytd">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</div>
                        <div class="col-2 divTbodytd">{!! nl2br(e($dataGdn->keterangan )) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'usulan-ukt')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-1 divTheadtd text-center p-2">No</div>
                        <div class="col-3 divTheadtd p-2">Pekerjaan</div>
                        <div class="col-5 divTheadtd p-2">Spesifikasi Pekerjaan</div>
                        <div class="col-3 p-2">Keterangan</div>
                    </div>
                    @foreach($usulan->detailUsulanUkt as $i => $dataUkt)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-3 divTbodytd">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</div>
                        <div class="col-5 divTbodytd">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                        <div class="col-3 divTbodytd">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
                    </div>
                    @endforeach
                @endif
            </table>
            @elseif ($modul == 'usulan-gdn')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row" style="width: 0%;">No</td>
                    <td class="col-row" style="width: 25%;">Bidang Kerusakan</td>
                    <td class="col-row" style="width: 25%;">Lokasi Perbaikan</td>
                    <td class="col-row" style="width: 25%;">Lokasi Spesifik</td>
                    <td class="" style="width: 25%;">Keterangan</td>
                </tr>
                @foreach($usulan->detailUsulanGdn as $i => $dataGdn)
                <tr>
                    <td class="col-row">{{ $i + 1 }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</td>
                    <td class="col-row text-left">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</td>
                    <td class="text-left">{!! nl2br(e($dataGdn->keterangan )) !!}</td>
                </tr>
                @endforeach
            </table>
            @elseif ($modul == 'usulan-ukt')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row" style="width: 0%;">No</td>
                    <td class="col-row" style="width: 30%;">Pekerjaan</td>
                    <td class="col-row" style="width: 50%;">Spesifikasi Pekerjaan</td>
                    <td class="" style="width: 20%;">Keterangan</td>
                </tr>
                @foreach($usulan->detailUsulanUkt as $i => $dataUkt)
                <tr>
                    <td class="col-row">{{ $i + 1 }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</td>
                    <td class="col-row text-left">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</td>
                    <td class="text-left">{!! nl2br(e($dataUkt->keterangan)) !!}</td>
                </tr>
                @endforeach
            </table>
            @elseif ($modul == 'usulan-aadb')
            @if($usulan->jenis_form == '1')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Jenis AADB</td>
                    <td class="col-row">Jenis Kendaraan</td>
                    <td class="col-row">Merk/Tipe</td>
                    <td class="col-row">Kualifikasi</td>
                    <td class="col-row">Jumlah</td>
                    <td class="">Tahun</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanKendaraan as $dataKendaraan)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->jenis_aadb)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->jenis_kendaraan)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->merk_tipe_kendaraan)) }}</td>
                    <td class="col-row">Kendaraan {{ ucfirst(strtolower($dataKendaraan->kualifikasi)) }}</td>
                    <td class="col-row">{{ $dataKendaraan->jumlah_pengajuan }} UNIT</td>
                    <td class="">{{ $dataKendaraan->tahun_kendaraan }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '2')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="col-row">Jadwal Servis</td>
                    <td class="col-row">Jadwal Ganti Oli</td>
                    <td class="">Keterangan</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanServis as $dataServis)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row text-left">
                        {{ strtoupper($dataServis->no_plat_kendaraan) }} <br>
                        {{ ucfirst(strtolower($dataServis->merk_tipe_kendaraan)) }} <br>
                        Kilometer Terakhir : {{ (int) $dataServis->kilometer_terakhir }} Km
                    </td>
                    <td class="col-row text-left">
                        Terakhir Servis : <br>
                        {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }} <br>
                        Jatuh Tempo Servis : <br>
                        {{ (int) $dataServis->jatuh_tempo_servis }} Km
                    </td>
                    <td class="col-row text-left">
                        Terakhir Ganti Oli : <br>
                        {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }} <br>
                        Jatuh Tempo Servis : <br>
                        {{ (int) $dataServis->jatuh_tempo_ganti_oli }} Km
                    </td>
                    <td>{{ $dataServis->keterangan_servis }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '3')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">No. Plat</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="col-row">Pengguna</td>
                    <td class="">Masa Berlaku STNK</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanSTNK as $dataSTNK)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ strtoupper($dataSTNK->no_plat_kendaraan) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataSTNK->merk_tipe_kendaraan)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataSTNK->pengguna)) }}</td>
                    <td class="">{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '4')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Bulan Pengadaan</td>
                    <td class="col-row">Jenis AADB</td>
                    <td class="col-row">No. Plat</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="">Kualifikasi</td>
                <?php $no = 1; ?>
                @foreach($usulan->usulanVoucher as $dataVoucher)
                @if($dataVoucher->status_pengajuan == 'true')
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataVoucher->jenis_aadb)) }}</td>
                    <td class="col-row">{{ strtoupper($dataVoucher->no_plat_kendaraan) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataVoucher->merk_tipe_kendaraan)) }}</td>
                    <td class="">{{ ucfirst(strtolower($dataVoucher->kualifikasi)) }}</td>
                 </tr>
                @endif
            </div>
            <div class="col-12 mt-5">
                <div class="col-12 text-capitalize">
                    <div class="row text-center">
                        <label class="col-sm-6">Yang Mengusulkan, <br> {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</label>
                        @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                        <label class="col-sm-6">Disetujui Oleh, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                        @endif
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="row text-center">
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_pengusul) !!}</label>
                        @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_kabag) !!}</label>
                        @endif
                    </div>
                </div>
                <div class="col-12 mt-4">
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
        function toDataURL(src, callback, outputFormat) {
            var img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                canvas.height = this.height;
                canvas.width = this.width;
                ctx.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL(outputFormat || 'image/png');
                callback(dataURL);
                canvas = null;
            };
            img.src = src;
        }

        toDataURL('https://i.ibb.co/Ws42B2m/logo-kemenkes-icon.png', function(dataUrl) {
            document.getElementById('logo1').src = dataUrl;
        });

        toDataURL('https://i.ibb.co/gyB7drd/logo-germas.png', function(dataUrl) {
            document.getElementById('logo2').src = dataUrl;
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            const element = document.body;
            html2pdf(element, {
                filename: 'cetak.pdf',
                html2canvas: {
                    scale: 2
                },
                margin: 6,
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                output: 'blob'

            }).then(function(pdfBlob) {
                const pdfUrl = URL.createObjectURL(pdfBlob);
                const downloadLink = document.createElement('a');
                downloadLink.href = pdfUrl;
                downloadLink.download = 'cetak.pdf';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            });
        });
    </script>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.close();
            }, 300);
        };
    </script>


</body>

</html>
