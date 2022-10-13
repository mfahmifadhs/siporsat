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
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>berita acarah serah terima</b></h5>
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
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
                <div class="form-group row mb-4">
                    <div class="col-md-12">pengajuan usulan {{ $bast->jenis_form }} barang</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Pengusul</div>
                    <div class="col-md-9">: {{ $bast->nama_pegawai }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Jabatan</div>
                    <div class="col-md-9">: {{ $bast->jabatan.' '.$bast->keterangan_pegawai }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Unit Kerja</div>
                    <div class="col-md-9">: {{ $bast->unit_kerja }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Tanggal Usulan</div>
                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($bast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                </div>
                @if($bast->rencana_pengguna != null)
                <div class="form-group row mb-0">
                    <div class="col-md-3">Rencana Pengguna</div>
                    <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                </div>
                @endif
            </div>
            <div class="col-12 table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Jenis Barang</th>
                            <th>Spesifikasi</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @if($bast->jenis_form == 'pengadaan')
                        @foreach($bast->barang as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kode_barang }}</td>
                            <td>{{ $dataBarang->nup_barang }}</td>
                            <td>{{ $dataBarang->kategori_barang }}</td>
                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                            <td>{{ $dataBarang->jumlah_barang }}</td>
                            <td>{{ $dataBarang->satuan_barang }}</td>
                        </tr>
                        @endforeach
                        @else
                        @foreach($bast->detailPerbaikan as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kode_barang }}</td>
                            <td>{{ $dataBarang->nup_barang }}</td>
                            <td>{{ $dataBarang->kategori_barang }}</td>
                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                            <td>{{ $dataBarang->jumlah_barang }}</td>
                            <td>{{ $dataBarang->satuan_barang }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 form-group" style="margin-top: 10%;">
                <div style="margin-left:30%;text-transform:capitalize;">
                    <label>Yang Menyerahkan, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                    <p style="margin-top: 13%;margin-left:17%;">
                        {!! QrCode::size(100)->generate('https://www.siporsat-kemenkes.com/bast/'.$bast->kode_otp_bast) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                    </div>
                    </p>
                </div>
            </div>
            <div class="col-md-6 form-group" style="margin-top: 10%;">
                <div style="margin-right:20%;margin-left:15%;text-transform:capitalize;">
                    <label>yang menerima, <br> pengusul</label>
                    <p style="margin-top: 13%;margin-left:17%;">
                        {!! QrCode::size(100)->generate('https://milkshake.app/'.$bast->kode_otp_bast) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">{{ $bast->nama_pegawai }}</label>
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
