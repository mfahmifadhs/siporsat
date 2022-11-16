@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">PEMELIHARAAN ALAT ANGKUTAN DARATA BERMOTOR (AADB)</h4>
            </div>
        </div>
    </div>
</div>

<section class="content text-capitalize">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 col-12">
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
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-tools fa-2x"></i> <br>
                            Usulan <br> Servis </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-id-card-alt fa-2x"></i> <br>
                            Usulan <br> Perpanjang STNK </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-gas-pump fa-2x"></i> <br>
                            Usulan <br> Voucher BBM </a>
                    </div>
                </div>
                <div class="form-group row">
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
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',2)->where('status_proses_id', 1)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Sedang Diproses</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',2)->where('status_proses_id', 2)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',2)->where('status_proses_id', 4)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Selesai</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',2)->where('status_proses_id', 5)->count() }}</div>
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
                                <h3 class="card-title"><small class="font-weight-bold">Usulan Perpanjangan STNK</small></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-default btn-xs" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 form-group small">Menunggu Persetujuan</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',3)->where('status_proses_id', 1)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Sedang Diproses</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',3)->where('status_proses_id', 2)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',3)->where('status_proses_id', 4)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Selesai</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',3)->where('status_proses_id', 5)->count() }}</div>
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
                                <h3 class="card-title"><small class="font-weight-bold">Usulan Voucher BBM</small></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-default btn-xs" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 form-group small">Menunggu Persetujuan</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',4)->where('status_proses_id', 1)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Sedang Diproses</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',4)->where('status_proses_id', 2)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',4)->where('status_proses_id', 4)->count() }}</div>
                                    <div class="col-md-12">
                                        <hr style="border: 1px solid grey;margin-top:-1vh;">
                                    </div>
                                    <div class="col-md-8 form-group small">Selesai</div>
                                    <div class="col-md-4 form-group small text-right">{{ $usulan->where('jenis_form',4)->where('status_proses_id', 5)->count() }}</div>
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
                            <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">
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
                                        @if ($dataUsulan->jenis_form == 1)
                                        @foreach($dataUsulan->usulanKendaraan as $dataPengadaan)
                                        Kendaraan {{ $dataPengadaan->jenis_aadb }}<br>
                                        {{ ucfirst(strtolower($dataPengadaan->jenis_kendaraan)) }} <br>
                                        {{ $dataPengadaan->merk_tipe_kendaraan.' '.$dataPengadaan->tahun_kendaraan }} <br>
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 2)
                                        @foreach($dataUsulan->usulanServis as $dataServis)
                                        {{ $dataServis->merk_tipe_kendaraan.' '.$dataServis->tahun_kendaraan }} <br>
                                        Jatuh Tempo Servis : <br>{{ $dataServis->jatuh_tempo_servis }} KM<br>
                                        Jatuh Tempo Ganti Oli : <br>{{ $dataServis->jatuh_tempo_ganti_oli }} KM<br>
                                        Tanggal Terakhir Servis : <br>{{ $dataServis->tgl_servis_terakhir }} <br>
                                        Tanggal Terakhir Ganti Oli : <br>{{ $dataServis->tgl_ganti_oli_terakhir }} <br>
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 3)
                                        @foreach($dataUsulan->usulanSTNK as $dataStnk)
                                        {{ $dataStnk->merk_tipe_kendaraan.' '.$dataStnk->tahun_kendaraan }} <br>
                                        Masa Berlaku STNK lama : <br>{{ $dataStnk->mb_stnk_lama }} <br>
                                        Masa Berlaku STNK Baru : <br>{{ $dataStnk->mb_stnk_baru }} <br>
                                        Biaya Perpanjangan : <br>Rp {{ number_format($dataStnk->biaya_perpanjangan, 0, ',', '.') }} <br>
                                        @if($dataStnk->bukti_pembayaran != null)
                                        Bukti Pembayaran : <br><a href="{{ asset('gambar/kendaraan/stnk/'. $dataStnk->bukti_pembayaran) }}" class="font-weight-bold" download>Download</a>
                                        @endif
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 4)
                                        @foreach($dataUsulan->usulanVoucher as $dataVoucher)
                                        {{ $dataVoucher->merk_tipe_kendaraan.' '.$dataVoucher->tahun_kendaraan }} <br>
                                        Bulan Pengadaan : {{ \Carbon\carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMM Y') }} <br>
                                        Voucher 25 : <br>{{ $dataVoucher->voucher_25 }} <br>
                                        Voucher 50 : <br>{{ $dataVoucher->voucher_50 }} <br>
                                        Voucher 100 : <br>{{ $dataVoucher->voucher_100 }} <br>
                                        Total Biaya : <br>Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }} <br>
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
                                        <span class="badge badge-sm badge-pill badge-warning">sedang diproses <br> petugas gudang</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center pt-4">
                                        <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Usulan
                                            </a>
                                            @else
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/aadb/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}"
                                                onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                            @if ($dataUsulan->status_proses_id == 5)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/bast-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Bast
                                            </a>
                                            @endif
                                        </div>
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
</section>

<section class="content text-capitalize">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12 form-group">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mt-1 font-weight-bold small">Filter AADB</h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>Jenis AADB</label> <br>
                                <select id="" class="form-control" name="jenis_aadb">
                                    <option value="">-- JENIS AADB --</option>
                                    <option value="bmn">BMN</option>
                                    <option value="sewa">SEWA</option>
                                </select>
                            </div>
                            <!-- <div class="col-sm-4">
                                <label>Unit Kerja</label> <br>
                                <select name="unit_kerja" id="unitkerja`+ i +`" class="form-control text-capitalize select2-1">
                                    <option value="">-- UNIT KERJA --</option>
                                </select>
                            </div> -->
                            <div class="col-sm-6">
                                <label>Nama Kendaraan</label> <br>
                                <select name="jenis_kendaraan" id="kendaraan`+ i +`" class="form-control text-capitalize select2-2">
                                    <option value="">-- NAMA KENDARAAN --</option>
                                </select>
                            </div>
                            <div class="col-sm-12 mt-2 text-right">
                                <button id="searchChartData" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ url('unit-kerja/aadb/dashboard') }}" class="btn btn-danger">
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
                        <table id="table-aadb" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Merk/Tipe Barang</th>
                                    <th>Pengguna</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                @foreach ($googleChartData1->kendaraan as $dataAadb)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <b class="text-primary">{{ $dataAadb->kode_barang.'.'.$dataAadb->nup_barang }}</b> <br>
                                        {{ $dataAadb->merk_tipe_kendaraan.' '.$dataAadb->tahun_kendaraan }} <br>
                                        {{ $dataAadb->no_plat_kendaraan }} <br>
                                        {{ $dataAadb->jenis_aadb }}
                                    </td>
                                    <td>
                                        No. BPKB : {{ $dataAadb->no_bpkb }} <br>
                                        No. Rangka : {{ $dataAadb->no_rangka }} <br>
                                        No. Mesin : {{ $dataAadb->no_mesin }} <br>
                                        Masa Berlaku STNK : <br>
                                        @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)) < 365 && $dataAadb->mb_stnk_plat_kendaraan != null)
                                            <span class="badge badge-sm badge-pill badge-danger">
                                                {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                            </span>
                                            @elseif ($dataAadb->mb_stnk_plat_kendaraan != null)
                                            <span class="badge badge-sm badge-pill badge-success">
                                                {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                            </span>
                                            @endif<br>
                                    </td>
                                    <td>
                                        Unit Kerja : {{ $dataAadb->unit_kerja }} <br>
                                        Pengguna : {{ $dataAadb->pengguna }} <br>
                                        Jabatan : {{ $dataAadb->jabatan }} <br>
                                        Pengemudi : {{ $dataAadb->pengemudi }}
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
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
        })

        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
        })

        let j = 0

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
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
        console.log(dataChart)
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
        let jenis_aadb      = $('select[name="jenis_aadb"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || jenis_kendaraan) {
            url = '<?= url("/unit-kerja/aadb/grafik?jenis_aadb='+jenis_aadb+'&jenis_kendaraan='+jenis_kendaraan+'") ?>'
        } else {
            url = '<?= url("/unit-kerja/aadb/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-aadb').DataTable()
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
                            '<b class="text-primary">'+ element.kode_barang +'.'+ element.nup_barang + '</b>' +
                            '<br>'+ element.merk_tipe_kendaraan +' '+ element.tahun_kendaraan +
                            '<br>'+ element.no_plat_kendaraan +
                            '<br>'+ element.jenis_aadb,
                            'No. BPKB :' + element.no_bpkb +
                            '<br> No. Rangka :' + element.no_rangka +
                            '<br> No. Mesin :' + element.no_mesin +
                            '<br> Masa Berlaku STNK :' + element.mb_stnk_plat_kendaraan,
                            'Unit Kerja :' + element.unit_kerja +
                            '<br> Pengguna :' + element.pengguna +
                            '<br> Jabatan :' + element.jabatan +
                            '<br> Pengemudi :' + element.pengemudi,
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
