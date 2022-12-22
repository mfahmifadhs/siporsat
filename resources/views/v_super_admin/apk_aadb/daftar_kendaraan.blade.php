@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master kendaraan</li>
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
                <table id="table-aadb" class="table table-bordered small">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Id</th>
                            <th>Kendaraan</th>
                            <th>Merk/Tipe</th>
                            <th>Tahun Perolehan</th>
                            <th>NUP</th>
                            <th>No. Plat</th>
                            <th>Masa Berlaku <br> STNK</th>
                            <th>Nomor BPKB</th>
                            <th>Nomor Rangka</th>
                            <th>Nomor Mesin</th>
                            <th>Pengguna</th>
                            <th>Unit Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($kendaraan as $dataKendaraan)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $dataKendaraan->id_kendaraan }}</td>
                            <td>
                                {{ $dataKendaraan->kode_barang }} <br> {{ $dataKendaraan->jenis_kendaraan }} <br> {{ $dataKendaraan->jenis_aadb }}
                            </td>
                            <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
                            <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                            <td>{{ $dataKendaraan->nup_barang }}</td>
                            <td>{{ $dataKendaraan->no_plat_kendaraan }}</td>
                            <td>{{ $dataKendaraan->mb_stnk_plat_kendaraan }}</td>
                            <td>{{ $dataKendaraan->no_bpkb }}</td>
                            <td>{{ $dataKendaraan->no_rangka }}</td>
                            <td>{{ $dataKendaraan->no_mesin }}</td>
                            <td>{{ $dataKendaraan->pengguna }}</td>
                            <td>{{ $dataKendaraan->unit_kerja }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('super-admin/aadb/kendaraan/detail/'. $dataKendaraan->id_kendaraan) }}">
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

<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('super-admin/aadb/kendaraan/file-kendaraan/upload') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <label class="col-form-label">Upload Data Kendaraan</label>
                    <input type="file" class="form-control" name="file" required>
                    <small>Format file (.xlsx)</small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, "Semua", -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                    "bVisible": false,
                    "aTargets": [1]
                },
            ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Data Master Barang',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 6, 8, 11, 12]
                        // columns: [0, 3, 4, 5, 6, 7, 8, 9]
                    }
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Data Master Barang',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    }
                },
                {
                    text: ' Upload',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    action: function(e, dt, node, config) {
                        var rowData = dt.row({
                            selected: true
                        }).data();
                        $('#upload').modal('show');
                    }
                }
            ],
        }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
