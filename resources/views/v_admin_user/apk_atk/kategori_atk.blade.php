@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1>{{ $id }} ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/atk/barang/daftar/seluruh-barang') }}">Daftar ATK</a></li>
                    <li class="breadcrumb-item active">{{ $id }} ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($id == 'kelompok')
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Kelompok ATK</h3>
                        <div class="card-tools">
                            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Kelompok Barang</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($subkelompokAtk as $dataSubkelompok)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataSubkelompok->id_subkelompok_atk }}</b><br>
                                        {{ $dataSubkelompok->subkelompok_atk }}
                                    </td>
                                    <td>
                                        <a href="#" type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $dataSubkelompok->id_subkelompok_atk }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ $dataSubkelompok->id_subkelompok_atk }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ubah Merk Barang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('admin-user/atk/barang/edit-atk/'. $dataSubkelompok->id_subkelompok_atk) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="atk" value="subkelompok_atk">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-form-label">ID Barang</label>
                                                        <input type="text" class="form-control" name="id_subkelompok_atk" value="{{ $dataSubkelompok->id_subkelompok_atk }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Barang</label>
                                                        <input type="text" class="form-control text-uppercase" name="subkelompok_atk" value="{{ $dataSubkelompok->subkelompok_atk }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah data barang ini ?')">
                                                        <i class="fas fa-edit"></i> Ubah
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
</section>
@endif

@if ($id == 'jenis')
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Jenis ATK</h3>
                        <!-- <div class="card-tools">
                            <a href="{{ url('admin-user/atk/barang/tambah-atk/2') }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-square"></i> BARANG
                            </a>
                        </div> -->
                    </div>
                    <div class="card-body">
                        <table id="table-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Sub Kelompok</td>
                                    <td>Jenis Barang</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($jenisAtk as $dataJenis)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataJenis->SubKelompokATK->id_subkelompok_atk }}</b><br>
                                        {{ $dataJenis->SubKelompokATK->subkelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataJenis->id_jenis_atk }}</b><br>
                                        {{ $dataJenis->jenis_atk }}
                                    </td>
                                    <td>
                                        <a href="#" type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $dataJenis->id_jenis_atk }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ $dataJenis->id_jenis_atk }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ubah Merk Barang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('admin-user/atk/barang/edit-atk/'. $dataJenis->id_jenis_atk) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="atk" value="jenis_atk">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-form-label">ID Barang</label>
                                                        <input type="text" class="form-control" name="id_jenis_atk" value="{{ $dataJenis->id_jenis_atk }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Barang</label>
                                                        <input type="text" class="form-control text-uppercase" name="jenis_atk" value="{{ $dataJenis->jenis_atk }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah data barang ini ?')">
                                                        <i class="fas fa-edit"></i> Ubah
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
</section>
@endif

@if ($id == 'kategori')
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Barang</h3>
                        <div class="card-tools">
                            <a href="{{ url('admin-user/atk/barang/tambah-atk/3') }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-square"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-sub-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Jenis Barang</td>
                                    <td>Nama Barang</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($kategoriAtk as $dataKategori)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataKategori->JenisATK->id_jenis_atk }}</b><br>
                                        {{ $dataKategori->JenisATK->jenis_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataKategori->id_kategori_atk }}</b><br>
                                        {{ $dataKategori->kategori_atk }}
                                    </td>
                                    <td>
                                        <a href="#" type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $dataKategori->id_kategori_atk }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="edit{{ $dataKategori->id_kategori_atk }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ubah Merk Barang</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('admin-user/atk/barang/edit-atk/'. $dataKategori->id_kategori_atk) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="atk" value="kategori_atk">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-form-label">ID Barang</label>
                                                        <input type="text" class="form-control" name="id_kategori_atk" value="{{ $dataKategori->id_kategori_atk }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label">Barang</label>
                                                        <input type="text" class="form-control text-uppercase" name="kategori_atk" value="{{ $dataKategori->kategori_atk }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah data barang ini ?')">
                                                        <i class="fas fa-edit"></i> Ubah
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
</section>
@endif

@section('js')
<script>
    $(function() {
        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

    })
</script>
@endsection

@endsection
