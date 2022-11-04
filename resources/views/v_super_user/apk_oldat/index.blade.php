@extends('v_super_user.layout.app')

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

<section class="content text-capitalize">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-12">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/pengadaan') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-car fa-2x"></i> <br>
                            Usulan <br> Pengadaan </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/perbaikan') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-tools fa-2x"></i> <br>
                            Usulan <br> Perbaikan </a>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><small class="font-weight-bold">Usulan Pengadaan</small></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-default btn-xs" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 form-group small">Menunggu Persetujuan</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','pengadaan')->where('status_proses_id', 1)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Sedang Diproses</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','pengadaan')->where('status_proses_id', 2)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','pengadaan')->where('status_proses_id', 4)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Selesai</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','pengadaan')->where('status_proses_id', 5)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><small class="font-weight-bold">Usulan Servis</small></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-default btn-xs" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 form-group small">Menunggu Persetujuan</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','perbaikan')->where('status_proses_id', 1)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Sedang Diproses</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','perbaikan')->where('status_proses_id', 2)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','perbaikan')->where('status_proses_id', 4)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Selesai</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form','perbaikan')->where('status_proses_id', 5)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-7 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><small class="font-weight-bold">Daftar Usulan</small></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-default btn-xs" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pengusul</th>
                                    <th>Usulan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="text-center pt-3">{{ $no++ }}</td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        No. Surat : {{ $dataUsulan->no_surat_usulan }} <br>
                                        Pengusul : {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }} <br>
                                        Unit Kerja : {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                    </td>
                                    <td class="small text-capitalize">
                                        @if ($dataUsulan->jenis_form == 'pengadaan')
                                        @foreach($dataUsulan->detailPengadaan as $i => $dataPengadaan)
                                        {{ ($i +1) }}.
                                        {{ ucfirst(strtolower($dataPengadaan->kategori_barang)).' '.$dataPengadaan->merk_barang }}
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 'perbaikan')
                                        @foreach($dataUsulan->detailPerbaikan as $i => $dataPerbaikan)
                                        {{
                                            ($i +1).'. '.ucfirst(strtolower($dataPerbaikan->kategori_barang)).' '.$dataPerbaikan->merk_tipe_barang
                                        }} <br>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="pt-2 small">
                                        Status Pengajuan : <br>
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                        @endif <br>
                                        Status Proses : <br>
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi kabag rt</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center pt-4">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>

                                        <div class="dropdown-menu">
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/pengajuan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/oldat/pengajuan/'. $dataUsulan->jenis_form.'/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses Penyerahan
                                            </a>
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> BAST
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        @if($dataUsulan->status_pengajuan_id == '')
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu persetujuan</b>
                                        </span>
                                        @elseif ($dataUsulan->status_pengajuan_id == 1)
                                        @if($dataUsulan->status_proses_id == 2)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">usulan sedang diproses</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 3)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi pengusul</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 4)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi kabag rt</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 5)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">selesai</b>
                                        </span>
                                        @endif
                                        @endif
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-capitalize">
                                        <div class="form-group row">
                                            <div class="col-md-12 text-center">
                                                <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form_usulan }}
                                                    <hr>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <h6 class="col-md-12 font-weight-bold text-muted">
                                                Informasi Pengusul
                                            </h6>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Nama Pengusul </label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->nama_pegawai }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->jabatan.' '.$dataUsulan->keterangan_pegawai }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Unit Kerja</label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->unit_kerja }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                            <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                        </div>
                                        @if($dataUsulan->jenis_form == 'pengadaan')
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->rencana_pengguna }}</div>
                                        </div>
                                        @else
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Biaya Perbaikan :</label></div>
                                            <div class="col-md-8">: Rp {{ number_format($dataUsulan->total_biaya, 0, ',', '.') }}</div>
                                        </div>
                                        @endif
                                        <div class="form-group row mt-4">
                                            <h6 class="col-md-12 font-weight-bold text-muted">
                                                Informasi Barang
                                            </h6>
                                        </div>
                                        @if($dataUsulan->jenis_form == 'pengadaan')
                                        @foreach($dataUsulan->detailPengadaan as $dataPengadaan )
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4"><label>Nama Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->kategori_barang }}</div>
                                                <div class="col-md-4"><label>Merk </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->merk_barang }}</div>
                                                <div class="col-md-4"><label>Jumlah </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->jumlah_barang.' '.$dataPengadaan->satuan_barang }}</div>
                                                <div class="col-md-4"><label>Estimasi Biaya</label></div>
                                                <div class="col-md-8">: Rp {{ number_format($dataPengadaan->estimasi_biaya, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        @foreach($dataUsulan->detailPerbaikan as $dataPerbaikan)
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4"><label>Nama Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->kategori_barang }}</div>
                                                <div class="col-md-4"><label>Merk Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->merk_tipe_barang }}</div>
                                                <div class="col-md-4"><label>Pengguna </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->nama_pegawai }}</div>
                                                <div class="col-md-4"><label>Unit Kerja</label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->unit_kerja }}</div>
                                                <div class="col-md-4"><label>Tahun Perolehan</label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->tahun_perolehan }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <div class="col-md-12">
                                            <span style="float: left;">
                                                @if($dataUsulan->status_proses_id == 5)
                                                <a href="{{ url('super-user/oldat/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat BAST
                                                </a>
                                                @endif
                                            </span>
                                            <span style="float: right;">
                                                <a href="{{ url('super-user/oldat/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                </a>
                                            </span>
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
        </div>
    </div>
</section>

<section class="content text-capitalize">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12 form-group">
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
            <div class="col-md-12 col-12 form-group">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mt-1 font-weight-bold small">Filter AADB</h4>
                        <div class="card-tools">
                            <button type="button" class="btn bg-primary btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label>Nama Barang</label> <br>
                                <select name="barang" id="barang`+ i +`" class="form-control text-capitalize select2-1">
                                    <option value="">-- NAMA BARANG --</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Unit Kerja</label> <br>
                                <select name="unitkerja" id="unitkerja`+ i +`" class="form-control text-capitalize select2-2">
                                    <option value="">-- UNIT KERJA --</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Kondisi</label> <br>
                                <select name="kondisi" id="kondisi`+ i +`" class="form-control text-capitalize select2-3">
                                    <option value="">-- KONDISI BARANG --</option>
                                </select>
                            </div>
                            <div class="col-sm-12 mt-2 text-right">
                                <button id="searchChartData" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ url('super-user/oldat/dashboard') }}" class="btn btn-danger">
                                    <i class="fas fa-undo"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div id="konten-chart-google-chart">
                        <div id="piechart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body">
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
</section>

@section('js')
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

    let j = 0

    for (let i = 1; i <= 3; i++) {
        $(".select2-" + i).select2({
            ajax: {
                url: `{{ url('super-user/oldat/select2-dashboard/` + i + `/barang') }}`,
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
        console.log('start')
        let dataBarang = data.barang
        // console.log(dataBarang)

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
        console.log('finish')
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
        console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Barang',
            legend: {
                'position': 'left',
                'alignment': 'center'
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
        console.log(barang)
        if (barang || unit_kerja || kondisi) {
            url =
                '<?= url("/super-user/oldat/grafik?barang='+barang+'&unit_kerja='+unit_kerja+'&kondisi='+kondisi+'") ?>'
        } else {
            url = '<?= url('/super-user/oldat/grafik') ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-barang').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTable.clear()
                    dataTable.draw()
                    let no = 1
                    console.log(res)
                    data.table.forEach(element => {
                        dataTable.row.add([
                            no++,
                            element.kategori_barang,
                            element.barang,
                            element.unit_kerja,
                            element.kondisi_barang
                        ]).draw(false)
                    });

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
