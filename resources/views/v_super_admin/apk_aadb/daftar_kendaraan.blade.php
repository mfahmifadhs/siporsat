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
                <table id="table-aadb" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kendaraan</th>
                            <th>No. Plat</th>
                            <th>Detail</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($kendaraan as $dataKendaraan)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <label>{{ $dataKendaraan->merk_tipe_kendaraan.' Tahun '.$dataKendaraan->tahun_kendaraan }}</label> <br>
                                No. Polisi : {{ $dataKendaraan->no_plat_kendaraan }} <br>
                                {{ $dataKendaraan->kode_barang.'.'.$dataKendaraan->nup_barang }} <br>
                                {{ $dataKendaraan->jenis_kendaraan }} <br>
                            </td>
                            <td>
                                <label>No. Plat</label> <br>
                                No. Polisi : {{ $dataKendaraan->no_plat_kendaraan }} <br>
                                Masa Berlaku : {{ $dataKendaraan->mb_stnk_plat_kendaraan }} <br>
                                <label>No. Plat RHS</label> <br>
                                No. Polisi : {{ $dataKendaraan->no_plat_rhs }} <br>
                                Masa Berlaku : {{ $dataKendaraan->mb_stnk_plat_rhs }}
                            </td>
                            <td>
                                No. BPKB: {{ $dataKendaraan->no_bpkb }} <br>
                                No. Rangka: {{ $dataKendaraan->no_rangka }} <br>
                                No. Mesin: {{ $dataKendaraan->no_mesin }} <br>
                                Nilai Perolehan: Rp {{ number_format($dataKendaraan->nilai_perolehan, 0, ',', '.') }} <br>
                            </td>
                            <td>
                                Unit Kerja : {{ $dataKendaraan->unit_kerja }} <br>
                                Pengguna : {{ $dataKendaraan->pengguna }} <br>
                                Jabatan : {{ $dataKendaraan->jabatan }} <br>
                            </td>
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
            ]
        });
    });
</script>
@endsection

@endsection
