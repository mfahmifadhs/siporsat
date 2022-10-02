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
            <div class="col-md-9 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <input type="number" class="form-control" name="tahun" placeholder="Tahun">
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}" nama="{{$item->unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">-- Pilih Tim Kerja --</option>
                                    @foreach ($timKerja as $item)
                                    <option value="{{$item->id_tim_kerja}}" nama="{{$item->tim_kerja}}">{{$item->tim_kerja}}</option>
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
                        <div id="konten-chart-google-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                        <div id="notif-konten-chart"></div>
                        <div class="table">
                            <table id="table-barang" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Barang</th>
                                        <th>Spesifikasi Barang</th>
                                        <th>Kondisi Barang</th>
                                        <th>Unit Kerja</th>
                                        <th>Tim Kerja</th>
                                        <th>Tahun</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                    @foreach ($googleChartData1->barang as $item)
                                                <tr>    
                                                    <td>{{$no++}}</td>
                                                    <td>{{$item->kategori_barang}}</td>
                                                    <td>{{$item->spesifikasi_barang}}</td>
                                                    <td>{{$item->kondisi_barang}}</td>
                                                    <td>{{$item->unit_kerja}}</td>
                                                    <td>{{$item->tim_kerja}}</td>
                                                    <td>{{$item->tahun_perolehan}}</td>
                                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Usulan Pengadaan -->
            <div class="col-md-3 form-group">
                <div class="card card-outline card-primary" style="height: 100%;">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">DAFTAR USULAN PENGAJUAN</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($pengajuan as $dataPengajuan)
                            <div class="col-md-6 text-capitalize">
                                <a class="text-decoration-none" data-toggle="modal" data-target="#modal-info-{{ $dataPengajuan->kode_otp }}" title="Upload Data Tim Kerja">
                                    <span style="font-size: 12px;">Usuan {{ $dataPengajuan->jenis_form }}</span><br>
                                    <span style="font-size: 13px;">
                                        <label>{{ $dataPengajuan->nama_pegawai }} <br> Kode OTP : {{ $dataPengajuan->kode_otp_usulan }}</label>
                                    </span>
                                </a>
                            </div>
                            <div class="col-md-6 text-capitalize ">
                                <p class="float-right" style="font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                    @if($dataPengajuan->status_pengajuan == null && $dataPengajuan->status_proses == 'belum proses')
                                    <span class="badge badge-warning py-1">belum diproses</span>
                                    @elseif($dataPengajuan->status_pengajuan == 'terima' && $dataPengajuan->status_proses == 'proses')
                                    <span class="badge badge-success py-1">disetujui</span>
                                    <span class="badge badge-warning py-1">diproses</span>
                                    @elseif($dataPengajuan->status_pengajuan == 'terima' && $dataPengajuan->status_proses == 'selesai')
                                    <span class="badge badge-warning py-1">disetujui</span>
                                    <span class="badge badge-warning py-1">selesai</span>
                                    @elseif($dataPengajuan->status_pengajuan == 'tolak')
                                    <span class="badge badge-danger py-1">pengajuan ditolak</span>
                                    @endif <br>
                                    @if(Auth::user()->pegawai->jabatan_id == 2 && $dataPengajuan->status_pengajuan == null)
                                    <a href="{{ url('super-user/oldat/pengajuan/proses-diterima/'. $dataPengajuan->kode_otp_usulan) }}" class="btn btn-success btn-sm text-white mr-1 mt-2" onclick="return confirm('Apakah pengajuan ini diterima ?')">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <a href="{{ url('super-user/oldat/pengajuan/proses-ditolak/'. $dataPengajuan->kode_otp_usulan) }}" class="btn btn-danger btn-sm text-white mt-2" onclick="return confirm('Apakah pengajuan ini ditolak ?')">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                    @elseif(Auth::user()->pegawai->jabatan_id != 2 && $dataPengajuan->status_pengajuan == 'terima' && $dataPengajuan->status_proses == 'proses')
                                    <a href="{{ url('super-user/oldat/surat/buat-bast/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-primary btn-xs text-white mt-2 text-decoration-none">
                                        <i class="fas fa-file"></i> &nbsp; Buat BAST
                                    </a>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <!-- Modal Detail Pengajuan -->
                            <div class="modal fade" id="modal-info-{{ $dataPengajuan->kode_otp }}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-capitalize" style="font-family: times new roman;">
                                            <div class="text-uppercase text-center font-weight-bold mb-4">
                                                usulan {{ $dataPengajuan->jenis_form }} bmn alat pengolah data
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->nama_pegawai }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Jabatan Pengusul :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->jabatan }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Unit Kerja :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->unit_kerja }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Tanggal Usulan :</label></div>
                                                <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Rencana Pengguna :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->rencana_pengguna }}</div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <td>No</td>
                                                                <td style="width: 15%;">Jenis Barang</td>
                                                                <td style="width: 20%;">Merk Barang</td>
                                                                <td style="width: 40%;">Spesifikasi</td>
                                                                <td style="width: 10%;">Volume</td>
                                                                <td style="width: 15%;">Satuan</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody>
                                                            @foreach($dataPengajuan->detailPengadaan as $dataBarangPengadaan)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataBarangPengadaan->kategori_barang }}</td>
                                                                <td>{{ $dataBarangPengadaan->merk_barang }}</td>
                                                                <td>{{ $dataBarangPengadaan->spesifikasi_barang }}</td>
                                                                <td>{{ $dataBarangPengadaan->jumlah_barang }}</td>
                                                                <td>{{ $dataBarangPengadaan->satuan_barang }}</td>
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
                            @endforeach
                            {{ $pengajuan->links("pagination::bootstrap-4") }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}">
                            <i class="fas fa-arrow-circle-right"></i> Seluruh pengajuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<!-- ChartJS -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(function() {
        $("#table-barang").DataTable({
            "responsive"    : true,
            "lengthChange"  : false,
            "autoWidth"     : false,
            "info"          : false,
            "paging"        : true
        });
    });


    let chart
    let chartData = JSON.parse(`<?php echo $googleChartData; ?>`)
    let dataChart = chartData.all
    console.log(dataChart)
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(function(){
        drawChart(dataChart)
    });

    function drawChart(dataChart) {
    //   console.log(dataChart);
      chartData =  [['Kategori Barang', 'Jumlah']]
      dataChart.forEach(data => {
          chartData.push(data)
      })
    //   console.log(chartData)
      var data = google.visualization.arrayToDataTable(chartData);

      var options = {
        title: 'Total Barang',
        legend: {
            'position':'left',
            'alignment':'center'
        },
      };

      chart = new google.visualization.PieChart(document.getElementById('piechart'));

      chart.draw(data, options);
    }
    $('body').on('click', '#searchChartData', function() {
        let tahun      = $('input[name="tahun"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let tim_kerja  = $('select[name="tim_kerja"').val()
        let url = ''
        if (tahun || unit_kerja || tim_kerja) {
            url = '<?= url("/super-user/oldat/grafik?tahun='+tahun+'&unit_kerja='+unit_kerja+'&tim_kerja='+tim_kerja+'") ?>'
        } else {
            url = '<?= url("/super-user/oldat/grafik") ?>'
        }
        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable =$('#table-barang').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)
                    
                    dataTable.clear()
                    dataTable.draw()
                    let no = 1
                    // let namaUnitKerja = $('select[name="unit_kerja"]').find(':selected').attr('nama')
                    // let namaTimKerja = $('select[name="tim_kerja"]').find(':selected').attr('nama')
                    // if(namaUnitKerja == undefined){
                    //     namaUnitKerja = ''
                    // }
                    // if(namaTimKerja == undefined){
                    //     namaTimKerja = ''
                    // }
                    // console.log(namaUnitKerja)
                    // console.log(data.table)
                    data.table.forEach(element => {
                        dataTable.row.add([no++,element.kategori_barang, element.spesifikasi_barang, element.kondisi_barang, element.unit_kerja, element.tim_kerja, element.tahun_perolehan]).draw(false)
                    });
                } else {
                    dataTable.clear()
                    dataTable.draw()
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').hide();
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
                    $('#notif-konten-chart').append(html);
                }
            },
        })
    })
</script>

@endsection

@endsection
