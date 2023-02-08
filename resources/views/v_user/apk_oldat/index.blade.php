@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">PEMELIHARAAN OLAH DATA & MEUBELAIR</h4>
            </div>
        </div>
    </div>
</div>

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
		<div class="row">
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Menunggu Persetujuan</span> <br>
                                <small class="text-danger fa-xs">Menunggu Persetujuan Kabag RT</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Sedang Diproses</span> <br>
                                <small class="text-danger fa-xs">Usulan Sedang Diproses Oleh PPK</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Konfirmasi BAST Pengusul</span>
                                <small class="text-danger fa-xs">Konfirmasi Pekerjaan Diterima</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Konfirmasi BAST Kabag RT</span> <br>
                                <small class="text-danger fa-xs">Kabag RT Konfirmasi BAST</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Selesai BAST</span> <br>
                                <small class="text-danger fa-xs">BAST telah di tanda tangani</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Ditolak</span> <br>
                                <small class="text-danger fa-xs">Usulan Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Usulan</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 1%;">No</th>
                                    <th class="text-left" style="width: 44%;">Tanggal / No. Surat</th>
                                    <th style="width: 25%;">Status Pengajuan</th>
                                    <th style="width: 25%;">Status Proses</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr class="text-center">
                                    <td class="text-center pt-3" style="width: 5vh;">{{ $no++ }}</td>
                                    <td class="text-left">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y | HH:mm') }} <br>
                                        {{ $dataUsulan->jenis_form_usulan }} <br>
                                        No. Surat :
                                        @if ($dataUsulan->status_pengajuan_id == 1)
                                        {{ strtoupper($dataUsulan->no_surat_usulan) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="pt-2">
                                        <h6 class="mt-3">
                                            @if($dataUsulan->status_pengajuan_id == 1)
                                            <span class="badge badge-sm badge-pill badge-success">
                                                Disetujui
                                            </span>
                                            @elseif($dataUsulan->status_pengajuan_id == 2)
                                            <span class="badge badge-sm badge-pill badge-danger">Ditolak</span>
                                            @if ($dataUsulan->keterangan != null)
                                            <p class="small mt-2 text-danger">{{ $dataUsulan->keterangan }}</p>
                                            @endif
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="pt-2">
                                    <h6 class="mt-2">
                                            @if($dataUsulan->status_proses_id == 1)
                                            <span class="badge badge-sm badge-pill badge-warning">Menunggu Persetujuan <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning">Sedang Diproses <br> oleh PPK</span>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning">Konfirmasi Barang <br> telah Diterima</span>
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning">Menunggu Konfirmasi BAST <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">Selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center pt-3">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-oldat/'. $dataUsulan->id_form_usulan) }}"
                                                onclick="return confirm('Apakah barang telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @else
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-oldat/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/oldat/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center font-weight-bold">
                                                        <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form }}
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
                                                    <div class="col-md-8">: {{ $dataUsulan->keterangan_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Unit Kerja</label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->unit_kerja }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat Usulan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('unit-kerja/surat/usulan-oldat/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan_id == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('unit-kerja/surat/bast-oldat/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Pekerjaan
                                                    </h6>
                                                </div>
                                                <div class="form-group row small">
                                                    @if ($dataUsulan->jenis_form == 'perbaikan')
                                                    <div class="col-md-12 text-center">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1">No</div>
                                                            <div class="col-sm-2">Kode Barang</div>
                                                            <div class="col-sm-3">Nama Barang</div>
                                                            <div class="col-sm-3">Merk / Tipe</div>
                                                            <div class="col-sm-3">Keterangan Perbaikan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailPerbaikan as $i => $dataPerbaikan)
                                                        @php $i = $i +1; @endphp
                                                        <div class="form-group row text-uppercase">
                                                            <div class="col-md-1">{{ $i }}</div>
                                                            <div class="col-md-2">
                                                                {{ $dataPerbaikan->kode_barang.'.'.$dataPerbaikan->nup_barang }}
                                                            </div>
                                                            <div class="col-md-3">{{ $dataPerbaikan->kategori_barang }}</div>
                                                            <div class="col-md-3">{{ $dataPerbaikan->merk_tipe_barang }}</div>
                                                            <div class="col-md-3">{{ $dataPerbaikan->keterangan_perbaikan }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                    @else
                                                    <div class="col-md-12 text-center">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1">No</div>
                                                            <div class="col-sm-2">Nama Barang</div>
                                                            <div class="col-sm-2">Merk / Tipe</div>
                                                            <div class="col-sm-3">Spesifikasi</div>
 							    <div class="col-sm-2">Jumlah</div>
                                                            <div class="col-sm-2">Estimasi Biaya</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailPengadaan as $i => $dataPengadaan)
                                                        @php $i = $i +1; @endphp
                                                        <div class="form-group row text-uppercase">
                                                            <div class="col-md-1">{{ $i }}</div>
                                                            <div class="col-md-2">{{ $dataPengadaan->kategori_barang }}</div>
                                                            <div class="col-md-2">{{ $dataPengadaan->merk_barang }}</div>
							    <div class="col-md-3">{!! nl2br(e($dataPengadaan->spesifikasi_barang)) !!}</div>
                                                            <div class="col-md-2">{{ $dataPengadaan->jumlah_barang.' '.$dataPengadaan->satuan_barang }}</div>
                                                            <div class="col-md-2">Rp {{ number_format($dataPengadaan->estimasi_biaya, 0, ',', '.') }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                    @endif
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
            <div class="col-md-12 col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-12">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <h3 class="card-title mt-1 font-weight-bold">Daftar Olah Data BMN & Meubelair</h3>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Nama Barang</label> <br>
                                    <select name="barang" id="barang`+ i +`" class="form-control text-capitalize select2-1" style="width: 100%;">
                                        <option value="">-- NAMA BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Kondisi</label> <br>
                                    <select name="kondisi" id="kondisi`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- KONDISI BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <label>&nbsp;</label> <br>
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('unit-kerja/oldat/dashboard') }}" class="btn btn-danger">
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
                            <div class="card">
                                <div class="card-body">
                                    <div class="alert alert-secondary loading" role="alert">
                                        Sedang menyiapkan data ...
                                    </div>
                                    <table id="table-barang" class="table table-bordered text-center" style="font-size: 80%;">
                                        <thead>
                                            <tr>
                                                <th>Id Barang</th>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Kode barang</th>
                                                <th>Merk/Tipe</th>
                                                <th>Pengguna</th>
                                                <th>Tahun</th>
                                                <th>Kondisi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
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
            buttons: [{
                    text: '(+) Usulan Pengadaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/oldat/usulan/pengadaan/baru') }}";
                    }
                },
                {
                    text: '(+) Usulan Perbaikan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/oldat/usulan/perbaikan/baru') }}";
                    }
                }
            ]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');

        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, 1, 2, 5, 6, 7]
                }
            }],
            columnDefs: [{
                    targets: -1,
                    data: null,
                    defaultContent: `<a type="button" class="btn btn-primary" data-toggle="dropdown">
                            <i class="fas fa-bars"></i>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item btn btn-detail">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                        </div>`,
                },
                {
                    "bVisible": false,
                    "aTargets": [0],
                },
            ],
            order: [
                [1, 'asc']
            ],

            "bDestroy": true
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');

        setTimeout(showTable(JSON.parse(`<?php echo $googleChartData; ?>`)), 1000);
    })

    let j = 0

    for (let i = 1; i <= 2; i++) {

        $(".select2-" + i).select2({
            ajax: {
                url: `{{ url('unit-kerja/oldat/select2-dashboard/` + i + `/barang') }}`,
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
                element.id_barang,
                no++,
                element.kategori_barang,
                element.kode_barang + ' - ' + element.nup_barang,
                element.barang,
                element.pengguna_barang,
                element.tahun_perolehan,
                element.kondisi_barang
            ])
        });
        dataTable.draw()
        $('.loading').hide()
    }

    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        window.location.href = "/unit-kerja/oldat/barang/detail/" + row[0];
    })

    // =========================================================
    //                          CHART
    // =========================================================


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
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let barang = $('select[name="barang"').val()
        let kondisi = $('select[name="kondisi"').val()
        let url = ''

        $('.loading').show()
        let dataTable = $('#table-barang').DataTable()
        dataTable.clear()
        dataTable.draw()

        if (barang || kondisi) {
            url =
                '<?= url("/unit-kerja/oldat/grafik?barang='+barang+'&kondisi='+kondisi+'") ?>'
        } else {
            url = '<?= url('/unit-kerja/oldat/grafik') ?>'
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
                            element.id_barang,
                            no++,
                            element.kategori_barang,
                            element.kode_barang + ' - ' + element.nup_barang,
                            element.barang,
                            element.pengguna_barang,
                            element.tahun_perolehan,
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
