@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Dashboard Pemeliharaan Rumah Dinas Negara</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
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
            <div class="col-3">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">MASA JATUH TEMPO SIP</h4>
                    </div>
                    <div class="card-body">
                        @foreach($rumahDinas as $penghuni)
                        @if(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($penghuni->masa_berakhir_sip)) < 365) <div class="row form-group">
                            <div class="col-md-8">
                                <h6>{{ $penghuni->nama_pegawai }}</h6> <br>
                                <label class="small">Rumah {{ $penghuni->lokasi_kota }}</label> <br>
                                <span class="small">{{ $penghuni->alamat_rumah }}</span>
                            </div>
                            <div class="col-md-4 small">
                                <label>Masa Berakhir SIP</label> <br>
                                <span class="badge badge-sm badge-pill badge-danger">
                                    {{ \Carbon\Carbon::parse($penghuni->masa_berakhir_sip)->isoFormat('DD MMMM Y') }}
                                </span>
                            </div>
                    </div>
                    <hr style="border: 0.5px solid grey;">
                    @endif
                    @endforeach
                    {{ $rumahDinas->links("pagination::bootstrap-4") }}
                </div>
            </div>
        </div>
        <div class="col-md-9 form-group">
            <div class="row">
                <div class="col-md-12 form-group">
                    <div class="card card-outline card-primary text-center" style="height: 100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="golongan_rumah">
                                        <option value="">Semua Golongan Rumah</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="lokasi_kota">
                                        <option value="">Semua Lokasi Kota</option>
                                        @foreach ($lokasiKota as $dataLokasi)
                                        <option value="{{$dataLokasi->lokasi_kota}}">{{$dataLokasi->lokasi_kota}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="kondisi_rumah">
                                        <option value="">Semua Kondisi</option>
                                        <option value="1">Baik</option>
                                        <option value="2">Rusak Ringan</option>
                                        <option value="3">Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mr-2">
                                    <div class="row">
                                        <button id="searchChartData" class="btn btn-primary ml-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('super-user/rdn/dashboard') }}" class="btn btn-danger ml-2">
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
                            <div id="notif-konten-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group">
                    <div class="card">
                        <div class="card-body">
                            <div class="table">
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


    <!-- <div class="row">
            <div class="col-12">
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

            <div class="col-md-9 form-group">
                <div class="col-md-12">
                    <div class="card card-outline card-primary text-center" style="height: 100%;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="golongan_rumah">
                                        <option value="">Semua Golongan Rumah</option>
                                        <option value="I">I</option>
                                        <option value="II">II</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="lokasi_kota">
                                        <option value="">Semua Lokasi Kota</option>
                                        @foreach ($lokasiKota as $dataLokasi)
                                        <option value="{{$dataLokasi->lokasi_kota}}">{{$dataLokasi->lokasi_kota}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <select id="" class="form-control" name="kondisi_rumah">
                                        <option value="">Semua Kondisi</option>
                                        <option value="1">Baik</option>
                                        <option value="2">Rusak Ringan</option>
                                        <option value="3">Rusak Berat</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mr-2">
                                    <div class="row">
                                        <button id="searchChartData" class="btn btn-primary ml-2">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('super-user/rdn/dashboard') }}" class="btn btn-danger ml-2">
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
                            <div id="notif-konten-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table">
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
        </div> -->

    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-rumah").DataTable({
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
                    alert('Berhasi mengirim kode OTP')
                    resOTP = res
                }
            });
        });

        $(document).on('click', '#btnCheckOTP', function() {
            let idUsulan = $(this).data('idtarget')
            let inputOTP = $('#inputOTP' + idUsulan).val()
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            } else if (inputOTP == resOTP) {
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit').prop('disabled', false)
            } else {
                alert('Kode OTP Salah')
                $('#btnSubmit').prop('disabled', true)
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
            url = '<?= url("/super-user/rdn/grafik?golongan_rumah='+golongan_rumah+'&lokasi_kota='+lokasi_kota+'&kondisi_rumah='+kondisi_rumah+'") ?>'
        } else {
            url = '<?= url("/super-user/rdn/grafik") ?>'
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
                            `<label>` + element.nama_pegawai + `</label>`

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
