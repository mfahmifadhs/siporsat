@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
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
            <div class="col-md-5 form-group">
                <div class="card card-outline card-primary" style="height:100%;">
                    <div class="card-header">
                        <h4 class="card-title">
                            <small><i class="fas fa-table"></i> <b>MASA JATUH TEMPO STNK</b></small>
                        </h4>
                        <!--<div class="card-tools">-->
                        <!--    <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">-->
                        <!--        <i class="fas fa-minus"></i>-->
                        <!--    </button>-->
                        <!--</div>-->
                    </div>
                    <div class="card-body">
                        <table id="table-jatuh-tempo" class="table table-bordered table-striped m-0 small">
                            <thead>
                                <tr>
                                    <th>No</td>
                                    <th>Unit Kerja</td>
                                    <th>Kendaraan</td>
                                    <th>Pengguna</td>
                                    <th>Masa Jatuh Tempo STNK</td>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach ($stnk as $jatuhTempo)
                                @if(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($jatuhTempo->mb_stnk_plat_kendaraan)) < 365) <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $jatuhTempo->unit_kerja }}</td>
                                <td>
                                    {{ $jatuhTempo->no_plat_kendaraan }} <br>
                                    {{ $jatuhTempo->merk_tipe_kendaraan }}
                                </td>
                                <td>
                                    {{ $jatuhTempo->pengguna }} <br>
                                    {{ $jatuhTempo->jabatan }}
                                </td>
                                <td>
                                    <span class="badge badge-sm badge-pill badge-danger">
                                        {{ \Carbon\Carbon::parse($jatuhTempo->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                    </span>
                                </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-7 form-group">
                <div class="card card-primary card-outline" style="height:100%;">
                    <div class="card-header">
                        <h4 class="card-title">
                            <small><i class="fas fa-table"></i> <b>SERVIS RECORD</b></small>
                        </h4>
                        <!--<div class="card-tools">-->
                        <!--    <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">-->
                        <!--        <i class="fas fa-minus"></i>-->
                        <!--    </button>-->
                        <!--</div>-->
                    </div>
                    <div class="card-body">
                        <table id="table-servis" class="table table-bordered table-striped m-0 small">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Unit Kerja</th>
                                    <th>Kendaraan</th>
                                    <th>Kilometer</th>
                                    <th>Waktu Servis</th>
                                    <th>Waktu Ganti Oli</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($jadwalServis as $dataJadwal)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $dataJadwal->unit_kerja }}</td>
                                    <td>
                                        @if ($dataJadwal->no_plat_kendaraan != '-')
                                        {{ $dataJadwal->no_plat_kendaraan }} <br>
                                        @endif
                                        {{ $dataJadwal->merk_tipe_kendaraan }}
                                    </td>
                                    <td>
                                        @if ($dataJadwal->km_terakhir == null) 0 @endif
                                        {{ $dataJadwal->km_terakhir }} Km
                                    </td>
                                    <td>{{ $dataJadwal->km_terakhir + $dataJadwal->km_servis }} Km</td>
                                    <td>{{ $dataJadwal->km_terakhir + $dataJadwal->km_ganti_oli }} Km</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#servis{{ $dataJadwal->id_jadwal_servis }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="servis{{ $dataJadwal->id_jadwal_servis }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    @if ($dataJadwal->no_plat_kendaraan != '-' || $dataJadwal->no_plat_kendaraan != NULL)
                                                    {{ $dataJadwal->no_plat_kendaraan }} -
                                                    @endif
                                                    {{ $dataJadwal->merk_tipe_kendaraan }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-4">Kilometer Terakhir</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group mb-3">
                                                            <input type="text" name="km_terakhir" class="form-control" value="{{ $dataJadwal->km_terakhir }}" readonly>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <b>Km</b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-4">Kilometer Servis</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group mb-3">
                                                            <input type="text" name="km_servis" class="form-control" value="{{ $dataJadwal->km_servis }}" readonly>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <b>Km</b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-4">Kilometer Ganti Oli</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group mb-3">
                                                            <input type="text" name="km_ganti_oli" class="form-control" value="{{ $dataJadwal->km_ganti_oli }}" readonly>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">
                                                                    <b>Km</b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-outline card-primary" style="height: 100%;">
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
                                    <option value="">Semua Unit Kerja</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_kendaraan">
                                    <option value="">Semua Jenis Kendaraan</option>
                                    @foreach ($jenisKendaraan as $item)
                                    <option value="{{$item->id_jenis_kendaraan}}">{{$item->jenis_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="merk_tipe_kendaraan">
                                    <option value="">Semua Merk Kendaraan</option>
                                    @foreach ($merk as $item)
                                    <option value="{{$item->merk_tipe_kendaraan}}">{{$item->merk_tipe_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tahun_kendaraan">
                                    <option value="">Semua Tahun Kendaraan</option>
                                    @foreach ($tahun as $item)
                                    <option value="{{$item->tahun_kendaraan}}">{{ $item->tahun_kendaraan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="pengguna">
                                    <option value="">Semua Pengguna</option>
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
                        <div id="konten-chart-google-chart">
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
                                        <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
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
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $("#table-jatuh-tempo").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'Semua']
            ]
        })

        $("#table-servis").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            pageLength: 5,
            lengthMenu: [
                [5, 10, 20, -1],
                [5, 10, 20, 'Semua']
            ]
        })

        $("#table-kendaraan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('super-user/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
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
            height: 500,
            legend: {
                'position': 'top',
                'alignment': 'center',
                'maxLines': '2'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let merk_tipe_kendaraan = $('select[name="merk_tipe_kendaraan"').val()
        let tahun_kendaraan = $('select[name="tahun_kendaraan"').val()
        let pengguna = $('select[name="pengguna"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || unit_kerja || jenis_kendaraan || merk_tipe_kendaraan || tahun_kendaraan || pengguna) {
            url = '<?= url("/super-user/aadb/dashboard/grafik?jenis_aadb='+jenis_aadb+'&unit_kerja='+unit_kerja+'&jenis_kendaraan='+jenis_kendaraan+'&merk_tipe_kendaraan='+merk_tipe_kendaraan+'&tahun_kendaraan='+tahun_kendaraan+'&pengguna='+pengguna+'") ?>'
        } else {
            url = '<?= url("/super-user/aadb/dashboard/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
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
                            element.merk_tipe_kendaraan,
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


<!-- @extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0">laporan {{$aksi}} kendaraan</h1>
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
                                    <a href="{{ url('super-user/aadb/laporan/'.$aksi.'/daftar') }}" class="btn btn-danger"><i class="fas fa-undo"></i></a>
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
                            <label>Laporan Usulan Alat Angkutan Darat Bermotor (AADB)</label>
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
                            <label>Daftar Usulan Alat Angkutan Darat Bermotor (AADB)</label>
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
        if (form || bulan || unit_kerja || status_pengajuan || status_proses) {
            url = '<?= url("/super-user/aadb/grafik-laporan?form='+form+'&bulan='+bulan+'&unit_kerja='+unit_kerja+'&status_pengajuan='+status_pengajuan+'&status_proses='+status_proses+'") ?>'
        } else {
            url = '<?= url("/super-user/aadb/grafik-laporan") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTableDaftar = $('#table-pengajuan').DataTable()
                let dataTableLaporan = $('#table-laporan').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTableDaftar.clear()
                    dataTableLaporan.clear()
                    dataTableDaftar.draw()
                    dataTableLaporan.draw()
                    let no = 1
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

@endsection -->
