@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard AADB</li>
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
                                    <label>Jenis AADB</label> <br>
                                    <select id="" class="form-control" name="jenis_aadb" style="width: 100%;">
                                        <option value="">-- JENIS AADB --</option>
                                        <option value="bmn">BMN</option>
                                        <option value="sewa">SEWA</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Unit Kerja</label> <br>
                                    <select name="unit_kerja" id="unitkerja`+ i +`" class="form-control text-capitalize select2-1" style="width: 100%;">
                                        <option value="">-- UNIT KERJA --</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label>Nama Kendaraan</label> <br>
                                    <select name="jenis_kendaraan" id="kendaraan`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- NAMA KENDARAAN --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-admin/aadb/dashboard') }}" class="btn btn-danger">
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
                                <table id="table-aadb" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            <th>Pengguna</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->kendaraan as $dataAadb)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <b class="text-primary">{{ $dataAadb->kode_barang.'.'.$dataAadb->nup_barang }}</b> <br>
                                                {{ $dataAadb->merk_tipe_kendaraan.' '.$dataAadb->tahun_kendaraan }} <br>
                                                {{ $dataAadb->no_plat_kendaraan }} <br>
                                                {{ $dataAadb->jenis_aadb }}
                                            </td>
                                            <td>
                                                No. BPKB : {{ $dataAadb->no_bpkb }} <br>
                                                No. Rangka : {{ $dataAadb->no_rangka }} <br>
                                                No. Mesin : {{ $dataAadb->no_mesin }} <br>
                                                Masa Berlaku STNK : <br>
                                                @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)) < 365 && $dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-danger">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @elseif ($dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-success">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @endif<br>
                                            </td>
                                            <td>
                                                Unit Kerja : {{ $dataAadb->unit_kerja }} <br>
                                                Pengguna : {{ $dataAadb->pengguna }} <br>
                                                Jabatan : {{ $dataAadb->jabatan }} <br>
                                                Pengemudi : {{ $dataAadb->pengemudi }}
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

        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
        })

        let j = 0

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('super-admin/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
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

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || unit_kerja || jenis_kendaraan) {
            url = '<?= url("/super-admin/aadb/grafik?jenis_aadb='+jenis_aadb+'&unit_kerja='+unit_kerja+'&jenis_kendaraan='+jenis_kendaraan+'") ?>'
        } else {
            url = '<?= url("/super-admin/aadb/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTable = $('#table-aadb').DataTable()
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
                            '<b class="text-primary">' + element.kode_barang + '.' + element.nup_barang + '</b>' +
                            '<br>' + element.merk_tipe_kendaraan + ' ' + element.tahun_kendaraan +
                            '<br>' + element.no_plat_kendaraan +
                            '<br>' + element.jenis_aadb,
                            'No. BPKB :' + element.no_bpkb +
                            '<br> No. Rangka :' + element.no_rangka +
                            '<br> No. Mesin :' + element.no_mesin +
                            '<br> Masa Berlaku STNK :' + element.mb_stnk_plat_kendaraan,
                            'Unit Kerja :' + element.unit_kerja +
                            '<br> Pengguna :' + element.pengguna +
                            '<br> Jabatan :' + element.jabatan +
                            '<br> Pengemudi :' + element.pengemudi,
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
