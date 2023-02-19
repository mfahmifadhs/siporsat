@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Usulan ATK</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}"> Dashboard</a></li>
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
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/atk/usulan/pengadaan/barang') }}">
                        <i class="fa fa-shopping-cart fa-2x py-1"></i> <br>
                        Usulan Pengadaan
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/atk/usulan/distribusi/barang') }}">
                        <i class="fa fa-people-carry fa-2x py-1"></i> <br>
                        Usulan Distribusi
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/atk/barang/stok/roum') }}">
                        <i class="fa fa-cubes fa-2x py-1"></i> <br>
                        Stok ATK Roum
                    </a>
                </div>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <b class="font-weight-bold text-primary card-title" style="font-size:medium;">
                            <i class="fas fa-table"></i> TABEL USULAN ATK
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
                            <form method="POST" action="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}">
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
                                            <option value="3">3 - Menunggu Konfirmasi BAST Pengusul</option>
                                            <option value="4">4 - Menunggu Konfirmasi BAST Kabag RT</option>
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
                                        <a href="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}" class="btn btn-danger">
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
                                    <th style="width: 1%;" class="text-center">No</th>
                                    <th style="width: 9%;">Tanggal</th>
                                    <th style="width: 5%;">Usulan</th>
                                    <th style="width: 5%;">No. Surat</th>
                                    <th style="width: 15%;">Pengusul</th>
                                    <th style="width: 15%;">Rencana Pemakaian</th>
                                    <th class="text-center" style="width: 11%;">Status Pengajuan</th>
                                    <th class="text-center" style="width: 10%;">Status Proses</th>
                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}</td>
                                    <td class="text-uppercase">{{ $dataUsulan->jenis_form }}</td>
                                    <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }} <br> {{ $dataUsulan->unit_kerja }}</td>
                                    <td>{{ $dataUsulan->rencana_pengguna }}</td>
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
                                                $ttdPpk = $dataUsulan->bastAtk->where('otp_bast_ppk', null)->count();
                                                $ttdPengusul = $dataUsulan->bastAtk->where('otp_bast_pengusul', null)->count();
                                                $ttdKabag = $dataUsulan->bastAtk->where('otp_bast_kabag', null)->count();
                                                $belum_diserahkan = (int) $atkNull + $atkFalse;
                                                @endphp

                                                @if ($belum_diserahkan != 0)
                                                {{ $belum_diserahkan }} barang <br> belum diserahkan
                                                @else
                                                seluruh barang <br> sudah diserahkan
                                                @endif
                                            </span>
                                            @if ($dataUsulan->bastAtk->count() != 0 && $ttdPpk != 0 || $ttdPengusul != 0 || $ttdKabag != 0)
                                            <hr>
                                            <!-- Menunggu Proses <br> Tanda Tangan BAST -->
                                            <!-- Total BAST yang belum di ttd PPK -->
                                            @if ($ttdPpk != 0 && $ttdPengusul != 0 && $ttdKabag != 0)
                                            <span class="badge badge-sm badge-pill badge-warning mt-1">
                                                {{ $ttdPpk }} Berita Acara <br> Menunggu TTD PPK
                                            </span>
                                            @endif

                                            <!-- Total BAST yang belum di ttd Pengusul -->
                                            @if ($dataUsulan->bastAtk->where('otp_bast_ppk', '!=', null)->where('otp_bast_pengusul', null)
                                            ->where('otp_bast_kabag', null)->count() != 0)
                                            <span class="badge badge-sm badge-pill badge-warning mt-1">
                                                {{ $dataUsulan->bastAtk->where('otp_bast_ppk', '!=', null)->where('otp_bast_pengusul', null)
                                                    ->where('otp_bast_pengusul', null)->count() }}
                                                Berita Acara <br> Menunggu TTD Pengusul
                                            </span>
                                            @endif

                                            <!-- Total BAST yang belum di ttd Kabag -->
                                            @if ($dataUsulan->bastAtk->where('otp_bast_ppk','!=',null)
                                            ->where('otp_bast_pengusul','!=',null)->where('otp_bast_kabag', null)->count() != 0)
                                            <span class="badge badge-sm badge-pill badge-warning mt-1">
                                                {{ $dataUsulan->bastAtk->where('otp_bast_ppk', '!=', null)
                                                            ->where('otp_bast_pengusul','!=',null)->where('otp_bast_kabag', null)->count() }}
                                                Berita Acara <br> Menunggu TTD Kabag RT
                                            </span>
                                            @endif

                                            @endif
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
                                            <!-- @if ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-atk/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah barang telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif -->
                                            <!-- Memproses Usulan -->
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/atk/usulan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/atk/usulan/'.$dataUsulan->jenis_form.'/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-atk/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            <!-- Verifikasi usulan oleh pengusul -->
                                            @if ($dataUsulan->otp_usulan_pengusul == null && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-atk/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/atk/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                            <!-- Hapus usulan oleh Kabag RT -->
                                            <!-- @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_pengajuan_id == NULL)
                                            <a class="dropdown-item btn" href="{{ url('super-user/atk/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                            @endif -->
                                            <!-- Berita Acara Serah Terima Distribusi ATK -->
                                            @if ($dataUsulan->jenis_form == 'distribusi' && $dataUsulan->bastAtk->count() != 0)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#bast-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Berita Acara
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Detail Usulan -->
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
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
                                                                @if ($dataUsulan->jenis_form == 'pengadaan')
                                                                {{ $dataUsulan->pengadaanAtk->count() }} barang
                                                                @else
                                                                {{ $dataUsulan->permintaanAtk->count() }} barang
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if ($dataUsulan->otp_usulan_pengusul != null)
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Surat Usulan</label></div>
                                                            <div class="col-md-7">:
                                                                <a href="{{ url('super-user/atk/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}" rel="noopener" target="_blank">
                                                                    <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                                </a>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if ($dataUsulan->jenis_form == 'pengadaan')
                                                        <div class="form-group row mb-0">
                                                            <div class="col-md-5"><label>Data Pengadaan</label></div>
                                                            <div class="col-md-7">:
                                                                <a href="{{ url('super-user/atk/surat/download-pengadaan/'. $dataUsulan->id_form_usulan) }}">
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
                                                <!-- <div class="form-group row mb-0">
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
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if($dataUsulan->jenis_form == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat Usulan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('super-user/atk/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id > 3 && $dataUsulan->status_pengajuan_id == 1 && $dataUsulan->jenis_form == 'distribusi' )
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('super-user/surat/bast-atk/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->jenis_form == 'pengadaan')
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Data Pengadaan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('super-user/atk/surat/download-pengadaan/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-download"></i> Download File
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif -->
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
                                                        <hr class="bg-secondary">
                                                        @endforeach
                                                        @else
                                                        @foreach($dataUsulan->permintaanAtk as $i => $dataAtk)
                                                        <div class="form-group row">
                                                            <div class="col-md-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->nama_barang }}</div>
                                                            <div class="col-md-3">{{ $dataAtk->spesifikasi }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah_disetujui.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-1">{{ $dataAtk->jumlah_penyerahan.' '.$dataAtk->satuan }}</div>
                                                            <div class="col-md-2 text-center font-weight-bold">
                                                                @if ($dataAtk->jumlah_disetujui == $dataAtk->jumlah_penyerahan && $dataAtk->jumlah_penyerahan != 0)
                                                                <span class="text-success">✅ Sudah Diserahkan Semua</span>
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
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Detail Bast -->
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
                                                            <div class="col-md-7">: {{ $dataUsulan->permintaanAtk->count() }} barang</div>
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
                                                            <div class="col-md-2 text-center">{{ $dataBast->detailBast->count() }} barang</div>
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
                                                                <a href="{{ url('super-user/surat/detail-bast-atk/'. $dataBast->id_bast) }}" class="btn btn-primary" rel="noopener" target="_blank">
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
