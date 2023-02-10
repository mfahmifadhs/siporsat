@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Alat Tulis Kantor (ATK)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('unit-kerja/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stok ATK</li>
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
                    <div class="card-header">
                        <h3 class="card-title mt-1 font-weight-bold text-uppercase">
                            Daftar dan Stok ATK {{ Auth::user()->pegawai->unitKerja->unit_kerja }}
                        </h3>
                        <!-- <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div> -->
                    </div>
                    <!-- <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-sm-3">
                                    <label>Kategori</label> <br>
                                    <select name="kategori" class="form-control text-capitalize select2-1" style="width: 100%;">
                                        <option value="">-- KATEGORI BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Jenis</label> <br>
                                    <select name="jenis" id="jenis`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- JENIS BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Nama Barang</label> <br>
                                    <select name="nama" id="barang`+ i +`" class="form-control text-capitalize select2-3" style="width: 100%;">
                                        <option value="">-- NAMA BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Merk/Tipe</label> <br>
                                    <select name="merk" id="merktipe`+ i +`" class="form-control text-capitalize select2-4" style="width: 100%;">
                                        <option value="">-- MERK/TIPE BARANG --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('unit-kerja/atk/dashboard') }}" class="btn btn-danger">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                                <table id="table-atk" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Spesifikasi</th>
                                            <th>Jumlah Pengadaan</th>
                                            <th>Jumlah Permintaan</th>
                                            <th>Sisa Stok</th>
                                            <th>Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->atk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">
                                                <form action="{{ url('unit-kerja/atk/barang/riwayat/'. Crypt::encrypt($dataAtk->spesifikasi)) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="nama_barang" value="{{ $dataAtk->nama_barang }}">
                                                    <button class="btn btn-primary">
                                                        <i class="fas fa-list"></i>
                                                    </button>
                                                </form>
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
            buttons: [{
                    text: '(+) Usulan Pengadaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/atk/usulan/pengadaan/baru') }}";
                    }
                },
                {
                    text: '(+) Usulan Distribusi',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/atk/usulan/distribusi/baru') }}";
                    }
                }
            ]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');

        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        $("#table-penggunaan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": false,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        })

        let total = 1
        let j = 0

        for (let i = 1; i <= 4; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/atk/select2-dashboard/` + i + `/distribusi') }}`,
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
        let kategori = $('select[name="kategori"').val()
        let jenis = $('select[name="jenis"').val()
        let nama = $('select[name="nama"').val()
        let merk = $('select[name="merk"').val()
        let url = ''

        if (kategori || jenis || nama || merk) {
            url = '<?= url("/unit-kerja/atk/grafik?kategori='+kategori+'&jenis='+jenis+'&nama='+nama+'&merk='+merk+'") ?>'
        } else {
            url = '<?= url("/unit-kerja/atk/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTable = $('#table-atk').DataTable()
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
                            `<b class="text-primary">` + element.id_kategori_atk + `</b> <br>` + element.kategori_atk,
                            `<b class="text-primary">` + element.id_atk + `</b> <br>` + element.merk_atk,
                            element.total_atk,
                            element.satuan,
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
