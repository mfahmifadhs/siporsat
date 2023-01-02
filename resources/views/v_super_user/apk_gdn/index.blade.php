@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">GEDUNG DAN BANGUNAN</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard GDN</li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold text-primary" style="font-size: medium;">
                                <i class="fas fa-table"></i> TOTAL USULAN
                            </h3>
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
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold text-primary font-weight-bold" style="font-size: medium;">
                            <i class="fas fa-table"></i> TABEL USULAN
                        </h4>
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
                                    <td class="pt-4 text-center">{{ $no++ }}</td>
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
                                    <td class="text-center pt-4">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/gdn/usulan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2 )
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/gdn/usulan/'. $dataUsulan->jenis_form.'/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @endif
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @endif
                                            @if ($dataUsulan->otp_usulan_pengusul == null)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-gdn/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/gdn/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @if ($dataUsulan->status_pengajuan == '')
                                                @if($dataUsulan->status_proses == 'belum proses')
                                                <span class="border border-warning">
                                                    <b class="text-warning p-3">Menunggu Persetujuan</b>
                                                </span>
                                                @elseif($dataUsulan->status_proses == 'proses')
                                                <span class="border border-primary">
                                                    <b class="text-primary p-3">Proses</b>
                                                </span>
                                                @endif
                                                @elseif ($dataUsulan->status_pengajuan == 'diterima')

                                                @else

                                                @endif
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center font-weight-bold">
                                                        <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form }}
                                                            <hr>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-2">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Pengusul
                                                    </h6>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->nama_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->keterangan_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Unit Kerja</label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->unit_kerja }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat Usulan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('super-user/gdn/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('super-user/gdn/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Perbaikan
                                                    </h6>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-4">Lokasi Pekerjaan</div>
                                                            <div class="col-sm-4">Spesifikasi Pekerjaan</div>
                                                            <div class="col-sm-4">Keterangan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailUsulanGdn as $dataGdn)
                                                        <div class="form-group row text-uppercase small">
                                                            <div class="col-sm-4">{{ $dataGdn->lokasi_bangunan }}</div>
                                                            <div class="col-sm-4">{{ $dataGdn->lokasi_spesifik }}</div>
                                                            <div class="col-sm-4">{{ $dataGdn->bid_kerusakan }}</div>
                                                            <div class="col-sm-4">{{ $dataGdn->keterangan }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
