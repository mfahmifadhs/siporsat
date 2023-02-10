@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">URUSAN KERUMAHTANGGAAN</h4>
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

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Menunggu Persetujuan</span> <br>
                                <small class="text-danger fa-xs">Menunggu Persetujuan Kabag RT</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Sedang Diproses</span> <br>
                                <small class="text-danger fa-xs">Usulan Sedang Diproses Oleh PPK</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 3)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Konfirmasi BAST Pengusul</span>
                                <small class="text-danger fa-xs">Konfirmasi Pekerjaan Diterima</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Konfirmasi BAST Kabag RT</span> <br>
                                <small class="text-danger fa-xs">Kabag RT Konfirmasi BAST</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Selesai BAST</span> <br>
                                <small class="text-danger fa-xs">BAST telah di tanda tangani</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_pengajuan_id', 2)->count() }} <small>usulan</small> </h5>
                                <span class="font-weight-bold mb-0 fa-sm">Ditolak</span> <br>
                                <small class="text-danger fa-xs">Usulan Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header bg-primary">
                        <h3 class="card-title font-weight-bold">Daftar Usulan Pengajuan</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 1%;">No</th>
                                    <th class="text-left" style="width: 20%;">Tanggal / No. Surat</th>
                                    <th class="text-left">Usulan</th>
                                    <th style="width: 15%;">Status Pengajuan</th>
                                    <th style="width: 15%;">Status Proses</th>
                                    <th style="width: 0%;">Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr class="text-center">
                                    <td class="pt-4">{{ $no++ }}</td>
                                    <td class="text-left">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y | HH:mm') }} <br>
                                        No. Surat :
                                        @if ($dataUsulan->status_pengajuan_id == 1)
                                        {{ strtoupper($dataUsulan->no_surat_usulan) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        @foreach($dataUsulan->detailUsulanUkt as $detailUkt)
                                        <div class="form-group row">
                                            <div class="col-md-3">Pekerjaan &emsp;:</div>
                                            <div class="col-md-9 text-capitalize">{{ ucfirst(strtolower($detailUkt->lokasi_pekerjaan)) }}</div>
                                            <div class="col-md-3">Spesifikasi &ensp; :</div>
                                            <div class="col-md-9 text-capitalize">{!! nl2br(e(ucfirst(strtolower($detailUkt->spesifikasi_pekerjaan)))) !!}</div>
                                            <div class="col-md-3">Keterangan &nbsp; : </div>
                                            <div class="col-md-9 text-capitalize">{!! nl2br(e(ucfirst(strtolower($detailUkt->keterangan)))) !!}</div>
                                        </div>
                                        @if (count($dataUsulan->detailUsulanUkt) > 1)
                                        <hr> @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <h6 class="mt-2">
                                            @if($dataUsulan->status_pengajuan_id == 1)
                                            <span class="badge badge-sm badge-pill badge-success p-2">
                                                Disetujui
                                            </span>
                                            @elseif($dataUsulan->status_pengajuan_id == 2)
                                            <span class="badge badge-sm badge-pill badge-danger p-2">Ditolak</span>
                                            @if ($dataUsulan->keterangan != null)
                                            <p class="small mt-2 text-danger p-2">{{ $dataUsulan->keterangan }}</p>
                                            @endif
                                            @endif
                                        </h6>
                                    </td>
                                    <td>
                                        <h6 class="mt-2">
                                            @if($dataUsulan->status_proses_id == 1)
                                            <span class="badge badge-sm badge-pill badge-warning p-2">Menunggu Persetujuan <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning p-2">Sedang Diproses <br> oleh PPK</span>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning p-2">Konfirmasi Pekerjaan <br> telah Diterima</span>
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning p-2">Menunggu Konfirmasi BAST <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success p-2">Selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->otp_usulan_pengusul == null)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif

                                            @if ($dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/ukt/usulan/edit/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah pekerjaan telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
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
                                                    <div class="col-md-12 text-center text-uppercase font-weight-bold">
                                                        <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form }} urusan kerumahtanggaan
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
                                                        <a href="{{ url('unit-kerja/surat/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan_id == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Surat BAST </label></div>
                                                    <div class="col-md-10">:
                                                        <a href="{{ url('unit-kerja/surat/bast-ukt/'. $dataUsulan->id_form_usulan) }}">
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
                                                <div class="form-group row small">
                                                    <div class="col-md-12">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-3">Lokasi</div>
                                                            <div class="col-sm-5">Spesifikasi Pekerjaan</div>
                                                            <div class="col-sm-3">Keterangan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->detailUsulanUkt as $i => $dataUkt)
                                                        @php $i = $i+1; @endphp
                                                        <div class="form-group row text-uppercase">
                                                            <div class="col-sm-1 text-center">{{ $i }}</div>
                                                            <div class="col-sm-3">{{ $dataUkt->lokasi_pekerjaan }}</div>
                                                            <div class="col-sm-5">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</div>
                                                            <div class="col-sm-3">{{ $dataUkt->keterangan }}</div>
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
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            buttons: [{
                text: '(+) Usulan Pekerjaan',
                className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                action: function(e, dt, node, config) {
                    window.location.href = "{{ url('unit-kerja/ukt/usulan/pekerjaan/baru') }}"
                }
            }]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection



@endsection
