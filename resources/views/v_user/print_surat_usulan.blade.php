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
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/fonts/VAGRounded.ttf') }}">
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
            border-bottom: 1px solid;
            font-size: 21px;
            font-family: 'Segoeui';
        }

        .divThead {
            font-weight: bold;
            border-right: 1px solid;
            margin: 0px;
        }

        .divTbody {
            border-top: 1px solid;
            /* border-right: 1px solid; */
            margin: 0px;
        }

        .divTheadtd {
            border-right: 1px solid;
        }

        .divTbodytd {
            /* border-right: 1px solid; */
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

<body>
    <section class="header">
        <img src="{{ asset('dist_admin/img/header-surat.png') }}" style="width: 100%;">
    </section>

    <section class="nomor my-3" style="font-family: 'Segoeui';">
        <h4 class="text-center font-weight-bold mb-4">
            SURAT PENGAJUAN
        </h4>
        <table class="h5 mx-5 col-md-12 col-12">
            <tr>
                <td style="width: 10%;">Nomor</td>
                <td>:</td>
                <td style="width: 60%;">{{ $usulan->no_surat_usulan }}</td>
                <td>{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="my-4">Hal</td>
                <td>:</td>
                <td class="text-capitalize">
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
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td>Pengusul</td>
                <td>:</td>
                <td>{{ $usulan->nama_pegawai }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $usulan->keterangan_pegawai }}</td>
                <td></td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ ucfirst(strtolower($usulan->unit_kerja)) }}</td>
                <td></td>
            </tr>
        </table>

        <table class="h5 mx-5 col-md-12 col-12">

        </table>
    </section>

    <section class="body my-3" style="font-family: 'Segoeui';">
        <div class="row h5 mx-5">
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
                        <div class="col-1 divTheadtd text-center p-2">{{ $i + 1 }}</div>
                        <div class="col-3 divTheadtd p-2">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</div>
                        <div class="col-5 divTheadtd p-2">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                        <div class="col-3 p-2">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
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
            <div class="col-12 mt-5" style="font-family: 'Segoeui';">
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
    </section>

    <!-- <section class="footer" style="font-family: 'Segoeui'; margin: 4vh; font-size: 18px;">
        <div class="border border-dark p-2">
            Kementerian Kesehatan tidak menerima suap dan/atau gratifikasi dalam bentuk apapun. Jika terdapat potensi suap
            atau gratifikasi silakan laporkan melalui HALO KEMENKES 1500567 dan <span style="color: blue;">https://wbs.kemkes.go.id</span>. Untuk verifikasi
            keaslian tanda tangan elektronik, silakan unggah dokumen pada laman <span style="color: blue;">https://tte.kominfo.go.id/verifyPDF</span>.
        </div>
    </section> -->
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
