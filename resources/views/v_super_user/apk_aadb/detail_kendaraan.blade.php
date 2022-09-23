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
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/aadb/kendaraan') }}">Kendaraan</a></li>
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4 order-2 order-md-1">
                    <h3 class="font-weight-bold"> {{ $kendaraan->merk_kendaraan.' '.$kendaraan->tipe_kendaraan }}</h3>
                    <p class="text-muted text-capitalize">
                        {{ $kendaraan->jenis_kendaraan.' '.$kendaraan->merk_kendaraan.' '.$kendaraan->tipe_kendaraan }}
                    </p>
                    <br>
                    <div class="text-muted">
                        <h5 class="text-muted">Informasi Pengguna</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm">Pengguna
                                    <b class="d-block">{{ $kendaraan->pengguna }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">Jabatan
                                    <b class="d-block">{{ $kendaraan->jabatan }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">Pengemudi
                                    <b class="d-block">{{ $kendaraan->pengemudi }}</b>
                                </p>
                            </div>
                        </div>

                        <h5 class="text-muted mt-2">Informasi Kendaraan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm">No. Plat Kendaraan
                                    <b class="d-block">{{ $kendaraan->no_plat_kendaraan }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">Masa Berlaku STNK
                                    <b class="d-block">{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">No. Plat RHS
                                    <b class="d-block">{{ $kendaraan->no_plat_rhs }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">Masa Berlaku STNK
                                    <b class="d-block">{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_rhs)->isoFormat('DD MMMM Y') }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">No. BPKB
                                    <b class="d-block">{{ $kendaraan->no_bpkb }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">No. Rangka
                                    <b class="d-block">{{ $kendaraan->no_rangka }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">No. Mesin
                                    <b class="d-block">{{ $kendaraan->no_mesin }}</b>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm">Tahun Kendaraan
                                    <b class="d-block">{{ $kendaraan->tahun_kendaraan }}</b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-8 order-1 order-md-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Pengguna Kendaraan</h3>

                                    <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Pengguna</th>
                                                <th>Jabatan</th>
                                            </tr>
                                        </thead>
                                        @php $no = 1; @endphp
                                        <tbody>
                                            @foreach($pengguna as $dataPengguna)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dataPengguna->tanggal_pengguna)->isoFormat('DD MMMM Y') }}</td>
                                                <td>{{ $dataPengguna->pengguna }}</td>
                                                <td>{{ $dataPengguna->jabatan }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-transparent">
                                    <h3 class="card-title">Pengemudi Kendaraan</h3>

                                    <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Pengemudi</th>
                                            </tr>
                                        </thead>
                                        @php $no = 1; @endphp
                                        <tbody>
                                            @foreach($pengguna as $dataPengemudi)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dataPengguna->tanggal_pengguna)->isoFormat('DD MMMM Y') }}</td>
                                                <td>{{ $dataPengguna->pengemudi }}</td>
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
    </div>

</section>

@endsection
