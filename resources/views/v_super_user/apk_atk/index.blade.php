@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card border border-secondary">
                    <div class="card-body">
                        <label class="text-muted m-0">Grafik Total Usulan ATK</label><br>
                        <div class="position-relative mb-4">
                            <div id="barchart_material"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group row">
                    <div class="col-md-6 col-12">
                        <a href="{{ url('super-user/atk/usulan/status/1') }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Menunggu Persetujuan</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="{{ url('super-user/atk/usulan/status/2') }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Sedang Diproses</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="{{ url('super-user/atk/usulan/status/5') }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Selesai BAST</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-12">
                        <a href="{{ url('super-user/atk/usulan/status/ditolak') }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Ditolak</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card border border-secondary" style="height: 100%;">
                            <div class="card-body">
                                <label class="text-muted m-0">Stok ketersediaan ATK</label><br>
                                <label class="text-muted small">Sisa stok barang (Total Pembelian - Total Distribusi)</label>
                                <table id="table-stok" class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Total Pembelian</th>
                                            <th>Total Distribusi</th>
                                            <th>Sisa Stok</th>
                                        </tr>
                                    </thead>
                                    @php $no = 1; @endphp
                                    <tbody>
                                        @foreach ($stokAtk as $row)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td class="text-left">{{ $row->barang }}</td>
                                            <td>{{ $row->pembelian.' '.$row->satuan_barang }}</td>
                                            <td>{{ $row->distribusi.' '.$row->satuan_barang }}</td>
                                            <td>{{ $row->stok.' '.$row->satuan_barang }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="card border border-secondary" style="height: 100%;">
                    <div class="card-body">
                        <label class="text-muted m-0">Rekap Usulan ATK</label><br>
                        <label class="text-muted small">Rekapitulasi total usulan distribusi ATK</label>
                        <table id="table-unitkerja" class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Unit Kerja</th>
                                    <th class="text-center">Total Usulan Distribusi</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach ($usulanUker as $i => $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">{{ $row->unit_kerja }}</td>
                                    <td>
                                        @php
                                        $totalUsulan = $usulanTotal->where('unit_kerja_id', $row->id_unit_kerja)->whereIn('jenis_form',['distribusi','permintaan'])->count()
                                        @endphp
                                        @if ($totalUsulan)
                                        <form action="{{ url('super-user/atk/usulan/status/uker') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="jenis_form" value="distribusi">
                                            <input type="hidden" name="id_unit_kerja" value="{{ $row->id_unit_kerja }}">
                                            {{ $totalUsulan }}
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
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {

        let chartData = JSON.parse(`<?php echo $chartAtk; ?>`)

        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(chartData);

            var options = {
                height: 300,
                chart: {
                    subtitle: 'Rekapitulasi Total Usulan',
                },
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()

        $("#table-stok").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [5, 25, 50, -1],
                [5, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-danger mr-2 rounded',
                    title: 'Stok ATK',
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-success mr-2 rounded',
                    title: 'Stok ATK',
                    messageTop: datetime
                }
            ],

        }).buttons().container().appendTo('#table-stok_wrapper .col-md-6:eq(0)');

        $("#table-unitkerja").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-danger mr-2 rounded',
                    title: 'Rekap Usulan ATK',
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-success mr-2 rounded',
                    title: 'Rekap Usulan ATK',
                    messageTop: datetime
                }
            ],

        }).buttons().container().appendTo('#table-unitkerja_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection

@endsection
