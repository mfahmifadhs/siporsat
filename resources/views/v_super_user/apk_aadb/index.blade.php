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
                <div class="row">
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/2962/2962303.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Pengadaan</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/4659/4659844.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Servis</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/3389/3389596.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Perpanjangan STNK</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/2915/2915450.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Voucher BBM</small></h5>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold small">Masa Jatuh Tempo STNK</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-jatuh-tempo" class="table table-striped m-0">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Kendaraan</td>
                                    <td>Pengguna</td>
                                    <td>Masa Jatuh Tempo STNK</td>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach ($stnk as $jatuhTempo)
                                @if(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($jatuhTempo->mb_stnk_plat_kendaraan)) < 365) <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ $jatuhTempo->no_plat_kendaraan }} <br>
                                        <b>{{ $jatuhTempo->merk_tipe_kendaraan }}</b>
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

            <div class="col-md-12 form-group">
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-jatuh-tempo").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": false,
            pageLength : 4,
            lengthMenu: [[4, 10, 20, -1], [4, 10, 20, 'Semua']]
        })

        $("#table-kendaraan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "Usulan AADB"
            jQuery.ajax({
                url: '/super-user/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasi mengirim kode OTP')
                    resOTP = res
                }
            });
        });

        $(document).on('click', '#btnCheckOTP', function() {
            let idUsulan = $(this).data('idtarget')
            let inputOTP = $('#inputOTP' + idUsulan).val()
            console.log(idUsulan)
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            } else if (inputOTP == resOTP) {
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit' + idUsulan).prop('disabled', false)
            } else {
                alert('Kode OTP Salah')
                $('#btnSubmit' + idUsulan).prop('disabled', true)
            }
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
