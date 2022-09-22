<!DOCTYPE html>
<html lang="en">

@foreach($bast as $dataBast)

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $dataBast->kode_otp_bast }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.min.css') }}">
</head>

<body>
    <div class="wrapper">
        <div class="row">
            <div class="col-md-2">
                <h2 class="page-header ml-4">
                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                </h2>
            </div>
            <div class="col-md-8 text-center">
                <h2 class="page-header">
                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>berita acara serah terima</b></h5>
                    <h5 style="font-size: 24px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
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
        <div class="row" style="font-size: 22px;">
            <div class="col-md-12 form-group">
                <p class="m-0 text-capitalize">
                    Pengusul <span style="margin-left: 9.9%;"> : {{ $dataBast->nama_pegawai }} </span> <br>
                    Jabatan <span style="margin-left: 10.8%;"> : {{ $dataBast->jabatan.' '.$dataBast->tim_kerja }}</span> <br>
                    Unit Kerja <span style="margin-left: 9.3%;"> : {{ $dataBast->unit_kerja }}</span> <br>
                    Tanggal Usulan <span style="margin-left: 4.7%;"> : {{ \Carbon\Carbon::parse($dataBast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</span> <br>
                    Rencana Pengguna <span style="margin-left: 1.4%;"> : {{ $dataBast->rencana_pengguna }}</span> <br>
                </p>
                <p class="text-justify mt-4">
                    Saya yang bertandatangan dibawah ini, telah menerima Barang Milik Negara (BMN).
                    dengan rincian sebagaimana tertera pada tabel dibawah ini, dalam keadaan baik dan
                    berfungsi normal sebagaimana mestinya.
                </p>
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
                        @if($dataBast->jenis_form == 'pengadaan')
                        @foreach($dataBast->detailPengadaan as $dataBarang)
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
                        @foreach($dataBast->detailPerbaikan as $dataBarang)
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
                    <label>yang menyerahkan, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                    <p style="margin-top: 13%;margin-left:17%;">
                        {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://www.siporsat-kemenkes.com/bast/'.$dataBast->kode_otp_bast) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                    </div>
                    </p>
                </div>
            </div>
            <div class="col-md-6 form-group" style="margin-top: 10%;">
                <div style="margin-right:20%;margin-left:10%;text-transform:capitalize;">
                    <label>yang menerima, <br> pengusul</label>
                    <p style="margin-top: 13%;margin-left:17%;">
                        {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://milkshake.app/'.$dataBast->kode_otp_bast) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">{{ $dataBast->nama_pegawai }}</label>
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
@endforeach
