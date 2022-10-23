@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Distribusi ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard Distribusi ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
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
            <div class="col-md-6 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-4">
                                <select name="kategori" class="form-control text-capitalize select2-1">
                                    <option value="">-- KATEGORI BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Jenis</label>
                            <div class="col-sm-4">
                                <select name="jenis" id="jenis`+ i +`" class="form-control text-capitalize select2-2">
                                    <option value="">-- JENIS BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select name="nama" id="barang`+ i +`" class="form-control text-capitalize select2-3">
                                    <option value="">-- NAMA BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                            <div class="col-sm-4">
                                <select name="merk" id="merktipe`+ i +`" class="form-control text-capitalize select2-4">
                                    <option value="">-- MERK/TIPE BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mr-2">
                            <div class="row">
                                <button id="searchChartData" class="btn btn-primary ml-2">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ url('super-user/aadb/dashboard') }}" class="btn btn-danger ml-2">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart-google-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                        <div id="notif-konten-chart"></div>
                        <div class="table">
                            <table id="table-kendaraan" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Merk/Tipe Barang</th>
                                        <th>Stok Barang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                    @foreach ($googleChartData1->atk as $dataAtk)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataAtk->subkelompok_atk }}</td>
                                        <td>{{ $dataAtk->merk_atk}}</td>
                                        <td>{{ $dataAtk->total_atk }}</td>
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
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        let total = 1
        let j = 0

        for (let i = 1; i <= 4; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('super-user/atk/select2-dashboard/` + i + `/distribusi') }}`,
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

    });

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
            ['Jenis ATK', 'Stok']
        ]
        console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Jenis ATK',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let kategori = $('select[name="kategori"').val()
        let jenis = $('select[name="jenis"').val()
        let nama = $('select[name="nama"').val()
        let merk = $('select[name="merk"').val()
        let url = ''

        if (kategori || jenis || nama || merk ) {
            url = '<?= url("/super-user/atk/grafik?kategori='+kategori+'&jenis='+jenis+'&nama='+nama+'&merk='+merk+'") ?>'
        } else {
            url = '<?= url("/super-user/atk/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                console.log(res.message);
                let dataTable = $('#table-kendaraan').DataTable()
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
                            element.subkelompok_atk,
                            element.jenis_atk,
                            element.kategori_atk,
                            element.merk_atk
                        ]).draw(false)
                    })

                } else {
                    dataTable.clear()
                    dataTable.draw()
                    // $('.notif-tidak-ditemukan').remove();
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
