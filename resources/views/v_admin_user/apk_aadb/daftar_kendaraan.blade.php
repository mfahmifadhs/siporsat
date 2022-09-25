@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Daftar AADB</li>
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
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload" title="Upload Data Kendaraan">
                        <i class="fas fa-file-upload"></i>
                    </a>
                    <a href="{{ url('admin-user/aadb/kendaraan/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data kendaraan ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Kategori Kendaraan">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-aadb" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis AADB</th>
                            <th>Kode Barang</th>
                            <th>Jenis Kendaraan</th>
                            <th>Merk / Tipe</th>
                            <th>No. Plat</th>
                            <th>Masa Berlaku STNK</th>
                            <th>No. Plat RHS</th>
                            <th>Masa Berlaku STNK</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Jabatan</th>
                            <th>Pengemudi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($kendaraan as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->jenis_aadb }}</td>
                            <td>{{ $row->kode_barang }}</td>
                            <td>{{ $row->jenis_kendaraan }}</td>
                            <td>{{ $row->merk_kendaraan.' '.$row->tipe_kendaraan }}</td>
                            <td>{{ $row->no_plat_kendaraan }}</td>
                            <td>{{ $row->mb_stnk_plat_kendaraan }}</td>
                            <td>{{ $row->no_plat_rhs }}</td>
                            <td>{{ $row->mb_stnk_plat_rhs }}</td>
                            <td>{{ $row->kondisi_kendaraan }}</td>
                            <td>{{ $row->pengguna }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td>{{ $row->pengemudi }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('super-admin/aadb/kendaraan/detail/'. $row->id_kendaraan) }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/aadb/kategori-kendaraan/proses-hapus/'. $row->id_kendaraan) }}" onclick="return confirm('Hapus data kategori kendaraan ?')">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/aadb/kategori-kendaraan/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Kategori kendaraan :</label>
                        <input type="text" class="form-control" name="kategori_kendaraan" placeholder="Tambah Kategori kendaraan" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Kategori kendaraan ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Data Kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin-user/aadb/kendaraan/upload/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Upload Data kendaraan</label>
                        <input type="file" name="upload" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="mt-2">
                            <small>Download format excel <a href="{{ asset('format/format_data_kendaraan.xlsx') }}" download>disini</a></small> <br>
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
        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [[10, 25, 50, "All", -1], [10, 25, 50, "All"]]
        }).buttons().container().appendTo('#table-aadb .col-md-6:eq(0)');;
    });
</script>
@endsection

@endsection