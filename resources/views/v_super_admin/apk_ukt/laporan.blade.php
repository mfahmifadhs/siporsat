@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Kerumah Tanggaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/ukt/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
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
        <div class="card card-primary card-outline">
            <div class="card-header text-capitalize ">
                <h3 class="card-title">Laporan Usulan Urusan Kerumahtanggaan</h3>
            </div>
            <div class="card-header">
                <canvas id="uktChart" style="height: 500px;"></canvas>
            </div>
            <div class="card-body">
                <table id="table-show" class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit Kerja</th>
                            <th>Total Usulan</th>
                        </tr>
                    </thead>
                    @php $no = 1; $totalUsulan = 0; @endphp
                    <tbody>
                        @foreach ($ukt as $row)
                        @php
                        $totalUsulan += $row->total_usulan;
                        @endphp
                        <tr>
                            <td style="width: 0%;">{{ $no++ }}</td>
                            <td style="width: 70%;" class="text-left">{{ $row->unit_kerja }}</td>
                            <td style="width: 30%;">{{ $row->total_usulan }} usulan</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">Total</td>
                            <td>{{ $totalUsulan  }} usulan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    // Chart
    var uktData = {!! json_encode($ukt) !!};
    var labels = uktData.map(data => data.unit_kerja);
    var values = uktData.map(data => data.total_usulan);

    var ctx = document.getElementById('uktChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                ],
                hoverBackgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    align: 'end',
                    anchor: 'end',
                    backgroundColor: function(context) {
                        return context.dataset.backgroundColor;
                    },
                    borderRadius: 4,
                    color: 'black',
                    font: {
                        weight: 'bold'
                    },
                    formatter: Math.round
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Unit Kerja'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Total Usulan'
                    },
                    beginAtZero: true
                }
            },
            maintainAspectRatio: false,
        }
    });
    // Table
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-show").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": true,
            "paging": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-download btn btn-info text-bold mr-2 rounded',
                    title: 'Show',
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-download btn btn-info text-bold mr-2 rounded',
                    title: 'Show',
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-show_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection

@endsection
