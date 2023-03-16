@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Referensi ATK</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/dashboard/data') }}"> Gudang ATK </a></li>
                    <li class="breadcrumb-item active">Refensi ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 form-group">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12 form-group">
                <div class="row">
                    <div class="col-5 col-sm-2">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#tabs-kategori" role="tab" aria-controls="vert-tabs-home" aria-selected="false">
                                Kategori Barang
                            </a>
                            <a class="nav-link active" id="vert-tabs-profile-tab" data-toggle="pill" href="#tabs-atk" role="tab" aria-controls="vert-tabs-profile" aria-selected="true">
                                Referensi Barang
                            </a>
                        </div>
                    </div>
                    <div class="col-7 col-sm-10">
                        <div class="tab-content" id="vert-tabs-tabContent">
                            <!-- Kategori -->
                            <div class="tab-pane fade" id="tabs-kategori" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <b class="font-weight-bold text-primary card-title pt-2" style="font-size:large;">
                                            <i class="fas fa-table"></i> DAFTAR KATEGORI BARANG ATK
                                        </b>
                                        <div class="card-tools">
                                            <a class="btn btn-default" type="button" data-toggle="modal" data-target="#modal-add-kategori">
                                                <i class="fas fa-plus-circle"></i> Tambah Kategori
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <table id="table-kategori" class="table table-bordered table-striped fa-1x">
                                                <thead class="bg-primary font-weight-bold">
                                                    <tr>
                                                        <th style="width: 0%;" class="text-center">No</th>
                                                        <th style="width: 12;">Kode Kategori</th>
                                                        <th>Deskripsi</th>
                                                        <th style="width: 10%;">Satuan</th>
                                                        <th style="width: 20%;">Keterangan</th>
                                                        <th style="width: 0%;" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($kategori as $i => $kategori)
                                                    <tr>
                                                        <td class="text-center">{{ $i + 1 }}</td>
                                                        <td>{{ $kategori->id_kategori_atk }}</td>
                                                        <td>{{ $kategori->deskripsi_kategori }}</td>
                                                        <td>{{ $kategori->satuan_kategori }}</td>
                                                        <td>{{ $kategori->keterangan_kategori }}</td>
                                                        <td class="text-center">
                                                            <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                                                <i class="fas fa-bars"></i>
                                                            </a>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#info-kategori-{{ $kategori->id_kategori_atk }}">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="info-kategori-{{ $kategori->id_kategori_atk }}">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-primary">
                                                                    <h6 class="modal-title font-weight-bold">{{ $kategori->id_kategori_atk.' - '.$kategori->deskripsi_kategori }}</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">×</span>
                                                                        </button>
                                                                </div>
                                                                <form action="{{ url('admin-user/atk/gudang/update-kategori/'. $kategori->id_kategori_atk) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body text-capitalize">
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label">Kode Kategori</label>
                                                                            <input class="col-md-10 form-control" name="id_kategori_atk" value="{{ $kategori->id_kategori_atk }}" readonly>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label">Deskripsi</label>
                                                                            <input class="col-md-10 form-control" name="deskripsi_kategori" value="{{ $kategori->deskripsi_kategori }}">
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label">Satuan</label>
                                                                            <input class="col-md-10 form-control" name="satuan_kategori" value="{{ $kategori->satuan_kategori }}">
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label class="col-md-2 col-form-label">Keterangan</label>
                                                                            <textarea class="col-md-10 form-control" name="keterangan_kategori">{{ $kategori->keterangan_kategori }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer text-right">
                                                                        <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Simpan Perubahan?')">
                                                                            <i class="fas fa-save"></i> SIMPAN
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Atk -->
                            <div class="tab-pane fade active show" id="tabs-atk" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <b class="font-weight-bold text-primary card-title pt-2" style="font-size:large;">
                                            <i class="fas fa-table"></i> DAFTAR REFERENSI BARANG ATK
                                        </b>
                                        <div class="card-tools">
                                            <a class="btn btn-default" type="button" data-toggle="modal" data-target="#modal-add-atk">
                                                <i class="fas fa-plus-circle"></i> Tambah Referensi
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table id="table-atk" class="table table-bordered table-striped fa-1x">
                                            <thead class="bg-primary font-weight-bold">
                                                <tr>
                                                    <th style="width: 0%;" class="text-center">No</th>
						    <th style="width:0%;">ID</th>
                                                    <th style="width: 12;">Kode Referensi</th>
                                                    <th style="width: 20;">Kode Kategori</th>
                                                    <th>Deskripsi</th>
                                                    <th style="width: 10%;">Satuan</th>
                                                    <th style="width: 20%;">Keterangan</th>
                                                    <th style="width: 0%;" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($referensi as $i => $row)
                                                <tr>
                                                    <td class="text-center">{{ $i + 1 }}</td>
                                                    <td>{{ $row->id_atk }}</td>
						    <td>{{ $row->kode_ref }}</td>
                                                    <td>{{ $row->kategori_id }}</td>
                                                    <td>{{ $row->deskripsi_barang }}</td>
                                                    <td>{{ $row->satuan_barang }}</td>
                                                    <td>{{ $row->keterangan_barang }}</td>
                                                    <td class="text-center">
                                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                                            <i class="fas fa-bars"></i>
                                                        </a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $row->id_atk }}">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="modal-info-{{ $row->id_atk }}">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary">
                                                                <h6 class="modal-title font-weight-bold">{{ $row->kode_ref.' - '.$row->deskripsi_barang }}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                            </div>
                                                            <form action="{{ url('admin-user/atk/gudang/update-atk/'. $row->id_atk) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-body text-capitalize">
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label">Kode Refensi</label>
                                                                        <input class="col-md-10 form-control" name="kode_ref" value="{{ $row->kode_ref }}">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label">Kode Kategori</label>
                                                                        <input class="col-md-10 form-control" name="kategori_id" value="{{ $row->kategori_id }}">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label">Deskripsi</label>
                                                                        <input class="col-md-10 form-control" name="deskripsi_barang" value="{{ $row->deskripsi_barang }}">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label">Satuan</label>
                                                                        <input class="col-md-10 form-control" name="satuan_barang" value="{{ $row->satuan_barang }}">
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label class="col-md-2 col-form-label">Keterangan</label>
                                                                        <textarea class="col-md-10 form-control" name="keterangan_barang">{{ $row->keterangan_barang }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer text-right">
                                                                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Simpan Perubahan?')">
                                                                        <i class="fas fa-save"></i> SIMPAN
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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

<!-- Modal Tambah Refensi -->

<div class="modal fade" id="modal-add-atk">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title font-weight-bold">Tambah Referensi ATK</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <form action="{{ url('admin-user/atk/gudang/insert/atk') }}" method="POST">
                @csrf
                <div class="modal-body text-capitalize">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Refensi</label>
                        <input class="col-md-10 form-control" name="kode_ref" placeholder="Kode Referensi" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Kategori</label>
                        <input class="col-md-10 form-control" name="kategori_id" placeholder="Kategori ID" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <input class="col-md-10 form-control" name="deskripsi_barang" placeholder="Deskripsi Barang" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Satuan</label>
                        <input class="col-md-10 form-control" name="satuan_barang" placeholder="Satuan Barang" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <textarea class="col-md-10 form-control" name="keterangan_barang" placeholder="Keterangan Tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Tambah Baru?')">
                        <i class="fas fa-plus-circle"></i> TAMBAH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->

<div class="modal fade" id="modal-add-kategori">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title font-weight-bold">Tambah Kategori ATK</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <form action="{{ url('admin-user/atk/gudang/insert/kategori') }}" method="POST">
                @csrf
                <div class="modal-body text-capitalize">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Kategori</label>
                        <input class="col-md-10 form-control" name="id_kategori_atk" placeholder="Kode Kategori" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <input class="col-md-10 form-control" name="deskripsi_kategori" placeholder="Deskripsi Kategori" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Satuan</label>
                        <input class="col-md-10 form-control" name="satuan_kategori" placeholder="Satuan" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <textarea class="col-md-10 form-control" name="keterangan_kategori" placeholder="Keterangan Tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Tambah Baru?')">
                        <i class="fas fa-plus-circle"></i> TAMBAH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-kategori").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-kategori_wrapper .col-md-6:eq(0)');
    })

    $(function() {
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "buttons": [
                {
                    extend: 'excel',
                    title: 'Daftar Referensi ATK',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },{
                    extend: 'pdf',
                    title: 'Daftar Referensi ATK',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },{
                    extend: 'print',
                    title: 'Daftar Referensi ATK',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }
            ]

        }).buttons().container().appendTo('#table-atk_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection
@endsection
