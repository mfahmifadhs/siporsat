@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Daftar Usulan Kerumah Tanggaan</li>
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
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Usulan</th>
                                    <th>Pengusul</th>
                                    <th>Unit Kerja</th>
                                    <th class="text-center">Status Pengajuan</th>
                                    <th class="text-center">Status Proses</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $dataUsulan->jenis_form }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td class="text-center">
                                        <h6 class="mt-3">
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
                                            <span class="badge badge-sm badge-pill badge-warning">sedang diproses <br> petugas gudang</span>
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
                                            @elseif ($dataUsulan->status_proses_id == 4 || $dataUsulan->status_proses_id == 5)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> BAST
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
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
                                                        <a href="{{ url('super-user/ukt/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
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
                                                    <div class="col-md-12 text-center">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-4">Lokasi Pekerjaan</div>
                                                            <div class="col-sm-4">Spesifikasi Pekerjaan</div>
                                                            <div class="col-sm-4">Keterangan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailUsulanUkt as $dataUkt)
                                                        <div class="form-group row text-uppercase small">
                                                            <div class="col-sm-4">{{ $dataUkt->lokasi_pekerjaan }}</div>
                                                            <div class="col-sm-4">{!! $dataUkt->spesifikasi_pekerjaan !!}</div>
                                                            <div class="col-sm-4">{{ $dataUkt->keterangan }}</div>
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
            "paging": true,
            buttons: [{
                text: '(+) Usulan Pekerjaan',
                className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                action: function(e, dt, node, config) {
                    window.location.href = "{{ url('super-user/ukt/usulan/pekerjaan/baru') }}"
                }
            }]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)')
    })
</script>

@endsection
@endsection
