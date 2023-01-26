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
        <div class="row" style="font-size: 22px;">
            <div class="col-md-12 form-group text-capitalize">
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
                    <div class="col-md-9">: {{ $bast->total_pengajuan }} kendaraan</div>
                </div>
                @if($bast->rencana_pengguna != null)
                <div class="form-group row mb-0">
                    <div class="col-md-2">Rencana Pengguna</div>
                    <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                </div>
                @endif
            </div>
            <div class="col-12 table-responsive mt-4 mb-5">
                @if($bast->jenis_form == '1')
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
                        @foreach($bast->usulanKendaraan as $dataKendaraan)
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
                <table class="table table-bordered m-0 text-capitalize">
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
            </div>
            <div class="col-md-12 text-capitalize">
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
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-gdn/'.$bast->otp_bast_ppk) !!}</label>
                    @if($bast->status_proses_id >= 4)
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-gdn/'.$bast->otp_bast_pengusul) !!}</label>
                    @endif
                    @if($bast->status_proses_id == 5)
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-gdn/'.$bast->otp_bast_kabag) !!}</label>
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
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
