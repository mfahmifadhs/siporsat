@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Dashboard Pengelolaan Alat Angkutan Darat Bermotor (AADB)</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 form-group">
                <div class="form-group col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn-block btn btn-primary">
                        <img src="https://cdn-icons-png.flaticon.com/512/2962/2962303.png" width="50" height="50">
                        <h5 class="font-weight-bold mt-1">Usulan <br> Pengadaan</h5>
                    </a>
                </div>
                <div class="form-group col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn-block btn btn-primary">
                        <img src="https://cdn-icons-png.flaticon.com/512/4659/4659844.png" width="50" height="50">
                        <h5 class="font-weight-bold mt-1">Usulan <br> Servis</h5>
                    </a>
                </div>
                <div class="form-group col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn-block btn btn-primary">
                        <img src="https://cdn-icons-png.flaticon.com/512/3389/3389596.png" width="50" height="50">
                        <h5 class="font-weight-bold mt-1">Usulan Perpanjangan STNK</h5>
                    </a>
                </div>
                <div class="form-group col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn-block btn btn-primary">
                        <img src="https://cdn-icons-png.flaticon.com/512/2915/2915450.png" width="50" height="50">
                        <h5 class="font-weight-bold mt-1">Usulan <br> Voucher BBM</h5>
                    </a>
                </div>
            </div>
            <div class="col-md-10 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_aadb">
                                    <option value="">-- Pilih Jenis AADB --</option>
                                    <option value="bmn">BMN</option>
                                    <option value="sewa">SEWA</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_kendaraan">
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    @foreach ($jenisKendaraan as $item)
                                    <option value="{{$item->id_jenis_kendaraan}}">{{$item->jenis_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="merk_kendaraan">
                                    <option value="">-- Pilih Merk Kendaraan --</option>
                                    @foreach ($merk as $item)
                                    <option value="{{$item->merk_kendaraan}}">{{$item->merk_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tahun_kendaraan">
                                    <option value="">-- Pilih Tahun Kendaraan --</option>
                                    @foreach ($tahun as $item)
                                    <option value="{{$item->tahun_kendaraan}}">{{$item->tahun_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="pengguna">
                                    <option value="">-- Pilih Pengguna --</option>
                                    @foreach ($pengguna as $item)
                                    <option value="{{$item->pengguna}}">{{$item->pengguna}}</option>
                                    @endforeach
                                </select>
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
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                        <div class="table">
                            <table id="table-kendaraan" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis AADB</th>
                                        <th>Unit Kerja</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Merk Kendaraan</th>
                                        <th>Tahun Kendaraan</th>
                                        <th>Pengguna</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                    @foreach ($googleChartData1->kendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>{{ $dataKendaraan->unit_kerja }}</td>
                                        <td>{{ $dataKendaraan->jenis_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->pengguna }}</td>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-kendaraan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
    })

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
        console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Kendaraan',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let merk_kendaraan = $('select[name="merk_kendaraan"').val()
        let tahun_kendaraan = $('select[name="tahun_kendaraan"').val()
        let pengguna = $('select[name="pengguna"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || unit_kerja || jenis_kendaraan || merk_kendaraan || merk_kendaraan || tahun_kendaraan || pengguna) {
            url = '<?= url("/super-user/aadb/dashboard/grafik?jenis_aadb='+jenis_aadb+'&unit_kerja='+unit_kerja+'&jenis_kendaraan='+jenis_kendaraan+'&merk_kendaraan='+merk_kendaraan+'&tahun_kendaraan='+tahun_kendaraan+'&pengguna='+pengguna+'") ?>'
        } else {
            url = '<?= url("/super-user/aadb/dashboard/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
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
                            element.jenis_aadb,
                            element.unit_kerja,
                            element.jenis_kendaraan,
                            element.merk_kendaraan,
                            element.tahun_kendaraan,
                            element.pengguna
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
