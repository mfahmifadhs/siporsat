<!DOCTYPE html>
<html lang="en">

@foreach($suratPengajuan as $dataSurat)

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $dataSurat->id_form_usulan }}</title>

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
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>surat pengajuan {{ $dataSurat->jenis_form }}</b></h5>
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
                <p class="m-0">Kode Form <span style="margin-left: 0.5%;">: {{ $dataSurat->kode_form }}</span></p>
                <p class="m-0">Perihal <span style="margin-left: 4%;">: {{ $dataSurat->jenis_form }} Barang</span></p>
            </div>
            <div class="col-md-12 form-group">
                <p class="text-justify">

                </p>
                <p class="m-0 text-capitalize">
                    Pengusul <span style="margin-left: 10%;"> : {{ $dataSurat->nama_pegawai }} </span> <br>
                    Jabatan <span style="margin-left: 11%;"> : {{ $dataSurat->jabatan.' '.$dataSurat->tim_kerja }}</span> <br>
                    Unit Kerja <span style="margin-left: 9.5%;"> : {{ $dataSurat->unit_kerja }}</span> <br>
                    Tanggal Usulan <span style="margin-left: 4.8%;"> : {{ \Carbon\Carbon::parse($dataSurat->tanggal_usulan)->isoFormat('DD MMMM Y') }}</span> <br>
                    Rencana Pengguna <span style="margin-left: 1.5%;"> : {{ $dataSurat->rencana_pengguna }}</span> <br>
                </p>
                <p class="text-capitalize">
                </p>
                <p class="text-capitalize">
                </p>
                <p class="text-capitalize">

                </p>
            </div>
            <div class="col-12 table-responsive">
                <table class="table table-bordered m-0">
                    @if($dataSurat->jenis_form == 'pengadaan')
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Spesifikasi Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($dataSurat->detailPengadaan as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kategori->kategori_barang }}</td>
                            <td>{{ $dataBarang->merk_barang }}</td>
                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                            <td>{{ $dataBarang->jumlah_barang }}</td>
                            <td>{{ $dataBarang->satuan_barang }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Spesifikasi</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($dataSurat->detailPerbaikan as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kode_barang }}</td>
                            <td>{{ $dataBarang->nup_barang }}</td>
                            <td>{{ $dataBarang->spesifikasi_barang }}</td>
                            <td>{{ $dataBarang->jumlah_barang }}</td>
                            <td>{{ $dataBarang->satuan_barang }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 form-group mt-4">
                <div class="text-center">
                    <label class="font-weight-bold text-center">PENGUSUL</label>
                    <p style="margin-top: 5% ;">
                        {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://www.siporsat-kemenkes.app/'.$dataSurat->kode_otp_usulan) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">{{ $dataSurat->nama_pegawai }}</label>
                    </div>
                </div>

            </div>
            <div class="col-md-6 form-group mt-4">
                <div class="text-center">
                    <label class="font-weight-bold text-center">KEPALA BAGIAN RT</label>
                    <p style="margin-top: 5% ;">
                        {!! QrCode::size(100)->merge(public_path('logo-kemenkes-icon.PNG'), 1, true)->generate('https://www.siporsat-kemenkes.com/'.$dataSurat->kode_otp_usulan) !!}
                    </p>
                    <div style="margin-top: 5%;">
                        <label class="text-underline">Muhamad Edwin Arafat, S.kom</label>
                    </div>
                    </p>
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
