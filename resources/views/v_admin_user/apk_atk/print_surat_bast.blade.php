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
        <div class="row text-capitalize">
            <div class="col-md-12 form-group">
                <div class="form-group row mb-3 text-center">
                    <div class="col-md-12 text-uppercase">
                        berita acara serah terima <br>
                        nomor surat : {{ $bast->no_surat_bast }}
                    </div>
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
                    <div class="col-md-2">Tanggal Usulan</div>
                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($bast->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                </div>
                @if($bast->rencana_pengguna != null)
                <div class="form-group row mb-0">
                    <div class="col-md-2">Rencana Pengguna</div>
                    <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                </div>
                @endif
            </div>
            <div class="col-12 table-responsiv" style="margin-bottom: 10vh;">
                <table class="table table-bordered m-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Merk/Tipe</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            @if ($bast->jenis_form == 'pengadaan')
                            <th>Harga</th>
                            @endif
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($bast->detailUsulanAtk as $dataAtk)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataAtk->kategori_atk }}</td>
                            <td>{{ $dataAtk->merk_atk }}</td>
                            <td>{{ $dataAtk->jumlah_pengajuan }}</td>
                            <td>{{ $dataAtk->satuan }}</td>
                            @if ($bast->jenis_form == 'pengadaan')
                            <td>Rp {{ number_format($dataAtk->harga, 0, ',', '.') }}</td>
                            @endif
                            <td>{{ $dataAtk->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div class="row text-center">
                    <label class="col-sm-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen</label>
                    <label class="col-sm-4">Yang Menerima, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                    <label class="col-sm-4">Mengetahui, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row text-center">
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.app/bast/'.$bast->otp_bast_pengusul) !!}</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row text-center">
                    <label class="col-sm-4">Marten Avero, Skm</label>
                    <label class="col-sm-4">{{ ucfirst(strtolower($bast->nama_pegawai)) }}</label>
                    <label class="col-sm-4">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
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
