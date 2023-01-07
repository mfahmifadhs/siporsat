@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h4 class="m-0">Daftar ATK</h4>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <b class="font-weight-bold mt-1 text-primary">
                            <i class="fas fa-table"></i> TABEL BARANG ATK
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="notif-konten-chart"></div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card-body border border-default">
                                <div id="konten-chart-google-chart">
                                    <div id="piechart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="card-body border border-default">
                                <table id="table-atk" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis</th>
                                            <th>Barang</th>
                                            <th>Spesifikasi</th>
                                            <th>Pengadaan</th>
                                            <th>Distribusi</th>
                                            <th>Stok</th>
                                            <th>Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->atk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('admin-user/atk/barang/riwayat-semua/'. $dataAtk->spesifikasi) }}" class="btn btn-primary">
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

<!-- <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12 text-right form-group">
                <a href="{{ url('admin-user/atk/barang/detail-kategori/kelompok') }}" class="btn btn-primary btn-sm">Kategori Barang</a>
                <a href="{{ url('admin-user/atk/barang/detail-kategori/jenis') }}" class="btn btn-primary btn-sm">Jenis Barang</a>
                <a href="{{ url('admin-user/atk/barang/detail-kategori/kategori') }}" class="btn btn-primary btn-sm">Barang</a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Barang</h3>
                        <div class="card-tools">
                            <a href="{{ url('admin-user/atk/barang/tambah-atk/4') }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-square"></i> TAMBAH
                            </a>=
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Kelompok</td>
                                    <td>Kategori</td>
                                    <td>Jenis Barang</td>
                                    <td>Nama Barang</td>
                                    <td>Merk/Tipe</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        // Chart
        let chart
        let chartData = JSON.parse(`<?php echo $googleChartData; ?>`)
        let dataChart = chartData.all
        google.charts.load('current', {
            'packages': ['corechart']
        })
        google.charts.setOnLoadCallback(function() {
            drawChart(dataChart)
        })

        function drawChart(dataChart) {

            chartData = [
                ['Jenis Kendaraan', 'Jumlah']
            ]

            dataChart.forEach(data => {
                chartData.push(data)
            })

            var data = google.visualization.arrayToDataTable(chartData);

            var options = {
                title: 'Total Kendaraan',
                titlePosition: 'none',
                is3D: false,
                legend: {
                    'position': 'top',
                    'alignment': 'center',
                    'maxLines': '5'
                },
            }

            chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    })
</script>

@endsection
@endsection
