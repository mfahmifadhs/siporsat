@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Alat Tulis Kantor (ATK)</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar {{ $id }} ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
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
                <a href="{{ url('admin-user/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <!-- Barang Masuk -->
            <div class="col-md-12 form-group mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Riwayat {{ $id }} Barang</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-8 mt-2">
                                Riwayat {{ $id }} ATK
                            </label>
                            @if(Auth::user()->id == 3)
                            <label class="col-md-4 text-right">
                                <a href="{{ url('admin-user/atk/gudang/tambah/'. $id) }}" class="btn btn-default border border-dark">
                                    <i class="fas fa-plus-circle"></i> Tambah {{ $id }}
                                </a>
                            </label>
                            @endif
                            <div class="col-md-12">
                                <hr>
                                <table id="table-transaksi" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 0%;">No</th>
                                            <th class="text-center" style="width: 12%;">Tanggal</th>
                                            @if ($id == 'Pembelian')
                                            <th>Nama Vendor</th>
                                            <th class="text-center">Total Biaya</th>
                                            @else
                                            <th>Unit Kerja</th>
                                            @endif
                                            <th class="text-center">Total</th>
                                            <th style="width: 25%;">Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksi as $i => $row)
                                        <tr>
                                            <td class="text-center">{{ $i+1 }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\carbon::parse($row->tanggal_transaksi)->isoFormat('DD MMMM Y') }}
                                            </td>
                                            @if ($id == 'Pembelian')
                                            <td>{{ $row->nama_vendor }}</td>
                                            <td class="text-center">Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                                            @else
                                            <td>{{ $row->unit_kerja }}</td>
                                            @endif
                                            <td class="text-center">{{ $row->total_barang }} barang</td>
                                            <td>{{ $row->keterangan_transaksi }} </td>
                                            <td class="text-center">
                                                <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item btn" href="{{ url('admin-user/atk/gudang/detail-transaksi/'. $row->id_transaksi) }}">
                                                        <i class="fas fa-info-circle"></i> Info
                                                    </a>
                                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $row->id_atk }}">
                                                        <i class="fas fa-edit"></i> Edit
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
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
        $("#table-alkom").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })

        $("#table-transaksi").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "searching": true,
            "sort": true
        })
    })
</script>

@endsection
@endsection
