@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Tulis Kantor (ATK)</h1>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/1') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small></h3>
                                    <p>PERSETUJUAN KABAG RT</p>
                                </div>
                                <div class="icon p-2">
                                    <i>1</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/2') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small></h3>
                                    <p>SEDANG DIPERSIAPKAN</p>
                                </div>
                                <div class="icon p-2">
                                    <i>2</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/3') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small></h3>
                                    <p>SUDAH DAPAT DIAMBIL</p>
                                </div>
                                <div class="icon p-2">
                                    <i>3</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/4') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 4)->count() }} <small>usulan</small></h3>
                                    <p>PROSES BERITA ACARA</p>
                                </div>
                                <div class="icon p-2">
                                    <i>4</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/5') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small></h3>
                                    <p>SELESAI BERITA ACARA</p>
                                </div>
                                <div class="icon p-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="{{ url('admin-user/atk/usulan/status/ditolak') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small></h3>
                                    <p>PENGAJUAN DITOLAK</p>
                                </div>
                                <div class="icon p-2">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12 text-center">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-left">Usulan Pengajuan</h6>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-left">Referensi dan Stok Barang</h6>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/usulan/daftar/seluruh-usulan') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-copy fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Usulan</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/surat/bast-atk/daftar') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-file-signature fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Berita Acara</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/gudang/referensi/daftar') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-boxes fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Referensi Barang</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/gudang/stok/*') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-store fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Stok Gudang ATK</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/gudang/dashboard/roum') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-pallet fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Laporan Gudang ATK</h6>
                        </a>
                    </div>
                    @if(Auth::user()->id == 3)
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/gudang/daftar-transaksi/Pembelian') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-arrow-circle-up fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Pembelian ATK</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/gudang/daftar-transaksi/Permintaan') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-arrow-circle-down fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Permintaan ATK</h6>
                        </a>
                    </div>
                    @endif
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/atk/usulan/laporan/*') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-file-alt fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Laporan</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            <i class="fas fa-chart-bar"></i> &nbsp; GRAFIK USULAN ATK

                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <div id="barchart_material"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            <i class="fas fa-table"></i> &nbsp; REKAPITULASI USULAN ATK UNIT KERJA
                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/1') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Persetujuan Kabag RT</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/2') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Sedang Diproses PPK</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/3') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Konfirmasi BAST Pengusul</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/4') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Konfirmasi BAST Kabag RT</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/5') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Selesai BAST</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('admin-user/atk/usulan/status/ditolak') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Ditolak</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="table-unitkerja" class="table table-striped m-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Unit Kerja</th>
                                                    <th class="text-center">Jumlah Usulan Pengadaan</th>
                                                    <th class="text-center">Jumlah Usulan Distribusi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usulanUker as $i => $row)
                                                <tr>
                                                    <td class="text-center">{{ $i+1 }}</td>
                                                    <td>{{ $row->unit_kerja }}</td>
                                                    <td class="text-center">
                                                        @php
                                                        $totalUsulan = $usulanTotal->where('unit_kerja_id', $row->id_unit_kerja)->where('jenis_form','pengadaan')->count()
                                                        @endphp
                                                        @if ($totalUsulan)
                                                        <form action="{{ url('admin-user/atk/usulan/status/uker') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="jenis_form" value="pengadaan">
                                                            <input type="hidden" name="id_unit_kerja" value="{{ $row->id_unit_kerja }}">
                                                            <button type="submit" class="btn btn-dark btn-sm font-weight-bold">
                                                                {{ $totalUsulan }}
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                        $totalUsulan = $usulanTotal->where('unit_kerja_id', $row->id_unit_kerja)->where('jenis_form','distribusi')->count()
                                                        @endphp
                                                        @if ($totalUsulan)
                                                        <form action="{{ url('admin-user/atk/usulan/status/uker') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="jenis_form" value="distribusi">
                                                            <input type="hidden" name="id_unit_kerja" value="{{ $row->id_unit_kerja }}">
                                                            <button type="submit" class="btn btn-dark btn-sm font-weight-bold">
                                                                {{ $totalUsulan }}
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold">
                            <i class="fas fa-table"></i> &nbsp; TABEL DAFTAR ATK
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="card-body border border-default">
                                <table id="table-atk" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Jenis</th>
                                            <th>Barang</th>
                                            <th>Spesifikasi</th>
                                            <th class="text-center">Pengadaan</th>
                                            <th class="text-center">Distribusi</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $dataChartAtk = json_decode($dataChartAtk) @endphp
                                        @foreach ($dataChartAtk->atk as $dataAtk)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('admin-user/atk/barang/riwayat-semua/'. Crypt::encrypt($dataAtk->spesifikasi)) }}" class="btn btn-primary">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true

        })

        $("#table-unitkerja").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": false,
            "paging": false,
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [11, 25, 50, -1],
                [11, 25, 50, "Semua"]
            ],
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        let chartUsulanAtk = JSON.parse(`<?php echo $usulanChartAtk; ?>`)
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(chartUsulanAtk);

            var options = {
                height: 300,
                chart: {
                    subtitle: 'Rekapitulasi Total Usulan',
                },
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    })
</script>

@endsection

@endsection
