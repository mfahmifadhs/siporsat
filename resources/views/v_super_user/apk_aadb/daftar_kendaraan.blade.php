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
        <div class="card">
            <div class="card-body">
                <table id="table-aadb" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit Kerja</th>
                            <th>Kendaraan</th>
                            <th>No. Plat</th>
                            <th>Detail</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($kendaraan as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->unit_kerja }}</td>
                            <td>
                                <label>{{ $row->merk_tipe_kendaraan.' Tahun '.$row->tahun_kendaraan }}</label> <br>
                                No. Polisi : {{ $row->no_plat_kendaraan }} <br>
                                {{ $row->kode_barang.'.'.$row->nup_barang }} <br>
                                {{ $row->jenis_kendaraan }} <br>
                            </td>
                            <td>
                                <label>No. Plat</label> <br>
                                No. Polisi : {{ $row->no_plat_kendaraan }} <br>
                                Masa Berlaku : {{ $row->mb_stnk_plat_kendaraan }} <br>
                                <label>No. Plat RHS</label> <br>
                                No. Polisi : {{ $row->no_plat_rhs }} <br>
                                Masa Berlaku : {{ $row->mb_stnk_plat_rhs }}
                            </td>
                            <td>
                                No. BPKB: {{ $row->no_bpkb }} <br>
                                No. Rangka: {{ $row->no_rangka }} <br>
                                No. Mesin: {{ $row->no_mesin }} <br>
                                Nilai Perolehan: Rp {{ number_format($row->nilai_perolehan, 0, ',', '.') }} <br>
                            </td>
                            <td>
                                Pengguna : {{ $row->pengguna }} <br>
                                Jabatan : {{ $row->jabatan }} <br>
                            </td>
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
        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ]
        }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
