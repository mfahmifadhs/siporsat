@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Dashboard Pengelolaan Gedung & Bangunan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
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
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @elseif ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">Daftar Usulan Pengajuan</h4>
                    </div>
                    <div class="card-body text-capitalize">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Surat Usulan</th>
                                    <th>Lokasi Perbaikan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="pt-4 text-center">{{ $no2++ }}</td>
                                    <td class="pt-3 small">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        No. Surat : {{ $dataUsulan->no_surat_usulan }} <br>
                                        Pengusul : {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }} <br>
                                        Unit Kerja : {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                    </td>
                                    <td class="small">
                                        <p class="font-weight-bold"></p>
                                        @foreach($dataUsulan->detailUsulanGdn as $i =>$dataGdn)
                                        <p>
                                            <label>{{ $no = $i + 1 }}. {{ $dataGdn->lokasi_bangunan }} / {{ $dataGdn->lokasi_spesifik }}</label> <br>
                                            <span class="pl-2">{{ ucfirst(strtolower($dataGdn->bid_kerusakan.' : '.$dataGdn->keterangan))  }}</span>
                                        </p>
                                        @endforeach
                                    </td>
                                    <td class="pt-2 small text-center">
                                        Status Pengajuan : <br>
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success py-2">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger py-2">ditolak</span>
                                        @endif <br>
                                        Status Proses : <br>
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning py-2">menunggu persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning py-2">sedang diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning py-2">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning py-2">menunggu <br> konfirmasi kabag rt</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success py-2">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" href="{{ url('super-admin/gdn/dashboard/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin menghapus usulan ini ?')">
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
        </div>
    </div>
</section>

@endsection
