@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan rdn</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard rdn</li>
                </ol>
            </div>
        </div>
    </div>
</div>

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
                                    <label>Golongan Rumah</label> <br>
                                    <select id="" class="form-control" name="golongan_rumah">
                                        <option value="">Semua Golongan Rumah</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Lokasi Kota</label> <br>
                                    <select id="" class="form-control" name="lokasi_kota">
                                        <option value="">Semua Lokasi Kota</option>
                                        @foreach ($lokasiKota as $dataLokasi)
                                        <option value="{{$dataLokasi->lokasi_kota}}">{{$dataLokasi->lokasi_kota}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Kondisi Rumah</label> <br>
                                    <select id="" class="form-control" name="kondisi_rumah">
                                        <option value="">Semua Kondisi</option>
                                        <option value="1">Baik</option>
                                        <option value="2">Rusak Ringan</option>
                                        <option value="3">Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-admin/rdn/dashboard') }}" class="btn btn-danger">
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
                                <table id="table-rumah" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Alamat Lengkap</th>
                                            <th>LB / LT</th>
                                            <th>Penghuni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->rumah as $dataRumah)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>
                                                <b>
                                                    Gol. {{ $dataRumah->golongan_rumah }} /
                                                    NUP. {{ $dataRumah->nup_rumah }} /
                                                    {{ $dataRumah->lokasi_kota }}
                                                </b> <br>
                                                {{ $dataRumah->alamat_rumah }}
                                            </td>
                                            <td>
                                                {{ $dataRumah->luas_bangunan }} m<sup>2</sup> /
                                                {{ $dataRumah->luas_tanah }} m<sup>2</sup>
                                            </td>
                                            <td>
                                                <label>{{ $dataRumah->nama_pegawai }}</label>
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
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        $("#table-rumah").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
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
            ['Lokasi Kota', 'Jumlah']
        ]

        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Rumah Dinas Negara',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let golongan_rumah = $('select[name="golongan_rumah"').val()
        let lokasi_kota = $('select[name="lokasi_kota"').val()
        let kondisi_rumah = $('select[name="kondisi_rumah"').val()
        let url = ''

        if (golongan_rumah || lokasi_kota || kondisi_rumah) {
            url = '<?= url("/super-admin/rdn/grafik?golongan_rumah='+golongan_rumah+'&lokasi_kota='+lokasi_kota+'&kondisi_rumah='+kondisi_rumah+'") ?>'
        } else {
            url = '<?= url("/super-admin/rdn/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-rumah').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)
                    console.log(data)
                    dataTable.clear()
                    dataTable.draw()
                    let no = 1

                    data.table.forEach(element => {
                        dataTable.row.add([
                            no++,
                            `<b> Gol.` + element.golongan_rumah + `/ NUP.` + element.nup_rumah + `/` + element.lokasi_kota + `</b> <br>` + element.alamat_rumah,
                            element.luas_bangunan + ` m<sup>2</sup> / ` + element.luas_tanah + ` m<sup>2</sup>`,
                            element.nama_pegawai == null ? '' : element.nama_pegawai,

                        ]).draw(false)
                    })

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
