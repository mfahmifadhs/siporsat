<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $bast->id_form_usulan }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.min.css') }}">
</head>
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
        font-size: 21px;
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
        border-right: 1px solid;
        padding: 10px;
    }
</style>

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
        <div class="row text-capitalize" style="font-size: 22px;">
            <div class="col-md-12 form-group">
                <div class="form-group row mb-3 text-center">
                    <div class="col-md-12 text-uppercase">
                        berita acara serah terima <br>
                        nomor surat : {{ $bast->no_surat_bast }}
                    </div>
                </div>
                <div class="form-group row mb-0">
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
                <!-- <div class="form-group row mb-0">
                    <div class="col-md-12 text-justify mt-4">
                        Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                        dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                        berfungsi normal sebagaimana mestinya.
                    </div>
                </div> -->
            </div>
            <div class="col-12 table-responsive mt-4 mb-5">
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
                        @foreach($bast->permintaanAtk as $dataAtk)
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
                    </tbody>
                </table>
                @elseif ($modul == 'gdn')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-md-1 divTheadtd text-center">No</div>
                        <div class="col-md-3 divTheadtd">Bidang Kerusakan</div>
                        <div class="col-md-2 divTheadtd">Lokasi Perbaikan</div>
                        <div class="col-md-4 divTheadtd">Lokasi Spesifik</div>
                        <div class="col-md-2">Keterangan</div>
                    </div>
                    @foreach($bast->detailUsulanGdn as $i => $dataGdn)
                    <div class="row divTbody">
                        <div class="col-md-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</div>
                        <div class="col-md-2 divTbodytd">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</div>
                        <div class="col-md-4 divTbodytd">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</div>
                        <div class="col-md-2 divTbodytd">{!! nl2br(e($dataGdn->keterangan )) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'ukt')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-md-1 divTheadtd text-center">No</div>
                        <div class="col-md-3 divTheadtd">Pekerjaan</div>
                        <div class="col-md-5 divTheadtd">Spesifikasi Pekerjaan</div>
                        <div class="col-md-3">Keterangan</div>
                    </div>
                    @foreach($bast->detailUsulanUkt as $i => $dataUkt)
                    <div class="row divTbody">
                        <div class="col-md-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</div>
                        <div class="col-md-5 divTbodytd">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                        <div class="col-md-3 divTbodytd">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
                    </div>
                    @endforeach
                </div>
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
            <div class="col-md-12 mt-5 text-capitalize">
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
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_ppk) !!}</label>
                        @if($bast->status_proses_id >= 4)
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_pengusul) !!}</label>
                        @endif
                        @if($bast->status_proses_id == 5)
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_kabag) !!}</label>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row text-center">
                        <label class="col-sm-4">Marten Avero, Skm</label>
                        @if($bast->status_proses_id >= 4)
                        <label class="col-sm-4">{{ ucfirst(strtolower($bast->nama_pegawai)) }}</label>
                        @endif
                        @if($bast->status_proses_id == 5)
                        <label class="col-sm-4">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
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
