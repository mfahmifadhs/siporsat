@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Alat Tulis Kantor (ATK)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}"> Dashboard</a></li>
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
                <a href="{{ url('admin-user/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header" id="accordion">
                        <h4 class="card-title mt-1 font-weight-bold">
                            Daftar Usulan ATK
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
                            <form method="POST" action="{{ url('admin-user/atk/usulan/daftar/seluruh-usulan') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-3">
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
                                    <div class="col-sm-2">
                                        <label>Status Proses</label> <br>
                                        <small>Pilih Status Proses Pengajuan</small>
                                        <select name="status_proses_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Status Proses --</option>
                                            <option value="1">1 - Menunggu Persetujuan Kabag RT</option>
                                            <option value="2">2 - Sedang Diproses oleh PPK</option>
                                            <option value="3">3 - Belum Diserahkan</option>
                                            <option value="5">5 - Selesai BAST</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Jenis Usulan</label> <br>
                                        <small>Pilih Jenis Usulan ATK</small>
                                        <select name="jenis_form" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Jenis Usulan --</option>
                                            <option value="pengadaan">Pengadaan</option>
                                            <option value="distribusi">Distribusi</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <label class="mt-4">&nbsp;</label> <br>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('admin-user/atk/usulan/daftar/seluruh-usulan') }}" class="btn btn-danger">
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
                                    <th style="width: 15%;">Tanggal</th>
                                    <th style="width: 15%;">No. Surat</th>
                                    <th>Pengusul</th>
                                    <th style="width: 20%;">Rencana Pemakaian</th>
                                    <th class="text-center" style="width: 15%;">Status Proses</th>
                                    <th class="text-center" style="width: 0%;">Aksi</th>
                                    <th>Tanggal</th>
                                    <th>No. Surat</th>
                                    <th>Jenis Usulan</th>
                                    <th>Unit Kerja</th>
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
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $dataUsulan->jenis_form }} ATK <br>
                                        {{ $dataUsulan->no_surat_usulan }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $dataUsulan->nama_pegawai }} <br>
                                        {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                    </td>
                                    <td>
                                        <div class="text-spacing">
                                            {{ $dataUsulan->rencana_pengguna }}
                                        </div>
                                    </td>
                                    <td class="text-center text-capitalize">
                                        <h6 class="mt-2">
                                            @if($dataUsulan->status_proses_id == 1)
                                            @if($dataUsulan->is_checked != true)
                                            <span class="badge badge-sm badge-pill badge-warning">
                                                sedang di validasi <br> oleh PJ
                                            </span>
                                            @else
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu persetujuan <br> kabag RT</span>
                                            @endif
                                            @elseif ($dataUsulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning">
                                                @php
                                                $atkNull = $dataUsulan->permintaanAtk
                                                ->where('status_penyerahan', null)
                                                ->where('status','diterima')
                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                ->count();
                                                $atkFalse = $dataUsulan->permintaanAtk
                                                ->where('status_penyerahan', 'false')
                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                ->count();
                                                $atkNull2 = $dataUsulan->permintaan2Atk
                                                ->where('status_penyerahan', null)
                                                ->where('status','diterima')
                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                ->count();
                                                $atkFalse2 = $dataUsulan->permintaan2Atk
                                                ->where('status_penyerahan', 'false')
                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                ->count();
                                                $ttdPpk = $dataUsulan->bastAtk->where('otp_bast_ppk', null)->count();
                                                $ttdPengusul = $dataUsulan->bastAtk->where('otp_bast_pengusul', null)->count();
                                                $ttdKabag = $dataUsulan->bastAtk->where('otp_bast_kabag', null)->count();
                                                $belum_diserahkan = (int) $atkNull + $atkFalse;
                                                $belum_diserahkan2 = (int) $atkNull2 + $atkFalse2;
                                                @endphp

                                                @if ($dataUsulan->jenis_form != 'permintaan' && $belum_diserahkan != 0)
                                                {{ $belum_diserahkan }} barang <br> belum diserahkan
                                                @elseif ($dataUsulan->jenis_form == 'permintaan' && $belum_diserahkan2 != 0)
                                                {{ $belum_diserahkan2 }} barang <br> belum diserahkan
                                                @else
                                                seluruh barang <br> sudah diserahkan
                                                @endif
                                            </span>
                                            @if ($dataUsulan->bastAtk->count() != 0 && $dataUsulan->bastAtk->where('otp_bast_ppk', null)->count() == 1 ||
                                            $dataUsulan->bastAtk->where('otp_bast_pengusul', null)->count() == 1 ||
                                            $dataUsulan->bastAtk->where('otp_bast_kabag', null)->count() == 1
                                            )
                                            <hr>
                                            <span class="badge badge-sm badge-pill badge-warning">
                                                Menunggu Proses <br> Tanda Tangan BAST
                                            </span>
                                            @endif
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                            @elseif ($dataUsulan->status_pengajuan_id == 2)
                                            @if ($dataUsulan->keterangan != null)
                                            <p class="small text-danger">{{ $dataUsulan->keterangan }}</p>
                                            @else
                                            <p class="small text-danger">Ditolak</p>
                                            @endif
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->jenis_form == 'distribusi' && $dataUsulan->status_proses_id == 3 && $belum_diserahkan != 0)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/atk/usulan/edit/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-people-carry"></i> Menyerahkan
                                            </a>
                                            @elseif ($dataUsulan->jenis_form == 'permintaan' && $dataUsulan->status_proses_id == 3 && $belum_diserahkan2 != 0)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/atk/usulan/edit/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-people-carry"></i> Menyerahkan
                                            </a>
                                            @endif
                                            @if ($dataUsulan->is_checked == null)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/atk/usulan/validasi/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-clipboard-check"></i> Validasi
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#usulan-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->jenis_form != 'pengadaan')
                                            @if ($dataUsulan->bastAtk->count() != 0)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#bast-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Berita Acara
                                            </a>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td>{{ $dataUsulan->jenis_form }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
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
                                <!-- Modal Usulan -->
                                <div class="modal fade" id="usulan-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
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
                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Tanggal Usulan </label></div>
                                                            <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Nomor Surat Usulan </label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->no_surat_usulan }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Jenis Usulan</label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->jenis_form }} ATK</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Total Pengajuan</label></div>
                                                            <div class="col-md-7">:
                                                                {{ $dataUsulan->total_pengajuan }}
                                                            </div>
                                                        </div>
                                                        @if ($dataUsulan->otp_usulan_pengusul != null)
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Surat Usulan</label></div>
                                                            <div class="col-md-7">:
                                                                <a href="{{ url('admin-user/surat/usulan-atk/'. $dataUsulan->id_form_usulan) }}">
                                                                    <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($dataUsulan->jenis_form == 'pengadaan')
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Data Pengadaan</label></div>
                                                            <div class="col-md-7">:
                                                                <a href="{{ url('admin-user/atk/surat/download-pengadaan/'. $dataUsulan->id_form_usulan) }}">
                                                                    <i class="fas fa-download"></i> Download File
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Nama Pengusul </label></div>
                                                            <div class="col-md-7">: {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Jabatan Pengusul </label></div>
                                                            <div class="col-md-7">: {{ ucfirst(strtolower($dataUsulan->keterangan_pegawai)) }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Unit Kerja</label></div>
                                                            <div class="col-md-7">: {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Rencana Pengguna</label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->rencana_pengguna }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi ATK
                                                    </h6>
                                                </div>
                                                <div class="form-group row small">
                                                    <div class="col-md-12 ">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-3">Nama Barang</div>
                                                            <div class="col-sm-3">Deskripsi</div>
                                                            <div class="col-sm-1">Permintaan</div>
                                                            <div class="col-sm-1">Disetujui</div>
                                                            @if ($dataUsulan->jenis_form == 'pengadaan')
                                                            <div class="col-sm-3">Keterangan</div>
                                                            @else
                                                            <div class="col-sm-1">Diserahkan</div>
                                                            <div class="col-sm-2 text-center">Keterangan</div>
                                                            @endif
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @if ($dataUsulan->jenis_form == 'pengadaan')
                                                        @foreach($dataUsulan->pengadaanAtk as $i => $dataAtk)
                                                        <div class="form-group row">
                                                            <div class="col-md-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-md-3">
                                                                {{ $dataAtk->jenis_barang }} <br>
                                                                {{ $dataAtk->nama_barang }}
                                                            </div>
                                                            <div class="col-md-3">{{ $dataAtk->spesifikasi }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-2">
                                                                {{ $dataAtk->status }}
                                                                @if ($dataAtk->keterangan != null)
                                                                <br>({{ $dataAtk->keterangan }})
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                        @elseif ($dataUsulan->jenis_form == 'distribusi')
                                                        @foreach($dataUsulan->permintaanAtk as $i => $dataAtk)
                                                        <div class="form-group row">
                                                            <div class="col-md-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->nama_barang }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->spesifikasi }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah_penyerahan.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-2 text-center font-weight-bold">
                                                                @if ($dataAtk->jumlah_disetujui == $dataAtk->jumlah_penyerahan && $dataAtk->jumlah_disetujui != 0)
                                                                <span class="text-success">✅ Sudah Diserahkan Semua</span>
                                                                @elseif ($dataAtk->jumlah_disetujui == 0)
                                                                <span class="text-danger">❌ Tidak Disetujui</span>
                                                                @elseif ($dataAtk->jumlah_penyerahan != 0)
                                                                <span class="text-dark">
                                                                    {{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_penyerahan }}
                                                                    {{ ucfirst(strtolower($dataAtk->satuan)) }} Belum Diserahkan
                                                                </span>
                                                                @else
                                                                <span class="text-danger">Belum Diserahkan</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @endforeach
                                                        @else
                                                        @foreach($dataUsulan->permintaan2Atk as $i => $dataAtk)
                                                        <div class="form-group row">
                                                            <div class="col-md-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->deskripsi_barang }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->catatan }}</div>
                                                            <div class="col-md-1">{{ (int) $dataAtk->jumlah.' '.$dataAtk->satuan_barang }}</div>
                                                            <div class="col-md-1">{{ (int) $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan_barang }}</div>
                                                            <div class="col-md-1">{{ (int) $dataAtk->jumlah_penyerahan.' '.$dataAtk->satuan_barang }}</div>
                                                            <div class="col-md-2 text-center font-weight-bold">
                                                                @if ($dataAtk->jumlah_disetujui == $dataAtk->jumlah_penyerahan && $dataAtk->jumlah_penyerahan != 0)
                                                                <span class="text-success">✅ Sudah Diserahkan Semua</span>
                                                                @elseif ($dataAtk->jumlah_penyerahan != 0)
                                                                <span class="text-dark">
                                                                    {{ $dataAtk->jumlah_disetujui - $dataAtk->jumlah_penyerahan }}
                                                                    {{ ucfirst(strtolower($dataAtk->satuan)) }} Belum Diserahkan
                                                                </span>
                                                                @elseif ($dataAtk->status == 'ditolak')
                                                                <span class="text-danger">❌ Tidak Disetujui</span>
                                                                @else
                                                                <span class="text-danger">Belum Diserahkan</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Bast -->
                                <div class="modal fade" id="bast-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-xl">
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
                                                        <h5>Berita Acara Serah Terima
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
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Tanggal Usulan </label></div>
                                                            <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Nomor Surat Usulan </label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->no_surat_usulan }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Jenis Usulan</label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->jenis_form }} ATK</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Total Pengajuan</label></div>
                                                            <div class="col-md-7">: {{ $dataUsulan->total_pengajuan }} barang</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Belum Diserahkan</label></div>
                                                            <div class="col-md-7">:
                                                                @php
                                                                $atkNull = $dataUsulan->permintaanAtk
                                                                ->where('status_penyerahan', null)
                                                                ->where('status','diterima')
                                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                                ->count();
                                                                $atkFalse = $dataUsulan->permintaanAtk
                                                                ->where('status_penyerahan', 'false')
                                                                ->where('form_usulan_id', $dataUsulan->id_form_usulan)
                                                                ->count();
                                                                $ttdPpk = $dataUsulan->bastAtk->where('otp_bast_ppk', null)->count();
                                                                $ttdPengusul = $dataUsulan->bastAtk->where('otp_bast_pengusul', null)->count();
                                                                $ttdKabag = $dataUsulan->bastAtk->where('otp_bast_kabag', null)->count();
                                                                $belum_diserahkan = (int) $atkNull + $atkFalse;
                                                                @endphp
                                                                {{ $belum_diserahkan }} barang
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                            <div class="col-md-8">: {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                                            <div class="col-md-8">: {{ ucfirst(strtolower($dataUsulan->keterangan_pegawai)) }}</div>
                                                        </div>
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-4"><label>Unit Kerja</label></div>
                                                            <div class="col-md-8">: {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi ATK
                                                    </h6>
                                                </div>
                                                <div class="form-group row small">
                                                    <div class="col-md-12 ">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-3">Tanggal Bast</div>
                                                            <div class="col-sm-3">Nomor Surat BAST</div>
                                                            <div class="col-sm-2 text-center">Jumlah Penyerahan</div>
                                                            <div class="col-sm-2 text-center">Status</div>
                                                            <div class="col-sm-1 text-center">Aksi</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($dataUsulan->bastAtk as $i => $dataBast)
                                                        <div class="form-group row">
                                                            <div class="col-md-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-md-3">{{ \Carbon\Carbon::parse($dataBast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                                            <div class="col-md-3">{{ $dataBast->nomor_bast }}</div>
                                                            <div class="col-md-2 text-center">
                                                                {{ $dataUsulan->jenis_form == 'distribusi' ? $dataBast->detailBast->count() : $dataBast->detailBast2->count() }}
                                                                barang
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                @if (!$dataBast->otp_bast_ppk)
                                                                Menunggu Konfirmasi PPK
                                                                @elseif (!$dataBast->otp_bast_pengusul)
                                                                Menunggu Konfirmasi Pengusul
                                                                @elseif (!$dataBast->otp_bast_kabag)
                                                                Menunggu Konfirmasi Kabag RT
                                                                @else
                                                                Sudah Selesai BAST
                                                                @endif
                                                            </div>
                                                            <div class="col-md-1 text-center">
                                                                <a href="{{ url('admin-user/surat/detail-bast-atk/'. $dataBast->id_bast) }}" class="btn btn-primary" rel="noopener" target="_blank">
                                                                    <i class="fas fa-external-link-square-alt"></i>
                                                                </a>
                                                            </div>
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
                "aTargets": [7, 8, 9, 10, 11]
            }, ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan ATK',
                    exportOptions: {
                        columns: [0, 7, 8, 9, 10],
                    },
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan ATK',
                    exportOptions: {
                        columns: [0, 7, 8, 9, 10, 11]
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
