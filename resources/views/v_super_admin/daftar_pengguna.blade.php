@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pengguna</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Pengguna</li>
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
                    <a href="{{ url('super-admin/pengguna/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data pengguna ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Sub Level">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-pengguna" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Level</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Unit Kerja</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($pengguna as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->level }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>{{ $row->keterangan_pegawai }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->password_teks }}</td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $row->id }}" title="Edit Sub Level">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/pengguna/proses-hapus/'. $row->id) }}" onclick="return confirm('Hapus data pengguna ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="modal-edit-{{ $row->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Ubah Informasi Pengguna</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('super-admin/pengguna/proses-ubah/'. $row->id) }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="level">Level :</label>
                                                <select name="id_level" class="form-control" required>
                                                    <option value="">-- Pilih Level --</option>
                                                    @foreach($level as $dataLevel)
                                                    <option value="{{ $dataLevel->id_level }}" <?php if ($row->level_id == $dataLevel->id_level) echo "selected"; ?>>
                                                        {{ $dataLevel->level }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="aplikasi">Unit Kerja :</label>
                                                <select name="id_unit_kerja" class="form-control" required>
                                                    <option value="">-- Pilih Unit Kerja --</option>
                                                    @foreach($unitKerja as $dataUnitKerja)
                                                    <option value="{{ $dataUnitKerja->id_unit_kerja }}" <?php if ($row->unit_kerja_id == $dataUnitKerja->id_unit_kerja) echo "selected"; ?>>
                                                        {{ $dataUnitKerja->unit_kerja }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nama">Pegawai : </label>
                                                <select name="id_pegawai" class="form-control text-capitalize" required>
                                                    <option value="">-- Pilih Pegawai --</option>
                                                    @foreach($pegawai as $dataPegawai)
                                                    <option value="{{ $dataPegawai->id_pegawai }}" <?php if ($row->pegawai_id == $dataPegawai->id_pegawai) echo "selected"; ?>>
                                                        {{ $dataPegawai->nama_pegawai }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="username">Username : </label>
                                                <input type="text" name="username" class="form-control" value="{{ $row->username }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password : </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <a type="button" onclick="passwordEdit()"><i class="fas fa-eye"></i></a>
                                                        </span>
                                                    </div>
                                                    <input type="password" id="edit-password" name="password" class="form-control" minlength="8" value="{{ $row->password_teks }}">
                                                </div>
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
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/pengguna/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Level :</label>
                        <select name="id_level" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach($level as $dataLevel)
                            <option value="{{ $dataLevel->id_level }}">{{ $dataLevel->level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unitkerja">Unit Kerja :</label>
                        <select name="id_unit_kerja" class="form-control" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerja as $dataUnitKerja)
                            <option value="{{ $dataUnitKerja->id_unit_kerja }}">{{ $dataUnitKerja->unit_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unitkerja">Pegawai :</label>
                        <select name="id_pegawai" class="form-control text-capitalize" required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach($pegawai as $dataPegawai)
                            <option value="{{ $dataPegawai->id_pegawai }}">{{ $dataPegawai->nama_pegawai }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="username">Username : </label>
                        <input type="text" name="username" class="form-control" placeholder="Masukan Username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password : </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <a type="button" onclick="passwordAdd()"><i class="fas fa-eye"></i></a>
                                </span>
                            </div>
                            <input type="password" id="add-password" name="password" class="form-control" minlength="8" placeholder="Minimal 8 Karakter" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Pengguna Baru ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        $("#table-pengguna").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-workunit_wrapper .col-md-6:eq(0)');
    });

    function passwordEdit() {
        var x = document.getElementById("edit-password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function passwordAdd() {
        var x = document.getElementById("add-password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
@endsection

@endsection
