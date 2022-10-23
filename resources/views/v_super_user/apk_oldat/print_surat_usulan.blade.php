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
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>surat pengajuan usulan</b></h5>
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
                    <div class="col-md-12 text-uppercase text-center">{{ $usulan->no_surat_usulan }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Pengusul</div>
                    <div class="col-md-9">: {{ $usulan->nama_pegawai }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Jabatan</div>
                    <div class="col-md-9">: {{ $usulan->jabatan.' '.$usulan->keterangan_pegawai }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Unit Kerja</div>
                    <div class="col-md-9">: {{ $usulan->unit_kerja }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Tanggal Usulan</div>
                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Total Pengajuan</div>
                    <div class="col-md-9">: {{ $usulan->total_pengajuan }} barang</div>
                </div>
                @if($usulan->rencana_pengguna != null)
                <div class="form-group row mb-0">
                    <div class="col-md-3">Rencana Pengguna</div>
                    <div class="col-md-9">: {{ $usulan->rencana_pengguna }}</div>
                </div>
                @endif
            </div>
            <div class="col-12 table-responsive">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Barang</th>
                            <th>Merk Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            @if($usulan->jenis_form == 'pengadaan')
                            <th>Estimasi Biaya</th>
                            @else
                            <th>Nilai Perolehan</th>
                            @endif
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @if($usulan->jenis_form == 'pengadaan')
                        @foreach($usulan->detailPengadaan as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kategori_barang }}</td>
                            <td>{{ $dataBarang->merk_barang }}</td>
                            <td>{{ $dataBarang->jumlah_barang }}</td>
                            <td>{{ $dataBarang->satuan_barang }}</td>
                            <td>Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        @else
                        @foreach($usulan->detailPerbaikan as $dataBarang)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataBarang->kategori_barang }}</td>
                            <td>{{ $dataBarang->merk_tipe_barang }}</td>
                            <td>{{ $dataBarang->nama_pegawai }}</td>
                            <td>{{ $dataBarang->unit_kerja }}</td>
                            <td>{{ $dataBarang->tahun_perolehan }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 form-group" style="margin-top: 10vh;">
                <div class="text-center text-capitalize">
                    <label>Yang Mengusulkan, <br> {{ $usulan->jabatan.' '.$usulan->tim_kerja }}</label>
                    <p style="margin-top:2vh;">
                        {!! QrCode::size(100)->generate('https://www.siporsat-kemenkes.com/bast/'.$usulan->kode_otp_bast) !!}
                    </p>
                    <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
                </div>
            </div>
            <div class="col-md-6 form-group" style="margin-top: 10vh;">
                <div class="text-center text-capitalize">
                    <label>Disetujui Oleh, <br> {{ $pimpinan->jabatan.' '.$pimpinan->keterangan_pegawai }}</label>
                    <p style="margin-top:4vh;">
                        {!! QrCode::size(100)->generate('https://www.siporsat-kemenkes.com/bast/'.$usulan->kode_otp_bast) !!}
                    </p>
                    <label class="text-underline">{{ $pimpinan->nama_pegawai }}</label>
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
