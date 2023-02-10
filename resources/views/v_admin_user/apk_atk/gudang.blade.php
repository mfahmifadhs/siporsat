@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Gudang ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Gudang ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6 col-12">
                <a href="{{ url('admin-user/atk/gudang/daftar-transaksi/Pembelian') }}">
                    <div class="card bg-success border border-dark">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>{{ $riwayatTotal->where('kategori_transaksi', 'Pembelian')->count() }} <small>Pembelian</small> </h3>
                                    <h6 class="font-weight-bold">Barang Masuk</h6>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-arrow-circle-up fa-4x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-12">
                <a href="{{ url('admin-user/atk/gudang/daftar-transaksi/Permintaan') }}">
                    <div class="card bg-danger border border-dark">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3>{{ $riwayatTotal->where('kategori_transaksi', 'Permintaan')->count() }} <small>Permintaan</small> </h3>
                                    <h6 class="font-weight-bold">Barang Keluar</h6>
                                </div>
                                <div class="col-md-2">
                                    <i class="fas fa-arrow-circle-down fa-4x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-12">
                <div class="col-md-12 col-12 p-0">
                    <a href="{{ url('admin-user/atk/gudang/referensi/daftar') }}" class="btn btn-primary btn-block border-dark">
                        <i class="fas fa-cubes fa-2x"></i>
                        <h5>Daftar Referensi ATK</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-10 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            <i class="fas fa-chart-bar"></i> &nbsp; GRAFIK TOTAL BARANG MASUK & KELUAR DI GUDANG ATK

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
                            <i class="fas fa-table"></i> &nbsp; REKAPITULASI BARANG MASUK & KELUAR
                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="table-unitkerja" class="table table-striped m-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Unit Kerja</th>
                                                    <th class="text-center">Jumlah Permintaan Barang Keluar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($riwayatUker as $i => $row)
                                                <tr>
                                                    <td class="text-center">{{ $i+1 }}</td>
                                                    <td>{{ $row->unit_kerja }}</td>
                                                    <td class="text-center">
                                                        @php
                                                        $totalUsulan = $riwayatTotal->where('unit_kerja_id', $row->id_unit_kerja)->where('status_riwayat','keluar')->count()
                                                        @endphp
                                                        @if ($totalUsulan)
                                                        <form action="{{ url('super-user/atk/usulan/status/uker') }}" method="POST">
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
                            <i class="fas fa-table"></i> &nbsp; TABEL DAFTAR REFERENSI ATK
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
                                                <a href="{{ url('super-user/atk/barang/riwayat-semua/'. Crypt::encrypt($dataAtk->spesifikasi)) }}" class="btn btn-primary">
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
</section>

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
                    "width": "30%",
                    "targets": 2
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
                    subtitle: 'Rekapitulasi Total Barang Masuk & Keluar',
                },
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    })
</script>

@endsection

@endsection
