@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0">Detail  Riwayat {{ $transaksi->kategori_transaksi }}</h1>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/dashboard/roum') }}">Gudang ATK</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/daftar-transaksi/'. $transaksi->kategori_transaksi) }}">Daftar Transaksi</a></li>
                    <li class="breadcrumb-item active">Detail Riwayat {{ $transaksi->kategori_transaksi }} ATK</li>
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
            </div>
            <div class="col-md-12 form-group mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Detail Riwayat {{ $transaksi->kategori_transaksi }} ATK</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group row">
                                    <label class="col-md-4">ID Transaksi</label>
                                    <div class="col-md-8">: {{ $transaksi->id_transaksi }}</div>
                                    <label class="col-md-4">Tanggal</label>
                                    <div class="col-md-8">: {{ \Carbon\carbon::parse($transaksi->tanggal_transaksi)->isoFormat('DD MMMM Y / HH:mm') }}</div>
                                    <label class="col-md-4">Kategori</label>
                                    <div class="col-md-8">: {{ $transaksi->kategori_transaksi }} Barang</div>
                                    <label class="col-md-4">Unit Kerja</label>
                                    <div class="col-md-8">: {{ ucfirst(strtolower($transaksi->unit_kerja)) }}</div>
                                    <label class="col-md-4">Jumlah {{ $transaksi->kategori_transaksi }}</label>
                                    <div class="col-md-8">: {{ $transaksi->total_barang }} Barang</div>
                                    <label class="col-md-4">Keterangan</label>
                                    <div class="col-md-8">: {{ $transaksi->keterangan_transaksi }}</div>
                                </div>
                            </div>
                            @if ($transaksi->kategori_transaksi == 'Pembelian')
                            <div class="col-md-6 col-6">
                                <div class="form-group row">
                                    <label class="col-md-4">Nomor Kwitansi</label>
                                    <div class="col-md-8">: {{ $transaksi->nomor_kwitansi }}</div>
                                    <label class="col-md-4">Nama Vendor</label>
                                    <div class="col-md-8">: {{ $transaksi->nama_vendor }}</div>
                                    <label class="col-md-4">Alamat Vendor</label>
                                    <div class="col-md-8">: {{ $transaksi->alamat_vendor }}</div>
                                    <label class="col-md-4">Total Biaya</label>
                                    <div class="col-md-8">: Rp {{ number_format($transaksi->total_biaya, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-md-12 col-12">
                                <label class="text-muted">Daftar Barang</label>
                                <table class="table table-bordered">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Kode Referensi</th>
                                            <th class="text-center">Kode Kategori</th>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Volume</th>
                                            <th class="text-center">Satuan</th>
                                            @if($transaksi->kategori_transaksi == 'Pembelian')
                                            <th>Harga Satuan</th>
                                            <th>Jumlah Biaya</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi->detailTransaksi as $i => $detail)
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td class="text-center">{{ $detail->kode_ref }}</td>
                                            <td class="text-center">{{ $detail->kategori_id }}</td>
                                            <td>{{ $detail->deskripsi_barang }}</td>
                                            <td class="text-center">{{ $detail->volume_transaksi }}</td>
                                            <td class="text-center">{{ $detail->satuan_barang }}</td>
                                            @if($transaksi->kategori_transaksi == 'Pembelian')
                                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->jumlah_biaya, 0, ',', '.') }}</td>
                                            @endif
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
    })
</script>

@endsection
@endsection
