@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pengajuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"> Dashboard OLDAT</a></li>
                    <li class="breadcrumb-item active">Dashboard OLDAT</li>
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
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <a href="#  " class="btn btn-primary" title="Download File" onclick="return confirm('Download data tim kerja ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Buat Pengajuan Usulan">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-pengajuan" class="table table-bordered text-capitalize">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Form</th>
                            <th>Jenis Form</th>
                            <th>Total Pengajuan</th>
                            <th>Rencana Pengguna</th>
                            <th>Tanggal Usulan</th>
                            <th>Status Pengajuan</th>
                            <th>Status Proses</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($formUsulan as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->id_form_usulan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Usulan Pengajuan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-md-6 form-group font-weight-bold">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/pengadaan') }}" class="btn btn-primary btn-lg text-center p-4">
                            PENGADAAN
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/perbaikan') }}" class="btn btn-primary btn-lg text-center p-4">
                            PERBAIKAN
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-workunit_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
