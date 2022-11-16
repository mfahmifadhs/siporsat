@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">PEMELIHARAAN GEDUNG DAN BANGUNAN</h4>
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
            <div class="col-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Usulan Perbaikan Gedung/Bangunan</h3>
                            <div class="card-tools"></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 form-group small">Menunggu Persetujuan</div>
                                <div class="col-md-4 form-group small text-right">{{ $usulan->where('status_proses_id', 1)->count() }}</div>
                                <div class="col-md-12">
                                    <hr style="border: 1px solid grey;margin-top:-1vh;">
                                </div>
                                <div class="col-md-8 form-group small">Sedang Diproses</div>
                                <div class="col-md-4 form-group small text-right">{{ $usulan->where('status_proses_id', 2)->count() }}</div>
                                <div class="col-md-12">
                                    <hr style="border: 1px solid grey;margin-top:-1vh;">
                                </div>
                                <div class="col-md-8 form-group small">Menunggu Konfirmasi</div>
                                <div class="col-md-4 form-group small text-right">{{ $usulan->where('status_proses_id', 4)->count() }}</div>
                                <div class="col-md-12">
                                    <hr style="border: 1px solid grey;margin-top:-1vh;">
                                </div>
                                <div class="col-md-8 form-group small">Selesai</div>
                                <div class="col-md-4 form-group small text-right">{{ $usulan->where('status_proses_id', 5)->count() }}</div>
                                <div class="col-md-12">
                                    <hr style="border: 1px solid grey;margin-top:-1vh;">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ url('unit-kerja/gdn/usulan/perbaikan/baru') }}" class="small-box-footer"><i class="fas fa-plus-circle"></i> Buat Usulan </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">Daftar Usulan Pengajuan</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0 small">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Usulan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="pt-4">{{ $no++ }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        No. Surat.{{ strtoupper($dataUsulan->no_surat_usulan) }}</p>
                                        <label>Lokasi Kerusakan :</label>
                                        @foreach($dataUsulan->detailUsulanGdn as $dataGdn)
                                        <p class="text-capitalize">
                                            <label for=""> {{ $no2++.'. '.$dataGdn->lokasi_bangunan }} / {{ $dataGdn->lokasi_spesifik }}</label><br>
                                            <span class="pl-3">○ {{ ucfirst(strtolower($dataGdn->bid_kerusakan)).' : '.$dataGdn->keterangan  }}</span>
                                        </p>
                                        @endforeach
                                    </td>
                                    <td class="pt-2">
                                        Status Pengajuan : <br>
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                        @endif <br>
                                        Status Proses : <br>
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang diproses petugas gudang</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/usulan-gdn/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Usulan
                                            </a>
                                            @else
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-gdn/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/gdn/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
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
