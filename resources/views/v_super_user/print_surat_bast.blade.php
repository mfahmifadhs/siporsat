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
        font-size: 18px;
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
@php
if($modul == 'oldat' && $bast->jenis_form == 'pengadaan') {
$namaPpk = 'Anggriany Aprilia Sampe, ST, MAP';
$jabatanPpk = 'Pejabat Pembuatan Komitmen Belanja Modal';
} else {
$namaPpk = 'Marten Avero, Skm';
$jabatanPpk = 'Pejabat Pembuatan Komitmen Belanja Operasional';
}
@endphp

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
                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>{{ $bast->unit_utama }}</b></h5>
                    <p style="font-size: 16px;">
                        <i>
                            @if ($bast->id_unit_utama == '02407')
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
            <div class="col-12 form-group">
                <div class="form-group row mb-3 text-center">
                    <div class="col-12 text-uppercase">
                        berita acara serah terima <br>
                        nomor surat : {{ $modul == 'atk' ? $bast->nomor_bast : $bast->no_surat_bast }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-12">
                        Pada Hari Ini, Tanggal {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }} bertempat Di
                        Kantor Pusat Kementerian Kesehatan Republik Indonesia, kami yang bertanda tangan dibawah Ini:
                    </div>
                </div>
                <div class="form-group row mb-0 py-3">
                    <div class="col-2"><span class="ml-5">Nama</span></div>
                    <div class="col-10">: {{ $namaPpk }}</div>
                    <div class="col-2"><span class="ml-5">Jabatan</span></div>
                    <div class="col-10">: {{ $jabatanPpk }}
                    </div>
                    <div class="col-2"><span class="ml-5">Unit Kerja</span></div>
                    <div class="col-10">: Biro Umum</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-12">
                        Dalam Berita Acara ini bertindak untuk dan atas nama Biro Umum Sekretariat Jenderal, {{ $jabatanPpk }}
                        selaku yang menyerahkan, yang selanjutnya disebut <b>PIHAK PERTAMA</b>.
                    </div>
                </div>
                <div class="form-group row mb-0 py-3">
                    <div class="col-2"><span class="ml-5">Nama</span></div>
                    <div class="col-10">: {{ $bast->nama_pegawai }}</div>
                    <div class="col-2"><span class="ml-5">Jabatan</span></div>
                    <div class="col-10">: {{ $bast->keterangan_pegawai }}
                    </div>
                    <div class="col-2"><span class="ml-5">Unit Kerja</span></div>
                    <div class="col-10">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                </div>
                <div class="form-group row mb-0 text-justify">
                    <div class="col-12">
                        Dalam Berita Acara ini bertindak untuk dan atas nama {{ ucfirst(strtolower($bast->unit_kerja)) }}
                        Selaku Penerima, yang selanjutnya disebut <b>PIHAK KEDUA</b>.
                    </div>
                </div>
                <div class="form-group row mb-0 text-justify">
                    <div class="col-12 mt-4">
                        Bahwa <b>PIHAK PERTAMA</b> telah menyerahkan barang/pekerjaan dari/kepada <b>PIHAK KEDUA</b>
                        dengan rincian sebagai berikut:
                    </div>
                </div>
                <!-- <div class="form-group row mb-0">
                    <div class="col-3">Tanggal</div>
                    <div class="col-9">: {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-3">Pengusul</div>
                    <div class="col-9">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-3">Jabatan</div>
                    <div class="col-9">: {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-3">Unit Kerja</div>
                    <div class="col-9">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-3">Total Pengajuan</div>
                    <div class="col-9">: {{ $bast->total_pengajuan }}</div>
                </div>
                @if($bast->rencana_pengguna != null)
                <div class="form-group row mb-0">
                    <div class="col-3">Rencana Pengguna</div>
                    <div class="col-9">: {{ $bast->rencana_pengguna }}</div>
                </div>
                @endif -->
                <!-- <div class="form-group row mb-0">
                    <div class="col-12 text-justify mt-4">
                        Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                        dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                        berfungsi normal sebagaimana mestinya.
                    </div>
                </div> -->
            </div>
            <div class="col-12 table-responsive mb-5">
                @if ($modul == 'oldat')
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th class="py-2">No</th>
                            <th class="py-2">Kode Barang</th>
                            <th class="py-2">Nama Barang</th>
                            <th class="py-2">Merk/Tipe Barang</th>
                            @if($bast->jenis_form == 'pengadaan')
                            <th class="py-2">Spesifikasi</th>
                            <th class="py-2">Tahun </th>
                            @else
                            <th class="py-2">Tahun Perolehan</th>
                            <th class="py-2">Keterangan</th>
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
                            <td>
                                @if (\Carbon\carbon::parse($dataBarang->tahun_perolehan)->isoFormat('Y') != -1)
                                    {{ \Carbon\carbon::parse($dataBarang->tahun_perolehan)->isoFormat('Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $dataBarang->keterangan_perbaikan }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                @elseif ($modul == 'atk')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-1 divTheadtd text-center p-2">No</div>
                        <div class="col-4 divTheadtd p-2">Nama Barang</div>
                        <div class="col-3 divTheadtd p-2">Deskripsi</div>
                        <div class="col-2 divTheadtd text-center p-2">Permintaan</div>
                        <div class="col-2 divTheadtd text-center p-2">Penyerahan</div>
                    </div>
                    @if ($bast->jenis_form == 'distribusi')
                    @foreach($bast->detailBast as $i => $detailAtk)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-4 divTbodytd">{{ $detailAtk->nama_barang }}</div>
                        <div class="col-3 divTbodytd">{{ $detailAtk->spesifikasi }}</div>
                        <div class="col-2 divTbodytd text-center">{{ (int) $detailAtk->jumlah_disetujui.' '.$detailAtk->satuan }}</div>
                        <div class="col-2 divTbodytd text-center">{{ (int) $detailAtk->jumlah_bast_detail.' '.$detailAtk->satuan }}</div>
                    </div>
                    @endforeach
                    @elseif ($bast->jenis_form == 'permintaan')
                    @foreach($bast->detailBast2 as $i => $detailAtk)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-4 divTbodytd">{{ $detailAtk->deskripsi_barang }}</div>
                        <div class="col-3 divTbodytd">{{ $detailAtk->catatan }}</div>
                        <div class="col-2 divTbodytd text-center">{{ (int) $detailAtk->jumlah_disetujui.' '.$detailAtk->satuan_barang }}</div>
                        <div class="col-2 divTbodytd text-center">{{ (int) $detailAtk->jumlah_bast_detail.' '.$detailAtk->satuan_barang }}</div>
                    </div>
                    @endforeach
                    @endif
                </div>
                @if ( $bast->detailAtk->count() > 6 ) <div class="pagebreak"></div> @endif
                @elseif ($modul == 'gdn')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-1 divTheadtd text-center py-2">No</div>
                        <div class="col-3 divTheadtd py-2">Bidang Kerusakan</div>
                        <div class="col-2 divTheadtd py-2">Lokasi Perbaikan</div>
                        <div class="col-4 divTheadtd py-2">Lokasi Spesifik</div>
                        <div class="col-2 py-2">Keterangan</div>
                    </div>
                    @foreach($bast->detailUsulanGdn as $i => $dataGdn)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</div>
                        <div class="col-2 divTbodytd">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</div>
                        <div class="col-4 divTbodytd">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</div>
                        <div class="col-2 divTbodytd">{!! nl2br(e($dataGdn->keterangan )) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'ukt')
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-1 divTheadtd text-center py-2">No</div>
                        <div class="col-3 divTheadtd py-2">Pekerjaan</div>
                        <div class="col-5 divTheadtd py-2">Spesifikasi Pekerjaan</div>
                        <div class="col-3 py-2">Keterangan</div>
                    </div>
                    @foreach($bast->detailUsulanUkt as $i => $dataUkt)
                    <div class="row divTbody">
                        <div class="col-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-3 divTbodytd">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</div>
                        <div class="col-5 divTbodytd">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                        <div class="col-3 divTbodytd">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
                    </div>
                    @endforeach
                </div>
                @elseif ($modul == 'aadb')
                @if($bast->jenis_form == '1')
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis AADB</th>
                            <th>Jenis Kendaraan</th>
                            <th>Merk / Tipe</th>
                            <th>Tahun Kendaraan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody class="text-capitalize">
                        @foreach($bast->usulanKendaraan as $dataPengadaan)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataPengadaan->jenis_aadb }}</td>
                            <td>{{ $dataPengadaan->jenis_kendaraan }}</td>
                            <td>{{ $dataPengadaan->merk_tipe_kendaraan }}</td>
                            <td>{{ $dataPengadaan->tahun_kendaraan }}</td>
                            <td>{{ $dataPengadaan->jumlah_pengajuan }} kendaraan</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @elseif($bast->jenis_form == '2')
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th class="py-2">No</th>
                            <th class="py-2">No. Plat</th>
                            <th class="py-2">Kendaraan</th>
                            <th class="py-2">Kilometer</th>
                            <th class="py-2">Jadwal Servis</th>
                            <th class="py-2">Jadwal Ganti Oli</th>
                            <th class="py-2">Keterangan</th>
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
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th class="py-2">No</th>
                            <th class="py-2">No. Plat</th>
                            <th class="py-2">Kendaraan</th>
                            <th class="py-2">Pengguna</th>
                            <th class="py-2">Masa Berlaku STNK</th>
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
                <table class="table table-data m-0">
                    <thead>
                        <tr>
                            <th class="py-2">No</th>
                            <th class="py-2">Bulan Pengadaan</th>
                            <th class="py-2">Jenis AADB</th>
                            <th class="py-2">No. Plat</th>
                            <th class="py-2">Kendaraan</th>
                            <th class="py-2">Kualifikasi</th>
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
                @if ( $bast->usulanVoucher->count() > 6 ) <div class="pagebreak"></div> @endif
                @endif
                @endif
            </div>
            <div class="col-12 mt-5">
                <div class="col-12 text-capitalize">
                    <div class="row text-center">
                        <label class="col-sm-4">Yang Menyerahkan, <br> {{ $jabatanPpk }} </label>
                        <label class="col-sm-4">Yang Menerima, <br> {{ $bast->keterangan_pegawai }}</label>
                        <label class="col-sm-4">Mengetahui, <br> {{ $pimpinan->keterangan_pegawai }}</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row text-center mt-4 ml">
                        @if ($bast->otp_bast_ppk != null)
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_ppk) !!}</label>
                        @else
                        <label style="padding:40px 0;"></label>
                        @endif
                        @if ($bast->otp_bast_pengusul != null)
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_pengusul) !!}</label>
                        @else
                        <label style="padding:40px 0;"></label>
                        @endif
                        @if ($bast->otp_bast_kabag != null)
                        <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-ukt/'.$bast->otp_bast_kabag) !!}</label>
                        @else
                        <label style="padding:40px 0;"></label>
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="row text-center">
                        <label class="col-sm-4">{{ $namaPpk }}</label>
                        <label class="col-sm-4">{{ $bast->nama_pegawai }}</label>
                        <label class="col-sm-4">{{ $pimpinan->nama_pegawai }}</label>
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
