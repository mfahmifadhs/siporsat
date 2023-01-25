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

<style>
    @media print {
        .pagebreak {
            page-break-before: always;
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
    <div class="text-capitalize">
        <div class="row">
            <div class="col-md-2">
                <h2 class="page-header ml-4">
                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                </h2>
            </div>
            <div class="col-md-8 text-center">
                <h2 class="page-header">
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $usulan->unit_utama }}</b></h5>
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
            <div class="col-md-12 form-group">
                <div class="form-group row mb-3 text-center">
                    <div class="col-md-12 text-uppercase">
                        usulan pengajuan <br>
                        nomor surat : {{ $usulan->no_surat_usulan }}
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Tanggal Usulan</div>
                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Pengusul</div>
                    <div class="col-md-9">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Jabatan</div>
                    <div class="col-md-9">: {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Unit Kerja</div>
                    <div class="col-md-9">: {{ ucfirst(strtolower($usulan->unit_kerja)) }}</div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-3">Total Pengajuan</div>
                    <div class="col-md-9">: {{ $usulan->total_pengajuan }}</div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="divTable">
                    <div class="row divThead">
                        <div class="col-md-1 divTheadtd text-center">No</div>
                        <div class="col-md-3 divTheadtd">Bidang Kerusakan</div>
                        <div class="col-md-2 divTheadtd">Lokasi Perbaikan</div>
                        <div class="col-md-4 divTheadtd">Lokasi Spesifik</div>
                        <div class="col-md-2">Keterangan</div>
                    </div>
                    @foreach($usulan->detailUsulanGdn as $i => $dataGdn)
                    <div class="row divTbody">
                        <div class="col-md-1 divTbodytd text-center">{{ $i + 1 }}</div>
                        <div class="col-md-3 divTbodytd">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</div>
                        <div class="col-md-2 divTbodytd">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</div>
                        <div class="col-md-4 divTbodytd">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</div>
                        <div class="col-md-2 divTbodytd">{!! nl2br(e($dataGdn->keterangan )) !!}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <div class="col-md-12">
                    <div class="row text-center">
                        <label class="col-sm-6">Yang Mengusulkan, <br> {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</label>
                        @if ($usulan->otp_usulan_kabag != null)
                        <label class="col-sm-6">Disetujui Oleh, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row text-center">
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan-gdn/'.$usulan->otp_usulan_pengusul) !!}</label>
                        @if ($usulan->otp_usulan_kabag != null)
                        <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/usulan-gdn/'.$usulan->otp_usulan_kabag) !!}</label>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row text-center">
                        <label class="col-sm-6">{{ ucfirst(strtolower($usulan->nama_pegawai)) }}</label>
                        @if ($usulan->otp_usulan_kabag != null )
                        <label class="col-sm-6">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
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
