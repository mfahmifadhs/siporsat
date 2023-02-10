@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Usulan Kerumahtanggaan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/ukt/dashboard') }}"> Dashboard</a></li>
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
            </div>
            <div class="col-md-12 float-right form-group">
                <div class="float-right">
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/ukt/usulan/pekerjaan/baru') }}">
                        <i class="fas fa-briefcase fa-2x py-1"></i> <br>
                        Usulan Pekerjaan
                    </a>
                </div>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <b class="font-weight-bold text-primary card-title" style="font-size:medium;">
                            <i class="fas fa-table"></i> TABEL USULAN URUSAN KERUMAHTANGGAAN
                        </b>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-md">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <form method="POST" action="{{ url('super-user/ukt/usulan/daftar/seluruh-usulan') }}">
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
                                        <a href="{{ url('super-user/ukt/usulan/daftar/seluruh-usulan') }}" class="btn btn-danger">
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
                                    <th style="width: 9%;">Tanggal</th>
                                    <th style="width: 5%;">No. Surat</th>
                                    <th style="width: 15%;">Pengusul</th>
                                    <th style="width: 15%;">Usulan</th>
                                    <th class="text-center" style="width: 11%;">Status Pengajuan</th>
                                    <th class="text-center" style="width: 10%;">Status Proses</th>
                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}</td>
                                    <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }} <br> {{ $dataUsulan->unit_kerja }}</td>
                                    <td class="text-uppercase">
                                        @foreach ($dataUsulan->detailUsulanUkt as $detailUkt)
                                        {!! nl2br(e(Str::limit($detailUkt->spesifikasi_pekerjaan, 50))) !!}
                                        @endforeach
                                    </td>
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
                                            <span class="badge badge-sm badge-pill badge-warning">konfirmasi pekerjaan <br> telah diterima</span>
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
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->status_proses_id == 1 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/edit/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @elseif ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah pekerjaan telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
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
                                            @if ($dataUsulan->otp_usulan_pengusul == null && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-ukt/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif

                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_pengajuan_id == NULL)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ukt/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
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
                                                    <div class="col-md-2"><label>Nomor Surat </label></div>
                                                    <div class="col-md-10">: {{ $dataUsulan->no_surat_usulan }}</div>
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
                                                @if ($dataUsulan->status_proses_id > 3 && $dataUsulan->status_pengajuan_id == 1)
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
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ]

        })
    })
</script>

@endsection
@endsection
