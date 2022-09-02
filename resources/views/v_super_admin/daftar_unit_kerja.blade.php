@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Unit Kerja</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Unit Utama</a></li>
                    <li class="breadcrumb-item active">Unit Kerja</li>
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
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload" title="Upload Data Tim Kerja">
                        <i class="fas fa-file-upload"></i>
                    </a>
                    <a href="{{ url('super-admin/unit-kerja/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data unit kerja ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Unit Kerja">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-tim-kerja" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit Utama</th>
                            <th>Unit Kerja</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($unitKerja as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->unit_utama }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $row->id_unit_kerja }}" title="Edit Unit Kerja">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/unit-kerja/proses-hapus/'. $row->id_unit_kerja) }}" onclick="return confirm('Hapus data unit kerja ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Upload -->
<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Data Unit Kerja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/unit-kerja/upload/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Upload Data Unit Kerja</label>
                        <input type="file" name="upload" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="mt-2">
                            <small>Download format excel <a href="{{ asset('format/format_data_unit_kerja.xlsx') }}" download>disini</a></small> <br>
                            <small>Format file harus (.xlsx)</small>
                        </p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Upload Data ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        $("#table-tim-kerja").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-workunit_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
