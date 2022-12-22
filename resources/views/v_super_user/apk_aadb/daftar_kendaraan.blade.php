@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar AADB</li>
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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <b class="font-weight-bold text-primary card-title mt-3" style="font-size:medium;">
                    <i class="fas fa-table"></i> TABEL DAFTAR AADB
                </b>
            </div>
            <div class="card-body">
                <table id="table-aadb" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Kode Barang</th>
                            <th>Jenis AADB</th>
                            <th>Nama Kendaraan</th>
                            <th>Merk/Tipe</th>
                            <th>No. Plat</th>
                            <th>Nilai Perolehan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Unit Kerja</th>
                            <th>No. Plat</th>
                            <th>Masa Berlaku STNK</th>
                            <th>No. Plat RHS</th>
                            <th>No. BPKB</th>
                            <th>No. Rangka</th>
                            <th>No. Mesin</th>
                            <th>Pengguna</th>
                            <th>Jabatan</th>
                            <th>Pengemudi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $nos = 1; ?>
                    <tbody>
                        @foreach($kendaraan as $row)
                        <tr>
                            <td>{{ $nos++ }}</td>
                            <td>{{ $row->kode_barang }}</td>
                            <td>{{ $row->nup_barang }}</td>
                            <td>{{ $row->kode_barang.'.'.$row->nup_barang }}</td>
                            <td class="text-uppercase">{{ $row->jenis_aadb }}</td>
                            <td>{{ ucfirst(strtolower($row->jenis_kendaraan)) }}</td>
                            <td>{{ ucfirst(strtolower($row->merk_tipe_kendaraan)) }}</td>
                            <td>{{ $row->no_plat_kendaraan }}</td>
                            <td>Rp {{ number_format($row->nilai_perolehan, 0, ',', '.') }}</td>
                            <td>{{ $row->tahun_kendaraan }}</td>
                            <td>{{ $row->kondisi_kendaraan }}</td>
                            <td>{{ $row->pengguna }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td class="text-uppercase">{{ $row->no_plat_kendaraan }}</td>
                            <td>{{ $row->mb_stnk_plat_kendaraan }}</td>
                            <td>{{ $row->no_plat_rhs }}</td>
                            <td>{{ $row->no_bpkb }}</td>
                            <td>{{ $row->no_rangka }}</td>
                            <td>{{ $row->no_mesin }}</td>
                            <td>{{ $row->pengguna }}</td>
                            <td>{{ $row->jabatan }}</td>
                            <td>{{ $row->pengemudi }}</td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('super-user/aadb/kendaraan/detail/'. $row->id_kendaraan) }}">
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

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/aadb/kategori-kendaraan/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Kategori kendaraan :</label>
                        <input type="text" class="form-control" name="kategori_kendaraan" placeholder="Tambah Kategori kendaraan" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Kategori kendaraan ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                "bVisible": false,
                "aTargets": [1, 2, 13, 14, 15, 16, 17, 18, 19, 20, 21]
            }],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Kendaraan AADB',
                    exportOptions: {
                        columns: [0, 12, 5, 6, 7, 10]
                        // columns: [0, 3, 4, 5, 6, 7, 8, 9]
                    },
                    messageTop: datetime
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Kendaraan AADB',
                    exportOptions: {
                        columns: [0, 12, 1, 2, 4, 5, 6, 10, 13, 14, 15, 16, 17, 18, 8, 19, 20, 21]
                    },
                    messageTop: datetime
                }
            ],
        }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
