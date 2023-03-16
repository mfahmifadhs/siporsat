@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0">Alat Tulis Kantor (ATK)</h1>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/barang/daftar/semua') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Permintaan ATK</li>
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
                        <h3 class="card-title font-weight-bold">Riwayat Permintaan {{ $atk->spesifikasi }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-2">Jenis Barang</label>
                            <div class="col-md-9">: {{ $atk->jenis_barang }}</div>
                            <label class="col-md-2">Nama Barang</label>
                            <div class="col-md-9">: {{ $atk->nama_barang }}</div>
                            <label class="col-md-2">Spesifikasi</label>
                            <div class="col-md-9">: {{ $atk->spesifikasi }}</div>
                            <label class="col-md-2">Stok</label>
                            <div class="col-md-9">: {{ $atk->jumlah_disetujui - $atk->jumlah_pemakaian.' '.$atk->satuan }}</div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Riwayat Permintaan Pengadaan</label>
                            <div class="col-md-12">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Unit Kerja</th>
                                            <th>Usulan</th>
                                            <th>Nama Barang</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pengadaan as $i => $dataUsulan)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ Carbon\carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y') }}</td>
                                            <td>{{ $dataUsulan->unit_kerja }}</td>
                                            <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                            <td>{{ $dataUsulan->nama_barang.' - '.$dataUsulan->spesifikasi }}</td>
                                            <td>{{ (int) $dataUsulan->jumlah.' '.$dataUsulan->satuan }}</td>
                                            <td>{{ (int) $dataUsulan->jumlah_disetujui.' '.$dataUsulan->satuan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Riwayat Permintaan Distribusi (Pemakaian)</label>
                            <div class="col-md-12">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Unit Kerja</th>
                                            <th>Usulan</th>
                                            <th>Nama Barang</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permintaan as $i => $dataUsulan)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ Carbon\carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y') }}</td>
                                            <td>{{ $dataUsulan->unit_kerja }}</td>
                                            <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                            <td>{{ $dataUsulan->nama_barang.' - '.$dataUsulan->spesifikasi }}</td>
                                            <td>{{ (int) $dataUsulan->jumlah.' '.$dataUsulan->satuan }}</td>
                                            <td>{{ (int) $dataUsulan->jumlah_disetujui.' '.$dataUsulan->satuan }}</td>
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
