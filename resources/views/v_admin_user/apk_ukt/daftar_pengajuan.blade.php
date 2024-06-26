@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Urusan Kerumahtanggaan (UKT)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/ukt/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan</li>
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
                <a href="{{ url('admin-user/ukt/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary" id="accordion">
                    <div class="card-header">
                        <h4 class="card-title mt-1 font-weight-bold">
                            Daftar Usulan Kerumahtanggaan
                        </h4>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-default btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <form method="POST" action="{{ url('admin-user/ukt/usulan/daftar/seluruh-usulan') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Pilih Tanggal</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <small>Tanggal Awal</small>
                                                <input type="date" class="form-control border-dark" name="start_date">
                                            </div>
                                            <div class="col-md-2 text-center" style="margin-top: 30px;"> ➖ </div>
                                            <div class="col-md-5">
                                                <small>Tanggal Akhir</small>
                                                <input type="date" class="form-control border-dark" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Unit Kerja</label> <br>
                                        <small>Pilih Unit Kerja</small>
                                        <select name="unit_kerja_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Unit Kerja --</option>
                                            @foreach ($uker as $dataUker)
                                            <option value="{{ $dataUker->id_unit_kerja }}">{{ $dataUker->unit_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Status Proses</label> <br>
                                        <small>Pilih Status Proses Pengajuan</small>
                                        <select name="status_proses_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Status Proses --</option>
                                            <option value="1">1 - Menunggu Persetujuan Kabag RT</option>
                                            <option value="2">2 - Sedang Diproses oleh PPK</option>
                                            <option value="3">3 - Menunggu Konfirmasi BAST Pengusul</option>
                                            <option value="4">4 - Menunggu Konfirmasi BAST Kabag RT</option>
                                            <option value="5">5 - Selesai BAST</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <label class="mt-4">&nbsp;</label> <br>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('admin-user/ukt/usulan/daftar/seluruh-usulan') }}" class="btn btn-danger">
                                            <i class="fas fa-undo"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%;">No</th>
                                    <th style="width: 29%;">Pengusul</th>
                                    <th>Usulan</th>
                                    <th class="text-center" style="width: 15%;">Status Proses</th>
                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                    <th>Tanggal</th>
                                    <th>No. Surat</th>
                                    <th>Jenis Usulan</th>
                                    <th>Unit Kerja</th>
                                    <th>Usulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="text-center">
                                        @if($dataUsulan->status_pengajuan_id == null)
                                        <i class="fas fa-clock text-warning"></i>
                                        @elseif($dataUsulan->status_pengajuan_id == 1)
                                        <i class="fas fa-check-circle text-green"></i>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <i class="fas fa-times-circle text-red"></i>
                                        @endif
                                        {{ $no++ }}
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-3">Tanggal</div>:
                                            <div class="col-md-8">
                                                {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}
                                            </div>
                                            <div class="col-md-3">No. Surat</div>:
                                            <div class="col-md-8">
                                                {{ $dataUsulan->no_surat_usulan }}
                                            </div>
                                            <div class="col-md-3">Nama</div>:
                                            <div class="col-md-8">
                                                {{ $dataUsulan->nama_pegawai }}
                                            </div>
                                            <div class="col-md-3">Unit Kerja</div>:
                                            <div class="col-md-8 text-capitalize">
                                                {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-left">
                                        @foreach($dataUsulan->detailUsulanUkt->take(1) as $detailUkt)
                                        <div class="form-group row">
                                            <div class="col-md-2">Pekerjaan</div>:
                                            <div class="col-md-9 text-capitalize">
                                                {{ ucfirst(strtolower($detailUkt->lokasi_pekerjaan)) }}
                                            </div>
                                            <div class="col-md-2">Spesifikasi</div>:
                                            <div class="col-md-9 text-capitalize">
                                                {!! nl2br(e(ucfirst(strtolower($detailUkt->spesifikasi_pekerjaan)))) !!}

                                            </div>
                                            <div class="col-md-2">Keterangan</div>:
                                            <div class="col-md-9 text-capitalize">
                                                {!! nl2br(e(ucfirst(strtolower($detailUkt->keterangan)))) !!}

                                            </div>
                                        </div>
                                        @endforeach
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
                                            @elseif ($dataUsulan->status_pengajuan_id == 2)
                                            <small class="text-danger">
                                                {{ $dataUsulan->keterangan }}
                                            </small>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>

                                            <a href="{{ url('admin-user/ukt/edit/usulan/'. $dataUsulan->id_form_usulan) }}" class="dropdown-item btn" type="button">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            @if ($dataUsulan->status_proses_id > 3 && $dataUsulan->status_pengajuan_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/surat/detail-bast-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-info-circle"></i> Berita Acara
                                            </a>
                                            @endif
                                            @if ($dataUsulan->status_proses_id == 1 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ukt/usulan/edit/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @elseif ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah pekerjaan telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ukt/usulan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2 )
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ppk/ukt/usulan/perbaikan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            @if ($dataUsulan->otp_usulan_pengusul == null && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif

                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_pengajuan_id == NULL)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ukt/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td>{{ $dataUsulan->jenis_form }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td>
                                        @foreach ($dataUsulan->detailUsulanUkt as $detailUkt)
                                        {!! nl2br(e($detailUkt->spesifikasi_pekerjaan)) !!}
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($dataUsulan->status_proses_id == 1)
                                        Menunggu Persetujuan Kabag RT
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        Sedang Diproses PPK
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        Menunggu Konfirmasi Pengusul
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        Menunggu Konfirmasi BAST Kabag RT
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        selesai
                                        @elseif ($dataUsulan->status_pengajuan_id == 2)
                                        {{ $dataUsulan->keterangan }}
                                        @endif
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
                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-3"><label>Nomor Surat </label></div>
                                                            <div class="col-md-9">: {{ $dataUsulan->no_surat_usulan }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-3"><label>Nama Pengusul </label></div>
                                                            <div class="col-md-9">: {{ $dataUsulan->nama_pegawai }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-3"><label>Jabatan Pengusul </label></div>
                                                            <div class="col-md-9">: {{ $dataUsulan->keterangan_pegawai }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-3"><label>Unit Kerja</label></div>
                                                            <div class="col-md-9">: {{ $dataUsulan->unit_kerja }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-3"><label>Tanggal Usulan </label></div>
                                                            <div class="col-md-9">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group row mb-0 text-right">
                                                            <div class="col-md-4"><label>Surat Usulan </label></div>
                                                            <div class="col-md-8">:
                                                                <a href="{{ url('admin-user/surat/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                                    <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                "bVisible": false,
                "aTargets": [5, 6, 7, 8, 9, 10]
            }, ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Urusan Kerumahtanggaan',
                    exportOptions: {
                        columns: [0, 5, 6, 7, 8, 9, 10],
                    },
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Urusan Kerumahtanggaan',
                    exportOptions: {
                        columns: [0, 5, 6, 7, 8, 9, 10]
                    },
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection
@endsection
