@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">GEDUNG DAN BANGUNAN</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard GDN</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <a href="{{ route('gdn.show', ['aksi' => 'proses', 'id' => 1]) }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Menunggu Persetujuan</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-12">
                        <a href="{{ route('gdn.show', ['aksi' => 'proses', 'id' => 2]) }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Sedang Diproses</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-12">
                        <a href="{{ route('gdn.show', ['aksi' => 'proses', 'id' => 3]) }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Proses Berita Acara</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-12">
                        <a href="{{ route('gdn.show', ['aksi' => 'proses', 'id' => 5]) }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Selesai Berita Acara</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12 col-12">
                        <a href="{{ url('super-user/gdn/usulan/status/ditolak') }}">
                            <div class="card bg-default border border-primary">
                                <div class="card-body">
                                    <h5>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                    <h6 class="font-weight-bold">Ditolak</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            GRAFIK URUSAN GEDUNG DAN BANGUNAN
                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool active text-white" data-card-widget="collapse">
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
                <div class="card">
                    <div class="card-header border-transparent bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            REKAPITULASI USULAN UNIT KERJA
                        </b>
                    </div>
                    <div class="card-body">
                        <table id="table-unitkerja" class="table table-striped m-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Unit Kerja</th>
                                    <th class="text-center">Jumlah Usulan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usulanUker as $i => $row)
                                <tr>
                                    <td class="text-center">{{ $i+1 }}</td>
                                    <td>{{ $row->unit_kerja }}</td>
                                    <td class="text-center">
                                        <h5>{{ $row->total_usulan }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ url('super-user/gdn/usulan/status/'. $row->id_unit_kerja) }}" class="btn btn-primary btn-sm">
                                            Detail <i class="fas fa-arrow-circle-right fa-1x"></i>
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
            "autoWidth": false,
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
                    "width": "15%",
                    "targets": 3
                },
            ]

        })

        let chartData = JSON.parse(`<?php echo $chartGdn; ?>`)
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
    })
</script>

@endsection



@endsection
