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
                <h3 class="card-title font-weight-bold">Laporan Pengadaan Barang</h3>
                <!-- <div class="card-tools">
                    <a href="{{ url('super-user/oldat/laporan/download/excel') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data barang ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                    <a href="{{ url('super-user/oldat/laporan/download/pdf') }}" class="btn btn-primary" title="Download PDF" onclick="return confirm('Download data barang ?')">
                        <i class="fas fa-file-pdf"></i>
                    </a>
                </div> -->
            </div>
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($barang as $row)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $row->kode_barang }}</td>
                            <td>{{ $row->nup_barang }}</td>
                            <td>{{ $row->kategori_barang }}</td>
                            <td>{{ $row->spesifikasi_barang }}</td>
                            <td>{{ $row->jumlah_barang }}</td>
                            <td>{{ $row->satuan_barang }}</td>
                            <td>{{ $row->tahun_perolehan }}</td>
                            <td>{{ $row->kondisi_barang }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" href="{{ url('super-user/oldat/laporan/detail/'. $row->id_barang) }}">
                                        <i class="fas fa-info-circle"></i> Detail
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
        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            buttons: [
                {
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'laporan_pengadaan_barang',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 10, 11,   12]
                    }
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'laporan_pengadaan_barang',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12]
                    }
                }
            ]
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
