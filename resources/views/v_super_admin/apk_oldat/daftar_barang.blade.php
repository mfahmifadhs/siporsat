@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-admin/oldat/kategori-barang/data/semua') }}">Kategori Barang</a></li>
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
                    <a href="{{ url('super-admin/oldat/barang/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data Barang ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="table-kategori-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Pengguna</th>
                            <th>Unit Kerja</th>
                            <th class="text-center">Aksi</th>
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
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('super-admin/oldat/barang/detail/'. $row->id_barang) }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    <a class="dropdown-item" href="{{ url('super-admin/oldat/kategori-barang/proses-hapus/'. $row->id_barang) }}" onclick="return confirm('Hapus data kategori barang ?')">
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

@section('js')
<script>
    $(function() {
        $("#table-kategori-barang").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [[10, 25, 50, "Semua", -1], [10, 25, 50, "Semua"]]
        });
    });
</script>
@endsection

@endsection
