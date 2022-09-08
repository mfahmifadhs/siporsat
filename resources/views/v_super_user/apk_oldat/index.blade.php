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

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="card text-center">
                    <div class="card-header">
                        <div class="row">
                            <div class="colmd-3">
                                <div class="card-tools m-r-1">
                                    <input type="number" class="form-control" name="tahun" placeholder="Tahun" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-tools m-r-1">
                                    <select id="" class="form-control" name="unit_kerja">
                                        <option value="">-- Pilih Unit Kerja --</option>
                                        @foreach ($unitKerja as $item)
                                        <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-tools m-r-1">
                                    <select id="" class="form-control" name="tim_kerja">
                                        <option value="">-- Pilih  Tim Kerja --</option>
                                        @foreach ($timKerja as $item)
                                        <option value="{{$item->id_tim_kerja}}">{{$item->tim_kerja}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="card-tools">
                                    <button id="searchChartData" class="btn btn-primary form-control">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart">
                            <canvas id="pie-chart" width="800" height="450"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<script>
    let ChartData = JSON.parse(`<?php echo $chartData; ?>`)
    let chart
    loadChart(ChartData)


    function loadChart(ChartData){
        chart = new Chart(document.getElementById("pie-chart"), {
        type: 'pie',
        data: {
          labels: ChartData.label,
          datasets: [{
            label: "qts",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: ChartData.data
          }]
        },
        options: {

          legend: {
              position: "left",
              align: "center"
          },
          maintainAspectRatio : false,
          responsive : true,
          title: {
            display: true,
            text: 'Total Pengadaan Barang'
          }
        }

    });

    }
$('body').on('click','#searchChartData',function () {
    let tahun = $('input[name="tahun"').val()
    let unit_kerja = $('select[name="unit_kerja"').val()
    let tim_kerja = $('select[name="tim_kerja"').val()
    let url =''
    if(tahun || unit_kerja || tim_kerja){
        url = '<?= url("/super-user/oldat/grafik?tahun='+tahun+'&unit_kerja='+unit_kerja+'&tim_kerja='+tim_kerja+'") ?>'
    }else{
        url = '<?= url("/super-user/oldat/grafik") ?>'
    }

    jQuery.ajax({
        url : url,
        type : "GET",
        success:function(res){
            console.log(res.message);
            if(res.message == 'success'){
                $('.notif-tidak-ditemukan').remove();
                $('#konten-chart').show();
                let data = JSON.parse(res.data)
                chart.destroy()
                loadChart(data)
            }else {
                $('.notif-tidak-ditemukan').remove();
                $('#konten-chart').hide();
                var html = '';
                        html +='<div class="notif-tidak-ditemukan">'
                        html +='<div class="card bg-secondary py-4">'
                        html +='<div class="card-body text-white">'
                        html +='<h5 class="mb-4 font-weight-bold text-center">'
                        html +='Data tidak dapat ditemukan'
                        html +='</h5>'
                        html +='</div>'
                        html +='</div>'
                        html +='</div>'
                        $('#konten-statistik').append(html);

            }
        },

    })
})
</script>
@endsection
