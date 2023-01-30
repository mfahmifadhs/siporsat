@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            <i class="fas fa-chart-bar"></i> &nbsp; GRAFIK USULAN ATK

                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <div id="barchart_material"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold card-title" style="font-size:medium;">
                            <i class="fas fa-table"></i> &nbsp; REKAPITULASI USULAN ATK UNIT KERJA
                        </b>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/1') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Persetujuan Kabag RT</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/2') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Sedang Diproses PPK</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/3') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Konfirmasi BAST Pengusul</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/4') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Menunggu Konfirmasi BAST Kabag RT</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/5') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Selesai BAST</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-12 col-12">
                                    <a href="{{ url('super-user/atk/usulan/status/ditolak') }}">
                                        <div class="card bg-default border border-primary">
                                            <div class="card-body">
                                                <h5>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                                <h6 class="font-weight-bold">Ditolak</h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="table-unitkerja" class="table table-striped m-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Unit Kerja</th>
                                                    <th class="text-center">Jumlah Usulan Pengadaan</th>
                                                    <th class="text-center">Jumlah Usulan Distribusi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usulanUker as $i => $row)
                                                <tr>
                                                    <td class="text-center">{{ $i+1 }}</td>
                                                    <td>{{ $row->unit_kerja }}</td>
                                                    <td class="text-center">
                                                        @php
                                                        $totalUsulan = $usulanTotal->where('unit_kerja_id', $row->id_unit_kerja)->where('jenis_form','pengadaan')->count()
                                                        @endphp
                                                        @if ($totalUsulan)
                                                        <form action="{{ url('super-user/atk/usulan/status/uker') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="jenis_form" value="pengadaan">
                                                            <input type="hidden" name="id_unit_kerja" value="{{ $row->id_unit_kerja }}">
                                                            <button type="submit" class="btn btn-dark btn-sm font-weight-bold">
                                                                {{ $totalUsulan }}
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                        $totalUsulan = $usulanTotal->where('unit_kerja_id', $row->id_unit_kerja)->where('jenis_form','distribusi')->count()
                                                        @endphp
                                                        @if ($totalUsulan)
                                                        <form action="{{ url('super-user/atk/usulan/status/uker') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="jenis_form" value="distribusi">
                                                            <input type="hidden" name="id_unit_kerja" value="{{ $row->id_unit_kerja }}">
                                                            <button type="submit" class="btn btn-dark btn-sm font-weight-bold">
                                                                {{ $totalUsulan }}
                                                            </button>
                                                        </form>
                                                        @endif
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
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <b class="font-weight-bold">
                            <i class="fas fa-table"></i> &nbsp; TABEL DAFTAR ATK
                        </b>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="card-body border border-default">
                                <table id="table-atk" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Jenis</th>
                                            <th>Barang</th>
                                            <th>Spesifikasi</th>
                                            <th class="text-center">Pengadaan</th>
                                            <th class="text-center">Distribusi</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Riwayat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $dataChartAtk = json_decode($dataChartAtk) @endphp
                                        @foreach ($dataChartAtk->atk as $dataAtk)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }}</td>
                                            <td>{{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ (int) $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">{{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_pemakaian.' '.$dataAtk->satuan }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('super-user/atk/barang/riwayat-semua/'. Crypt::encrypt($dataAtk->spesifikasi)) }}" class="btn btn-primary">
                                                    <i class="fas fa-list"></i>
                                                </a>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true

        })

        $("#table-unitkerja").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": false,
            "paging": false,
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [11, 25, 50, -1],
                [11, 25, 50, "Semua"]
            ],
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        let chartUsulanAtk = JSON.parse(`<?php echo $usulanChartAtk; ?>`)
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(chartUsulanAtk);

            var options = {
                height: 300,
                chart: {
                    subtitle: 'Rekapitulasi Total Usulan',
                },
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    })
</script>

@endsection

@endsection
