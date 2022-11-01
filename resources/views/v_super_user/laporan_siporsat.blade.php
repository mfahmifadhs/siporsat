@extends('v_super_user.layout.app')

@section('content')


<!-- content-wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Laporan Pengajuan Usulan</small></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card">
                            <div class="card-body">
                                <table id="table" class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <td colspan="5">{{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">Total Usulan Penyediaan Barang / Jasa </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2" class="py-5">No</td>
                                            <td rowspan="2" class="py-5">Kategori Pengadaan / Penyediaan</td>
                                            <td colspan="3">Status </td>
                                        </tr>
                                        <tr>
                                            <td>Ditolak</td>
                                            <td>Sedang Proses Pengadaan</td>
                                            <td>Sudah BAST (Selesai)</td>
                                        </tr>
                                    </thead>
                                    @php $no = 1; @endphp
                                    <tbody class="text-uppercase">
                                        @foreach($reportOldat as $report)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $report['usulan'] }} OLDAT</td>
                                            <td>{{ $report['ditolak'] }}</td>
                                            <td>{{ $report['proses'] }}</td>
                                            <td>{{ $report['selesai'] }}</td>
                                        </tr>
                                        @endforeach
                                        @foreach($reportAadb as $report)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $report['usulan'] }}</td>
                                            <td>{{ $report['ditolak'] }}</td>
                                            <td>{{ $report['proses'] }}</td>
                                            <td>{{ $report['selesai'] }}</td>
                                        </tr>
                                        @endforeach
                                        @foreach($reportAtk as $report)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $report['usulan'] }} ATK</td>
                                            <td>{{ $report['ditolak'] }}</td>
                                            <td>{{ $report['proses'] }}</td>
                                            <td>{{ $report['selesai'] }}</td>
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
    <!-- / Main Content -->
</div>
<!-- /.content-wrapper -->
@section('js')
<script>
    $(function() {
        $("#table").DataTable({
            "responsive"   : true,
            "lengthChange" : false,
            "searching"    : false,
            "info"         : false,
            "paging"       : false,
            "autoWidth"    : false,
            buttons: [
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-success mr-2 rounded',
                    title: 'Oktober',
                    messageTop: 'Total Usulan Penyediaan Barang / Jasa '
                }
            ]
        }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
