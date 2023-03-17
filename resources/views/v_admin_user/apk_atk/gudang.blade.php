@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Tulis Kantor (ATK)</h1>
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
        <v class="row">
            <div class="col-md-12 col-12 mb-3">
                <a href="{{ url('admin-user/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
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
            <div class="col-md-12 col-12">
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
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "columnDefs": [{
                    "width": "0%",
                    "targets": 0
                },
                {
                    "width": "15%",
                    "targets": 1
                },
                {
                    "width": "15%",
                    "targets": 2
                },
                {
                    "width": "10%",
                    "targets": 4
                },
                {
                    "width": "13%",
                    "targets": 5
                },
                {
                    "width": "13%",
                    "targets": 6
                },
                {
                    "width": "10%",
                    "targets": 7
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
