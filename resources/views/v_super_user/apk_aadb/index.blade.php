@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn btn-primary">Usulan <br> Pengadaan</a>
                        <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary">Usulan <br> Servis</a>
                        <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary">Usulan <br> Perpanjangan STNK</a>
                        <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary">Usulan <br> Voucher BBM</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 form-group">
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
                            <div class="col-md-1 form-group">
                                <button id="searchChartData" class="btn btn-primary form-control">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    let chart
    let dataChart = JSON.parse(`<?php echo $googleChartData; ?>`)
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        drawChart(dataChart)
    });

    function drawChart(dataChart) {
      console.log(dataChart);
      chartData =  [['Jenis Kendaraan', 'Jumlah']]
      dataChart.forEach(data => {
          chartData.push(data)
      })
      console.log(chartData)
      var data = google.visualization.arrayToDataTable(chartData);

      var options = {
        title: 'Total Jenis Kendaraan',
        legend: {
            'position':'left',
            'alignment':'center'
        },
      };

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
                console.log(res.message);
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data)
                } else {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart').hide();
                    var html = '';
                    html += '<div class="notif-tidak-ditemukan">'
                    html += '<div class="card bg-secondary py-4">'
                    html += '<div class="card-body text-white">'
                    html += '<h5 class="mb-4 font-weight-bold text-center">'
                    html += 'Data tidak dapat ditemukan'
                    html += '</h5>'
                    html += '</div>'
                    html += '</div>'
                    html += '</div>'
                    $('#konten-statistik').append(html);

                }
               
            },

        })
    })
</script>

@endsection
@endsection
