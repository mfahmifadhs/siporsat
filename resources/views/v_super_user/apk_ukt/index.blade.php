@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">URUSAN KERUMAH TANGGAAN</h4>
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
                                    <th style="width: 12%;">Tanggal</th>
                                    <th style="width: 10%;">Usulan</th>
                                    <th>Pengusul</th>
                                    <th class="text-center" style="width: 15%;">Status Pengajuan</th>
                                    <th class="text-center" style="width: 15%;">Status Proses</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="pt-4 text-center">{{ $no++ }}</td>
                                    <td class="pt-4">{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y | HH:mm') }}</td>
                                    <td class="pt-4">{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td class="pt-3">{{ $dataUsulan->nama_pegawai }} <br> {{ $dataUsulan->unit_kerja }}</td>
                                    <td class="text-center">
                                        <h6 class="mt-2">
                                            @if($dataUsulan->status_pengajuan_id == 1)
                                            <span class="badge badge-sm badge-pill badge-success">
                                                Disetujui
                                            </span>
                                            @elseif($dataUsulan->status_pengajuan_id == 2)
                                            <span class="badge badge-sm badge-pill badge-danger">Ditolak</span>
                                            @if ($dataUsulan->keterangan != null)
                                            <p class="small mt-2 text-danger">{{ $dataUsulan->keterangan }}</p>
                                            @endif
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center text-capitalize">
                                        <h6 class="mt-2">
                                            @if($dataUsulan->status_proses_id == 1)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu persetujuan <br> kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu konfirmasi BAST <br> kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2 )
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/ukt/usulan/perbaikan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->otp_usulan_pengusul == null && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center font-weight-bold text-uppercase">
                                                        <h5 class="font-weight-bold">Detail Pengajuan Usulan Urusan Kerumahtanggaan
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
                                                    <div class="col-md-2"><label>Nama Pengusul </label></div>
                                                    <div class="col-md-10">: {{ $dataUsulan->nama_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-10">: {{ $dataUsulan->keterangan_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Unit Kerja</label></div>
                                                    <div class="col-md-10">: {{ $dataUsulan->unit_kerja }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-10">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Surat Usulan </label></div>
                                                    <div class="col-md-10">:
                                                        <a href="{{ url('super-user/ukt/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 4 && $dataUsulan->status_pengajuan == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Surat BAST </label></div>
                                                    <div class="col-md-10">:
                                                        <a href="{{ url('super-user/ukt/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Pekerjaan
                                                    </h6>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-md-12">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1">No</div>
                                                            <div class="col-sm-3">Pekerjaan</div>
                                                            <div class="col-sm-5">Spesifikasi Pekerjaan</div>
                                                            <div class="col-sm-3">Keterangan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailUsulanUkt as $i => $dataUkt)
                                                        <div class="form-group row text-uppercase small">
                                                            <div class="col-sm-1">{{ $i + 1 }}</div>
                                                            <div class="col-sm-3">{{ $dataUkt->lokasi_pekerjaan }}</div>
                                                            <div class="col-sm-5">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                                                            <div class="col-sm-3">{!! nl2br(e($dataUkt->keterangan)) !!}</div>
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
