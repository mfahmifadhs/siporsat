@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan BMN OLDAT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard OLDAT</li>
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
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Menunggu Persetujuan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Sedang Diproses</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Konfirmasi BAST Pengusul</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Konfirmasi BAST Kabag RT</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Selesai BAST</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Ditolak</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            GRAFIK USULAN OLDAT & MEUBELAIR
                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool active text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <div id="reportChart"></div>
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
                        <table id="table-unitkerja" class="table table-striped table-bordered m-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Unit Kerja</th>
                                    <th class="text-center">Total Usulan Pengadaan</th>
                                    <th class="text-center">Total Usulan Perbaikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usulanUker as $i => $row)
                                <tr>
                                    <td class="text-center">{{ $i+1 }}</td>
                                    <td>{{ $row->unit_kerja }}</td>
                                    <td class="text-center">
                                        <h5>{{ $row->total_pengadaan }}</h5>
                                    </td>
                                    <td class="text-center">
                                        <h5>{{ $row->total_perbaikan }}</h5>
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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline" id="accordion">
                    <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                        <div class="card-header">
                            <div class="card-tools">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </div>
                        </div>
                    </a>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label>Nama Barang</label> <br>
                                    <select name="barang" id="barang`+ i +`" class="form-control text-capitalize select2-1" style="width: 100%;">
                                        <option value="">-- NAMA BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Unit Kerja</label> <br>
                                    <select name="unitkerja" id="unitkerja`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- UNIT KERJA --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Kondisi</label> <br>
                                    <select name="kondisi" id="kondisi`+ i +`" class="form-control text-capitalize select2-3" style="width: 100%;">
                                        <option value="">-- KONDISI BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-admin/oldat/dashboard') }}" class="btn btn-danger">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                <div class="alert alert-secondary loading" role="alert">
                                    Sedang menyiapkan data ...
                                </div>
                                <table id="table-barang" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe</th>
                                            <th>Pengguna</th>
                                            <th>Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">

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
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
        })

        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            order: [
                [0, 'asc']
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');

        setTimeout(showTable(JSON.parse(`<?php echo $googleChartData; ?>`)), 1000);
    })

    // Chart Usulan
    let chartUsulanData = JSON.parse(`<?php echo $chartOldat; ?>`)

    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable(chartUsulanData);
        var options = {
            height: 300,
            chart: {
                subtitle: 'Rekapitulasi Total Usulan',
            },
        };
        var chart = new google.charts.Bar(document.getElementById('reportChart'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>

<script>
    // Chart Barang
    let j = 0

    for (let i = 1; i <= 3; i++) {
        $(".select2-" + i).select2({
            ajax: {
                url: `{{ url('super-admin/oldat/select2-dashboard/` + i + `/barang') }}`,
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        })
    }

    function showTable(data) {
        let dataTable = $('#table-barang').DataTable()
        let dataBarang = data.barang

        dataTable.clear()
        let no = 1
        dataBarang.forEach(element => {
            dataTable.row.add([
                no++,
                element.kategori_barang,
                element.barang,
                element.unit_kerja,
                element.kondisi_barang
            ])
        });
        dataTable.draw()
        $('.loading').hide()
    }

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
            ['Kategori Barang', 'Jumlah']
        ]

        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Barang',
            legend: {
                'position': 'top',
                'alignment': 'center',
                'maxLines': 10
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let barang = $('select[name="barang"').val()
        let unit_kerja = $('select[name="unitkerja"').val()
        let kondisi = $('select[name="kondisi"').val()
        let url = ''

        $('.loading').show()
        let dataTable = $('#table-barang').DataTable()
        dataTable.clear()
        dataTable.draw()

        if (barang || unit_kerja || kondisi) {
            url =
                '<?= url("/super-admin/oldat/grafik?barang='+barang+'&unit_kerja='+unit_kerja+'&kondisi='+kondisi+'") ?>'
        } else {
            url = '<?= url('/super-admin/oldat/grafik') ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTable = $('#table-barang').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTable.clear()
                    dataTable.draw()
                    let no = 1
                    data.table.forEach(element => {
                        dataTable.row.add([
                            no++,
                            element.kategori_barang,
                            element.barang,
                            element.unit_kerja,
                            element.kondisi_barang
                        ])
                    });
                    dataTable.draw()
                    $('.loading').hide()

                } else {
                    dataTable.clear()
                    dataTable.draw()
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').hide();
                    var html = ''
                    html += '<div class="notif-tidak-ditemukan">'
                    html += '<div class="card bg-secondary py-4">'
                    html += '<div class="card-body text-white">'
                    html += '<h5 class="mb-4 font-weight-bold text-center">'
                    html += 'Data tidak dapat ditemukan'
                    html += '</h5>'
                    html += '</div>'
                    html += '</div>'
                    html += '</div>'
                    $('#notif-konten-chart').append(html)
                }
            },
        })
    })
</script>
@endsection

@endsection
