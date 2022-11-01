@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">GEDUNG DAN BANGUNAN</h4>
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
            </div>
            <div class="col-2">
                <div class="col-12">
                    <div class="small-box bg-primary">
                        <div class="inner text-center">
                            <h3>150</h3>
                            <p>Usulan Perbaikan</p>
                        </div>
                        <a href="{{ url('unit-kerja/gdn/usulan/perbaikan/baru') }}" class="small-box-footer"><i class="fas fa-plus-circle"></i> Buat Usulan </a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="small-box bg-primary">
                        <div class="inner text-center">
                            <h3>53</h3>
                            <p>Usulan Struktural</p>
                        </div>
                        <a href="{{ url('unit-kerja/gdn/usulan/struktural/baru') }}" class="small-box-footer"><i class="fas fa-plus-circle"></i> Buat Usulan </a>
                    </div>
                </div>
            </div>
            <div class="col-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Usulan Pengajuan</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Lokasi Perbaikan</th>
                                    <th>Status Pengajuan</th>
                                    <th>Status Proses</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;$no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="pt-4">{{ $no++ }}</td>
                                    <td class="pt-4">{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y') }}</td>
                                    <td class="text-uppercase">
                                        <p class="font-weight-bold">No. Surat : {{ $dataUsulan->no_surat_usulan }}</p>
                                        @foreach($dataUsulan->detailUsulanGdn as $dataGdn)
                                            <p>
                                                <label for=""> {{ $no2++.'. '.$dataGdn->lokasi_bangunan }} / {{ $dataGdn->lokasi_spesifik }}</label><br>
                                                <span class="pl-3">○ {{ $dataGdn->bid_kerusakan.' : '.$dataGdn->keterangan  }}</span>
                                            </p>
                                        @endforeach
                                    </td>
                                    <td class="text-center pt-4">
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-capitalize pt-4">
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang diproses <br> petugas gudang</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/usulan-gdn/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Usulan
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

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
    })
</script>

@endsection



@endsection
