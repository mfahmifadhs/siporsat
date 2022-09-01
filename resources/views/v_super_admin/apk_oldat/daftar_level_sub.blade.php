@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sub Level</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Level Utama</a></li>
                    <li class="breadcrumb-item active">Sub Level</li>
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
                    <a href="{{ url('super-admin/level/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data pengguna ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Sub Level">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-sublevel" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Level</th>
                            <th>Aplikasi</th>
                            <th>Sub Level</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($subLevel as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->level }}</td>
                            <td>{{ $row->aplikasi }}</td>
                            <td>{{ $row->sub_level }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $row->id_sub_level }}" title="Edit Sub Level">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/level/sub-proses-hapus/'. $row->id_sub_level) }}" onclick="return confirm('Hapus data sub level ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="modal-edit-{{ $row->id_sub_level }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Tambah Sub Level Modal</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('super-admin/level/sub-proses-ubah/'. $row->id_sub_level) }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="level">Level :</label>
                                                <select name="id_level" class="form-control" required>
                                                    <option value="2">super user</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="aplikasi">Aplikasi :</label>
                                                <select name="id_aplikasi" class="form-control" required>
                                                    <option value="">-- Pilih Aplikasi --</option>
                                                    @foreach($aplikasi as $dataAplikasi)
                                                    <option value="{{ $dataAplikasi->id_aplikasi }}" <?php if ($row->aplikasi_id == $dataAplikasi->id_aplikasi) echo "selected"; ?>>
                                                        {{ $dataAplikasi->aplikasi }}
                                                    </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="sublevel">Sub Level : </label>
                                                <select name="sublevel" class="form-control" required>
                                                    <option value="">-- Pilih Aplikasi --</option>
                                                    @foreach($timKerja as $dataTimKerja)
                                                    <option value="{{ $dataTimKerja->id_tim_kerja }}" <?php if ($row->tim_kerja_id == $dataTimKerja->id_tim_kerja) echo "selected"; ?>>
                                                        {{ $dataAplikasi->tim_kerja }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Sub Level ?')">Submit</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
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
                <h4 class="modal-title">Tambah Sub Level Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/level/sub-proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Level :</label>
                        <select name="id_level" class="form-control" required>
                            <option value="2">super user</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="aplikasi">Aplikasi :</label>
                        <select name="id_aplikasi" class="form-control" required>
                            <option value="">-- Pilih Aplikasi --</option>
                            @foreach($aplikasi as $dataAplikasi)
                            <option value="{{ $dataAplikasi->id_aplikasi }}">{{ $dataAplikasi->aplikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sublevel">Sub Level : </label>
                        <select name="sub_menu" class="form-control" required>
                            <option value="">-- Pilih Tim Kerja --</option>
                            @foreach($timKerja as $dataTimKerja)
                            <option value="{{ $dataTimKerja->id_tim_kerja }}">{{ $dataTimKerja->tim_kerja }}</option>
                         @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Sub Level ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        $("#table-sublevel").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-workunit_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
