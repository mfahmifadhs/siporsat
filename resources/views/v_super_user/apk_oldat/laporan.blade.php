@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0">laporan {{$aksi}} barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">laporan {{$aksi}} barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <input type="hidden" name="jenis_form" value="{{ $aksi }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <input type="month" name="bulan" class="form-control" value="{{ \Carbon\Carbon::now()->month }}">
                                </div>
                                <div class="form-group">
                                    <label>Unit Kerja</label>
                                    <select name="unit_kerja" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach($unitKerja as $dataUnitKerja)
                                        <option value="{{ $dataUnitKerja->id_unit_kerja }}">{{ $dataUnitKerja->unit_kerja }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status Pengajuan</label>
                                    <select name="status_pengajuan" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Diterima</option>
                                        <option value="2">Ditolak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status Proses</label>
                                    <select name="status_proses" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Menunggu Persetujuan</option>
                                        <option value="2">Sedang Diproses</option>
                                        <option value="3">Menunggu Konfirmasi Pengusul</option>
                                        <option value="4">Menunggu Konfirmasi Kepala Bagian RT</option>
                                        <option value="5">Selesai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button id="searchChartData" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-user/oldat/laporan/pengadaan/daftar') }}" class="btn btn-danger"><i class="fas fa-undo"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart-google-chart">
                            <div id="piechart" style="height: 300px;"></div>
                        </div>
                        <div id="notif-konten-chart"></div>
                        <div class="table-laporan">
                            <label>Laporan Usulan Pengajuan Olah Data BMN & Meubelair</label>
                            <table id="table-laporan" class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <td colspan="6">Total Usulan Penyediaan Barang / Jasa </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" class="py-5">No</td>
                                        <td rowspan="2" class="py-5">Kategori Pengadaan / Penyediaan</td>
                                        <td colspan="3">Status </td>
                                        <td rowspan="2" class="py-5">Bulan</td>
                                    </tr>
                                    <tr>
                                        <td>Ditolak</td>
                                        <td>Sedang Proses Pengadaan</td>
                                        <td>Sudah BAST (Selesai)</td>
                                    </tr>
                                </thead>
                                @php $no = 1; $dataChart = json_decode($chartPengajuan) @endphp
                                <tbody>
                                    @foreach ($dataChart->laporan as $dataLaporan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataLaporan->usulan }}</td>
                                        <td>{{ $dataLaporan->ditolak }}</td>
                                        <td>{{ $dataLaporan->proses }}</td>
                                        <td>{{ $dataLaporan->selesai }}</td>
                                        <td>{{ $dataLaporan->bulan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-daftar mt-4">
                            <label>Daftar Usulan Pengajuan Olah Data BMN & Meubelair</label>
                            <table id="table-pengajuan" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Usulan</th>
                                        <th>Tanggal</th>
                                        <th>Pengusul</th>
                                        <th>Unit Kerja</th>
                                        <th>Total Pengajuan</th>
                                        <th>Status Pengajuan</th>
                                        <th>Status Proses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $dataChart = json_decode($chartPengajuan) @endphp
                                    @foreach ($dataChart->pengajuan as $dataUsulan)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$dataUsulan->id_form_usulan}}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                        <td>{{$dataUsulan->nama_pegawai}}</td>
                                        <td>{{$dataUsulan->unit_kerja}}</td>
                                        <td>{{$dataUsulan->total_pengajuan}} barang</td>
                                        <td>{{$dataUsulan->status_pengajuan}}</td>
                                        <td>{{$dataUsulan->status_proses}}</td>
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
    const monthControl = document.querySelector('input[type="month"]');
    const date = new Date()
    const month = ("0" + (date.getMonth() + 1)).slice(-2)
    const year = date.getFullYear()
    monthControl.value = `${year}-${month}`;

    $(function() {
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Data Master Barang',
                    exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Data Master Barang',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ]
        }).buttons().container().appendTo('#table-pengajuan_wrapper .col-md-6:eq(0)');

        $("#table-laporan").DataTable({
            "responsive"   : true,
            "lengthChange" : false,
            "searching"    : false,
            "info"         : false,
            "paging"       : false,
            "autoWidth"    : false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Laporan Usulan Pengajuan OLDAT'
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Laporan Usulan Pengajuan OLDAT'
                }
            ]
        }).buttons().container().appendTo('#table-laporan_wrapper .col-md-6:eq(0)');
    });

    let chart
    let chartData = JSON.parse(`<?php echo $chartPengajuan; ?>`)
    let dataChart = chartData.all
    google.charts.load('current', {
        'packages': ['corechart']
    })
    google.charts.setOnLoadCallback(function() {
        drawChart(dataChart)
    })

    function drawChart(dataChart) {

        chartData = [
            ['Pengusul', 'Jumlah']
        ]
        // console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Usulan Tahun 2022',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let form = $('input[name="jenis_form"').val()
        let bulan = $('input[name="bulan"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let status_pengajuan = $('select[name="status_pengajuan"').val()
        let status_proses = $('select[name="status_proses"').val()
        let url = ''
        console.log(bulan)
        if (form || bulan || unit_kerja || status_pengajuan || status_proses) {
            url = '<?= url("/super-user/oldat/grafik-laporan?form='+form+'&bulan='+bulan+'&unit_kerja='+unit_kerja+'&status_pengajuan='+status_pengajuan+'&status_proses='+status_proses+'") ?>'
        } else {
            url = '<?= url("/super-user/oldat/grafik-laporan") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTableDaftar  = $('#table-pengajuan').DataTable()
                let dataTableLaporan = $('#table-laporan').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove()
                    $('#konten-chart-google-chart').show()
                    let data = JSON.parse(res.data)
                    console.log(data.bulan)
                    drawChart(data.chart)

                    dataTableDaftar.clear()
                    dataTableLaporan.clear()
                    dataTableDaftar.draw()
                    dataTableLaporan.draw()
                    let no = 1
                    console.log(data.searchLaporan)
                    data.table.forEach(element => {
                        dataTableDaftar.row.add([
                            no++,
                            element.id_form_usulan,
                            element.tanggal_usulan,
                            element.nama_pegawai,
                            element.unit_kerja,
                            element.total_pengajuan,
                            element.status_pengajuan,
                            element.status_proses
                        ]).draw(false)
                    });

                    data.searchLaporan.forEach(element => {
                        dataTableLaporan.row.add([
                            no++,
                            element.usulan,
                            element.ditolak,
                            element.proses,
                            element.selesai,
                            element.bulan
                        ]).draw(false)
                    });

                } else {
                    dataTableDaftar.clear()
                    dataTableLaporan.clear()
                    dataTableDaftar.draw()
                    dataTableLaporan.draw()
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
