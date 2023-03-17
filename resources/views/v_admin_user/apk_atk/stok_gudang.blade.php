@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Alat Tulis Kantor (ATK)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Stok ATK</li>
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
                <a href="{{ url('admin-user/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Stok ATK</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-stok" class="table table-bordered fa-1x">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Referensi</th>
                                    <th>Kode Barang</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Total Pembelian</th>
                                    <th class="text-center">Total Permintaan</th>
                                    <th class="text-center">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($barang as $i => $dataAtk)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $dataAtk['kode_ref'] }}</td>
                                    <td>{{ $dataAtk['kategori_id'] }}</td>
                                    <td>{{ $dataAtk['deskripsi'] }}</td>
                                    <td class="text-center">{{ $dataAtk['satuan'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['barang_masuk'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['barang_keluar'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['jumlah'] }}</td>
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
        $("#table-stok").DataTable({
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
</script>

@endsection
@endsection
