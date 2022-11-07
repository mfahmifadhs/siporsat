@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h4 class="m-0">Daftar ATK</h4>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">4)  Barang</h3>
                        <div class="card-tools">
                            <a href="{{ url('admin-user/atk/barang/tambah-atk/3') }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-square"></i> BARANG
                            </a>
                            <a href="{{ url('admin-user/atk/barang/tambah-atk/4') }}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-square"></i> MERK/TIPE
                            </a>
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
                                    <td>Kelompok</td>
                                    <td>Kategori</td>
                                    <td>Jenis Barang</td>
                                    <td>Nama Barang</td>
                                    <td>Merk/Tipe</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataAtk)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataAtk->KategoriATK->JenisATK->SubKelompokATK->KelompokATK->id_kelompok_atk }}</b><br>
                                        {{ $dataAtk->KategoriATK->JenisATK->SubKelompokATK->KelompokATK->kelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataAtk->KategoriATK->JenisATK->SubKelompokATK->id_subkelompok_atk }}</b><br>
                                        {{ $dataAtk->KategoriATK->JenisATK->SubKelompokATK->subkelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataAtk->KategoriATK->JenisATK->id_jenis_atk }}</b><br>
                                        {{ $dataAtk->KategoriATK->JenisATK->jenis_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataAtk->KategoriATK->id_kategori_atk }}</b><br>
                                        {{ $dataAtk->KategoriATK->kategori_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataAtk->id_atk }}</b><br>
                                        {{ $dataAtk->merk_atk }}
                                    </td>
                                    <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">3) Jenis Barang</h3>
                        <div class="card-tools">
                            <a href="{{ url('admin-user/atk/barang/tambah/3') }}" type="button" class="btn btn-tool">
                                <i class="fas fa-plus-square"></i>
                            </a>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-jenis-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Kelompok</td>
                                    <td>Kategori</td>
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
                                        <b class="text-info">{{ $dataJenis->SubKelompokATK->KelompokATK->id_kelompok_atk }}</b><br>
                                        {{ $dataJenis->SubKelompokATK->KelompokATK->kelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataJenis->SubKelompokATK->id_subkelompok_atk }}</b><br>
                                        {{ $dataJenis->SubKelompokATK->subkelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataJenis->id_jenis_atk }}</b><br>
                                        {{ $dataJenis->jenis_atk }}
                                    </td>
                                    <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">1) Kelompok Barang</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-kelompok-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Kelompok ATK</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($kelompokAtk as $dataKelompok)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataKelompok->id_kelompok_atk }}</b><br>
                                        {{ $dataKelompok->kelompok_atk }}
                                    </td>
                                    <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">2) Kategori Barang</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-sub-atk" class="table table-bordered text-center">
                            <thead class="font-weight-bold">
                                <tr>
                                    <td>No</td>
                                    <td>Kelompok ATK</td>
                                    <td>Kategori</td>
                                    <td>Aksi</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($subkelompokAtk as $dataSubkelompok)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataSubkelompok->KelompokATK->id_kelompok_atk }}</b><br>
                                        {{ $dataSubkelompok->KelompokATK->kelompok_atk }}
                                    </td>
                                    <td class="text-left">
                                        <b class="text-info">{{ $dataSubkelompok->id_subkelompok_atk }}</b><br>
                                        {{ $dataSubkelompok->subkelompok_atk }}
                                    </td>
                                    <td><a href="#" class="btn btn-primary"><i class="fas fa-edit"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-kategori-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
        $("#table-jenis-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
        $("#table-sub-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
        $("#table-kelompok-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
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
