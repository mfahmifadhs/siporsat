@extends('v_super_user.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Master Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="col-md-12 form-group">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @elseif ($message = Session::get('failed'))
            <div class="alert alert-danger">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
        </div>
        <div class="alert alert-secondary loading" role="alert">
            Sedang menyiapkan data ...
        </div>
        <br>
        <div class="card card-primary card-outline table-container">
            <div class="card-header">
                <b class="font-weight-bold text-primary card-title" style="font-size:medium;">
                    <i class="fas fa-table"></i> TABEL DAFTAR OLDAT & MEUBELAIR
                </b>
            </div>
            <div class="card-header">
                <label>Total Barang Per-Unit Kerja</label>
                <div class="form-group row">
                    @foreach ($rekapBarang as $row)
                    <div class="col-md-3">{{ $row->unit_kerja }}</div>:
                    <div class="col-md-2">{{ $row->total_barang }} barang</div>
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>id Barang</th>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merk/Tipe</th>
                            <th>Nilai Perolehan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>



@section('js')
<script>
    $(document).ready(function() {
        $(function() {
            var currentdate = new Date();
            var datetime = "Tanggal: " + currentdate.getDate() + "/" +
                (currentdate.getMonth() + 1) + "/" +
                currentdate.getFullYear() + " \n Pukul: " +
                currentdate.getHours() + ":" +
                currentdate.getMinutes() + ":" +
                currentdate.getSeconds()
            $("#table-barang").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
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
                        "aTargets": [0, 2, 3]
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                buttons: [{
                        extend: 'pdf',
                        text: ' PDF',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Daftar Barang Olah Data BMN & Meubelair',
                        exportOptions: {
                            columns: [1, 11, 4, 5, 8, 9]
                            // columns: [0, 3, 4, 5, 6, 7, 8, 9]
                        },
                        messageTop: datetime
                    },
                    {
                        extend: 'excel',
                        text: ' Excel',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Daftar Barang Olah Data BMN & Meubelair',
                        exportOptions: {
                            columns: [1, 2, 3, 5, 6, 7, 8, 9, 10, 11]
                        },
                        messageTop: datetime
                    }
                ],
                "bDestroy": true
            }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
        });
        // $('.table-container').hide()
        setTimeout(showTable, 1000);
    })

    function showTable() {
        let dataTable = $('#table-barang').DataTable()
        let dataBarang = JSON.parse(`<?php echo $barang; ?>`)

        dataTable.clear()
        // dataTable.draw()
        let no = 1
        dataBarang.forEach(element => {
            dataTable.row.add([
                element.id_barang,
                no,
                element.kode_barang,
                element.nup_barang,
                element.kode_barang + `.` + element.nup_barang,
                element.kategori_barang,
                element.barang?.toString() || '',
                `Rp ` + String(element.nilai_perolehan).replace(/(.)(?=(\d{3})+$)/g, '$1,'),
                element.tahun_perolehan,
                element.kondisi_barang,
                element.pengguna_barang,
                element.unit_kerja
            ])
            no++
        });
        dataTable.draw()
        $('.loading').hide()
    }

    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        window.location.href = "/super-user/oldat/barang/detail/" + row[0];
    })



        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(chartData);
            var options = {
                height: 300,
                chart: {
                    subtitle: 'Rekapitulasi Total Usulan',
                },
            };
            var chart = new google.charts.Bar(document.getElementById('reportChart'));
            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
</script>
@endsection

@endsection
