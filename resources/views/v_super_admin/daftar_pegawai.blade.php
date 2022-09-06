@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pegawai</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Pegawai</li>
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
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload" title="Upload Data Pegawai">
                        <i class="fas fa-file-upload"></i>
                    </a>
                    <a href="{{ url('super-admin/pegawai/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data pengguna ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Sub Level">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-sublevel" class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th style="width: 1%;">No</th>
                            <th style="width: 10%;">NIP</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 15%;">No. Hp</th>
                            <th style="width: 15%;">Jabatan</th>
                            <th style="width: 20%;">Tim Kerja</th>
                            <th style="width: 15%;">Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($pegawai as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->nip_pegawai }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>{{ $row->nohp_pegawai }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td>{{ $row->tim_kerja }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-edit-{{ $row->id_pegawai }}" title="Edit Informasi Pegawai">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/pegawai/proses-hapus/'. $row->id_pegawai) }}" onclick="return confirm('Hapus data pegawai ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Edit -->
                        <div class="modal fade" id="modal-edit-{{ $row->id_pegawai }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Ubah Informasi Pegawai</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('super-admin/pegawai/proses-ubah/'. $row->id_pegawai) }}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label>NIP :</label>
                                                <input type="text" class="form-control" name="nip" value="{{ $row->nip_pegawai }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Pegawai :</label>
                                                <input type="text" class="form-control" name="nama_pegawai" value="{{ $row->nama_pegawai }}">
                                            </div>
                                            <div class="form-group">
                                                <label>No. Hp :</label>
                                                <input type="text" class="form-control" name="nohp_pegawai" value="{{ $row->nohp_pegawai }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Jabatan :</label>
                                                <select name="id_jabatan" class="form-control">
                                                    <option value="">-- Pilih Jabatan --</option>
                                                    @foreach($jabatan as $dataJabatan)
                                                    <option value="{{ $dataJabatan->id_jabatan }}" <?php if ($row->jabatan_id == $dataJabatan->id_jabatan) echo "selected"; ?>>
                                                        {{ $dataJabatan->jabatan }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Tim Kerja (Jika Staff) : </label>
                                                <select name="id_tim_kerja" class="form-control">
                                                    <option value="">-- Pilih Tim Kerja --</option>
                                                    @foreach($timKerja as $dataTimKerja)
                                                    <option value="{{ $dataTimKerja->id_tim_kerja }}" <?php if ($row->tim_kerja_id == $dataTimKerja->id_tim_kerja) echo "selected"; ?>>
                                                        {{ $dataTimKerja->tim_kerja }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Unit Kerja : </label>
                                                <select name="id_unit_kerja" class="form-control">
                                                    <option value="">-- Pilih Unit Kerja --</option>
                                                    @foreach($unitKerja as $dataUnitKerja)
                                                    <option value="{{ $dataUnitKerja->id_unit_kerja }}" <?php if ($row->unit_kerja_id == $dataUnitKerja->id_unit_kerja) echo "selected"; ?>>
                                                        {{ $dataUnitKerja->unit_kerja }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Ketarangan Jabatan Pegawai :</label>
                                                <textarea name="keterangan_pegawai" class="form-control" rows="2">{{ $row->keterangan_pegawai }}</textarea>
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
                <form action="{{ url('super-admin/pegawai/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label>NIP :</label>
                        <input type="text" class="form-control" name="nip" placeholder="Nomor Induk Pegawai (NIP)">
                    </div>
                    <div class="form-group">
                        <label>Nama Pegawai :</label>
                        <input type="text" class="form-control" name="nama_pegawai" placeholder="Nama Pegawai" required>
                    </div>
                    <div class="form-group">
                        <label>No. Hp :</label>
                        <input type="text" class="form-control" name="nohp_pegawai" placeholder="Nomor HP Pegawai">
                    </div>
                    <div class="form-group">
                        <label>Jabatan :</label>
                        <select name="id_jabatan" class="form-control">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan as $dataJabatan)
                            <option value="{{ $dataJabatan->id_jabatan }}">{{ $dataJabatan->jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tim Kerja (Jika Staff) : </label>
                        <select name="id_tim_kerja" class="form-control">
                            <option value="">-- Pilih Tim Kerja --</option>
                            @foreach($timKerja as $dataTimKerja)
                            <option value="{{ $dataTimKerja->id_tim_kerja }}">{{ $dataTimKerja->tim_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unit Kerja : </label>
                        <select name="id_unit_kerja" class="form-control" required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerja as $dataUnitKerja)
                            <option value="{{ $dataUnitKerja->id_unit_kerja }}">{{ $dataUnitKerja->unit_kerja }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ketarangan Jabatan Pegawai :</label>
                        <textarea name="keterangan_pegawai" class="form-control" rows="2"></textarea>
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

<!-- Model Upload -->
<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Data Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/pegawai/upload/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Upload Data Pegawai</label>
                        <input type="file" name="upload" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="mt-2">
                            <small>Download format excel <a href="{{ asset('format/format_data_pegawai.xlsx') }}" download>disini</a></small> <br>
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
