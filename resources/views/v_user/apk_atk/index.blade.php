@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h4 class="m-0 ml-2">ALAT TULIS KANTOR</h4>
            </div>
        </div>
    </div>
</div>

<section class="content text-capitalize">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12 form-group">
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
            <div class="col-md-3 col-12">
                <div class="info-box mb-3 bg-primary">
                    <span class="info-box-icon"><i class="fas fa-cubes"></i></span>
                    <div class="info-box-content">
                        <div class="row">
                            <div class="col-md-8">
                                <span class="info-box-text">Daftar Kebutuhan ATK</span>
                                <span class="info-box-number">
                                    {{ $stok->sum('jumlah_disetujui') - $stok->sum('jumlah_pemakaian') }}
                                </span>
                            </div>
                            <div class="col-md-4 pt-3">
                                <a href="{{ url('unit-kerja/atk/barang/stok/*') }}" class="text-black btn btn-default btn-sm">
                                    Lihat &nbsp;<i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="info-box mb-3 bg-primary">
                    <span class="info-box-icon"><i class="fas fa-cubes"></i></span>
                    <div class="info-box-content">
                        <div class="row">
                            <div class="col-md-8">
                                <span class="info-box-text">Daftar Referensi ATK</span>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ url('unit-kerja/atk/barang/referensi/*') }}" class="text-black btn btn-default btn-sm">
                                    Lihat &nbsp;<i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="col-md-9 col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Usulan</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 1%;">No</th>
                                    <th class="text-left" style="width: 44%;">Tanggal / No. Surat</th>
                                    <th style="width: 25%;">Keterangan</th>
                                    <th style="width: 25%;">Status Pengajuan</th>
                                    <th style="width: 25%;">Status Proses</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr class="text-center">
                                    <td class="text-center pt-3">{{ $no++ }}</td>
                                    <td class="text-left">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y | HH:mm') }} <br>
                                        <span class="text-uppercase">{{ $dataUsulan->jenis_form }}</span> <br>
                                        No. Surat :
                                        @if ($dataUsulan->status_pengajuan_id == 1)
                                        {{ strtoupper($dataUsulan->no_surat_usulan) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="pt-4">{{ $dataUsulan->rencana_pengguna }}</td>
                                    <td class="pt-2">
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
                                    <td class="pt-2">
                                        <h6 class="mt-3">
                                            @if($dataUsulan->status_proses_id == 1)
                                            <span class="badge badge-sm badge-pill badge-warning">Menunggu Persetujuan <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning">Barang Sedang <br> Dipersiapkan</span>
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
                                            <!-- Total BAST yang belum di ttd Pengusul -->
                                            @if ($dataUsulan->bastAtk->where('otp_bast_ppk', '!=', null)->where('otp_bast_pengusul', null)
                                            ->where('otp_bast_kabag', null)->count() != 0)
                                            <hr>
                                            <span class="badge badge-sm badge-pill badge-warning mt-1">
                                                {{ $dataUsulan->bastAtk->where('otp_bast_ppk', '!=', null)->where('otp_bast_pengusul', null)
                                                    ->where('otp_bast_pengusul', null)->count() }}
                                                Berita Acara <br> Menunggu TTD Pengusul
                                            </span>
                                            @endif

                                            @endif
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning">Menunggu Konfirmasi BAST <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">Selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <!-- @if ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-atk/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah barang telah diterima?')">
                                                <i class="fas fa-check-circle"></i> Barang Diterima
                                            </a>
                                            @endif -->
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @else
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-atk/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/atk/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                            @if ($dataUsulan->jenis_form == 'distribusi' && $dataUsulan->bastAtk->count() != 0)
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#bast-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Berita Acara
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Info Usulan -->
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
                                                        <a href="{{ url('unit-kerja/surat/usulan-atk/'. $dataUsulan->id_form_usulan) }}" rel="noopener" target="_blank">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan_id == 1 && $dataUsulan->jenis_form == 'distribusi')
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('unit-kerja/surat/detail-bast-atk/'. $dataUsulan->id_form_usulan) }}">
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
                                                                <a href="{{ url('unit-kerja/surat/detail-bast-atk/'. $dataBast->id_bast) }}" class="btn btn-primary" rel="noopener" target="_blank">
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
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {

        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            buttons: [
                // {
                //     text: '(+) Usulan Pengadaan',
                //     className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                //     action: function(e, dt, node, config) {
                //         window.location.href = "{{ url('unit-kerja/atk/usulan/pengadaan/baru') }}";
                //     }
                // },
                {
                    text: '(+) Usulan Distribusi',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/atk/usulan/distribusi/baru') }}";
                    }
                }
            ]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');

        $("#table-atk").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        $("#table-penggunaan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": false,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        })

        let total = 1
        let j = 0

        for (let i = 1; i <= 4; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/atk/select2-dashboard/` + i + `/distribusi') }}`,
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            })
        }

    })
</script>
@endsection

@endsection
