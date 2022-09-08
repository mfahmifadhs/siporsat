@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang</li>
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
                    <a href="{{ url('super-user/tim-kerja/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data Kategori Barang ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($barang as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->kode_barang }}</td>
                            <td>{{ $row->nup_barang }}</td>
                            <td>{{ $row->kategori_barang }}</td>
                            <td>{{ $row->spesifikasi_barang }}</td>
                            <td>{{ $row->jumlah_barang }}</td>
                            <td>{{ $row->satuan_barang }}</td>
                            <td>{{ $row->tahun_perolehan }}</td>
                            <td>{{ $row->kondisi_barang }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["pdf"],
            "lengthMenu": [[10, 25, 50, "Semua", -1], [10, 25, 50, "Semua"]]
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
