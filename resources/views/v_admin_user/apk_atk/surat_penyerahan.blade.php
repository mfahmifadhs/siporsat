<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Penyerahan</title>

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
                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>sekretariat jenderal</b></h5>
                    <p style="font-size: 18px;"><i>
                            Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Jakarta 12950 <br>
                            Telepon : (021) 5201590</i>
                    </p>
                </h2>
            </div>
            <div class="col-md-2">
                <h2 class="page-header">
                    <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
                </h2>
            </div>
            <div class="col-md-12" style="margin-top: -15px;">
                <hr style="border-width: medium;border-color: black;">
                <hr style="border-width: 1px;border-color: black;margin-top: -11px;">
            </div>
        </div>
        <div class="row" style="font-size: 22px;">
            <div class="col-md-12 form-group">
                <div class="form-group row mb-0">
                    <div class="col-md-2">Nomor</div>
                    <div class="col-md-6 text-uppercase">: {{ $usulan->no_surat_usulan }}</div>
                    <div class="col-md-4 text-right">
                        {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                    </div>
                    <div class="col-md-2 mb-5">Hal</div>
                    <div class="col-md-10 text-capitalize">: {{ $usulan->jenis_form }} ATK</div>
                    <div class="col-md-2">Tanggal</div>
                    <div class="col-md-10">: {{ Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                    <div class="col-md-2">Nama</div>
                    <div class="col-md-10">: {{ $usulan->nama_pegawai }}</div>
                    <div class="col-md-2">Jabatan</div>
                    <div class="col-md-10">: {{ $usulan->keterangan_pegawai }}
                    </div>
                    <div class="col-md-2">Unit Kerja</div>
                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->unit_kerja)) }}</div>
                    <div class="col-md-2">Keterangan</div>
                    <div class="col-md-10 text-capitalize">: {{ ucfirst(strtolower($usulan->rencana_pengguna)) }}</div>
                </div>
                <div class="form-group row mb-0 py-3">
                    <div class="col-12 table-responsive mt-4 mb-5">
                        <span>Berikut adalah daftar ATK yang belum diserahkan :</span>
                        <table class="table table-data mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 0%;" class="text-center">No</th>
                                    <th style="width: 15%;" class="text-center">Jenis Barang</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;" class="text-center">Permintaan</th>
                                    <th style="width: 0%;" class="text-center">Satuan</th>
                                    <th style="width: 20%;" class="text-center">Belum Diserahkan</th>
                                    <th style="width: 0%;" class="text-center">Satuan</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($usulan->permintaanAtk->where('status','diterima') as $i => $dataPermintaan)
                                @php
                                $permintaan = $dataPermintaan->jumlah_disetujui;
                                $belum_diserahkan = $dataPermintaan->jumlah_disetujui - $dataPermintaan->jumlah_penyerahan;
                                @endphp
                                @if ($belum_diserahkan != 0)
                                <tr>
                                    <td class="text-center"> {{ $no++ }}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="modul" value="distribusi">
                                        <input type="hidden" name="id_permintaan[{{$i}}]" value="{{ $dataPermintaan->id_permintaan }}">
                                        {{ $dataPermintaan->jenis_barang }}
                                    </td>
                                    <td>{{ ucfirst(strtolower($dataPermintaan->nama_barang.' '.$dataPermintaan->spesifikasi)) }}</td>
                                    <td class="text-center">{{ $permintaan }}</td>
                                    <td class="text-center">{{ $dataPermintaan->satuan }}</td>
                                    <td class="text-center">{{ $belum_diserahkan }}</td>
                                    <td class="text-center">{{ $dataPermintaan->satuan }}</td>
                                    @endif
                                    @endforeach
                            </tbody>
                        </table>
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
