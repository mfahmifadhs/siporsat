@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Angkutan Darat Bermotor (AADB)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan AADB</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="col-md-12 form-group">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @elseif ($message = Session::get('failed'))
            <div class="alert alert-danger">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
            <a href="{{ url('admin-user/aadb/dashboard') }}" class="print mr-2">
                <i class="fas fa-arrow-circle-left"></i> Kembali
            </a>
        </div>
        <div class="row form-group" id="rekap-default">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header" id="accordion">
                        <h4 class="card-title mt-1 font-weight-bold">
                            Daftar Usulan Aadb
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
                            <form method="POST" action="{{ url('admin-user/aadb/usulan/daftar/seluruh-usulan') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label>Pilih Tanggal</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <span style="font-size: 70%;">Tanggal Awal</span>
                                                <input type="date" class="form-control border-dark" name="start_date">
                                            </div>
                                            <div class="col-md-2 text-center" style="margin-top: 30px;"> ➖ </div>
                                            <div class="col-md-5">
                                                <span style="font-size: 70%;">Tanggal Akhir</span>
                                                <input type="date" class="form-control border-dark" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Unit Kerja</label> <br>
                                        <span style="font-size: 70%;">Pilih Unit Kerja</span>
                                        <select name="unit_kerja_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Unit Kerja --</option>
                                            @foreach ($uker as $dataUker)
                                            <option value="{{ $dataUker->id_unit_kerja }}">{{ $dataUker->unit_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Status Proses</label> <br>
                                        <span style="font-size: 70%;">Pilih Status Proses Pengajuan</span>
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
                                        <span style="font-size: 70%;">Pilih Jenis Usulan AADB</span>
                                        <select name="jenis_form" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Jenis Usulan --</option>
                                            <option value="1">Pengadaan Baru/Sewa</option>
                                            <option value="2">Permintaan Servis</option>
                                            <option value="3">Perpanjangan STNK</option>
                                            <option value="4">Permintaan Voucher BBM</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <label class="mt-4">&nbsp;</label> <br>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('admin-user/aadb/usulan/daftar/seluruh-usulan') }}" class="btn btn-danger">
                                            <i class="fas fa-undo"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 1%;">No</th>
                                    <th style="width: 15%;">Tanggal</th>
                                    <th style="width: 15%;">No. Surat</th>
                                    <th style="width: 20%;">Pengusul</th>
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
                                    <td>
                                        @if($dataUsulan->status_pengajuan_id == null)
                                        <i class="fas fa-clock text-warning"></i>
                                        @elseif($dataUsulan->status_pengajuan_id == 1)
                                        <i class="fas fa-check-circle text-green"></i>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <i class="fas fa-times-circle text-red"></i>
                                        @endif{{ $no++ }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}</td>
                                    <td>
                                        {{ $dataUsulan->no_surat_usulan }} <br>
                                        {{ $dataUsulan->jenis_form_usulan }}
                                    </td>
                                    <td>{{ $dataUsulan->nama_pegawai }} <br> {{ $dataUsulan->unit_kerja }}</td>
                                    <td class="text-uppercase">
                                        <div class="text-spacing">
                                            @if ($dataUsulan->jenis_form == 1)
                                            @foreach ($dataUsulan->usulanKendaraan as $detailAadb)
                                            {!! nl2br(e(Str::limit($detailAadb->merk_tipe_kendaraan, 50))) !!}
                                            @endforeach
                                            @elseif ($dataUsulan->jenis_form == 2)
                                            @foreach ($dataUsulan->usulanServis as $detailAadb)
                                            {!! nl2br(e(Str::limit($detailAadb->merk_tipe_kendaraan, 50))) !!}
                                            @endforeach
                                            @elseif ($dataUsulan->jenis_form == 3)
                                            @foreach ($dataUsulan->usulanSTNK as $detailAadb)
                                            {!! nl2br(e(Str::limit($detailAadb->merk_tipe_kendaraan, 50))) !!}
                                            @endforeach
                                            @else
                                            @foreach ($dataUsulan->usulanVoucher->take(5) as $detailAadb)
                                            {!! nl2br(e(Str::limit($detailAadb->merk_tipe_kendaraan, 50) . PHP_EOL)) !!}
                                            @endforeach
                                            @endif
                                        </div>
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
                                            @if ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah pekerjaan telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/aadb/usulan/persetujuan/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataUsulan->status_proses_id == 2 )
                                            <a class="dropdown-item btn" href="{{ url('admin-user/ppk/aadb/pengajuan/'.$dataUsulan->jenis_form.'/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->otp_usulan_pengusul == null && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('admin-user/aadb/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif

                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataUsulan->status_pengajuan_id == NULL)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/aadb/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                            @endif


                                            @if ($dataUsulan->status_proses_id > 3 && $dataUsulan->status_pengajuan_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('admin-user/surat/detail-bast-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-info-circle"></i> Berita Acara
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $dataUsulan->no_surat_usulan }}</td>
                                    <td>{{ $dataUsulan->jenis_form_usulan }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td>
                                        @if ($dataUsulan->jenis_form == 1)
                                        {{ $dataUsulan->usulanKendaraan->pluck('merk_tipe_kendaraan')->implode(', ') }}
                                        @elseif ($dataUsulan->jenis_form == 2)
                                        {{ $dataUsulan->usulanServis->pluck('merk_tipe_kendaraan')->implode(', ') }}
                                        @elseif ($dataUsulan->jenis_form == 3)
                                        {{ $dataUsulan->usulanSTNK->pluck('merk_tipe_kendaraan')->implode(', ') }}
                                        @else
                                        {{ $dataUsulan->usulanVoucher->pluck('merk_tipe_kendaraan')->implode(', ') }}
                                        @endif
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
                                                    <div class="col-md-12 text-center font-weight-bold">
                                                        <h5>DETAIL PENGAJUAN USULAN {{ $dataUsulan->jenis_form_usulan }}
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
                                                    <div class="col-md-3"><label>Nomor Surat </label></div>
                                                    <div class="col-md-9 text-uppercase">: {{ $dataUsulan->no_surat_usulan }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3"><label>Nama Pengusul </label></div>
                                                    <div class="col-md-9">: {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-9">: {{ ucfirst(strtolower($dataUsulan->keterangan_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3"><label>Unit Kerja</label></div>
                                                    <div class="col-md-9">: {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if($dataUsulan->rencana_pengguna)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-9">: {{ $dataUsulan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat Usulan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('admin-user/surat/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Kendaraan
                                                    </h6>
                                                </div>
                                                <div class="form-group row small">
                                                    <div class="col-md-12">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            @if ($dataUsulan->jenis_form == 1)
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">Jenis AADB</div>
                                                            <div class="col-sm-2">Jenis Kendaraan</div>
                                                            <div class="col-sm-2">Merk/Tipe</div>
                                                            <div class="col-sm-2">Tahun Kendaraan</div>
                                                            <div class="col-sm-1">Jumlah</div>
                                                            @elseif ($dataUsulan->jenis_form == 2)
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">Kendaraan</div>
                                                            <div class="col-sm-2">Kilometer Terakhir</div>
                                                            <div class="col-sm-2">Jadwal Servis</div>
                                                            <div class="col-sm-2">Jadwal Ganti Oli</div>
                                                            <div class="col-sm-2">Keterangan</div>
                                                            @elseif ($dataUsulan->jenis_form == 3)
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">No. Plat</div>
                                                            <div class="col-sm-4">Kendaraan</div>
                                                            <div class="col-sm-3">Pengguna</div>
                                                            <div class="col-sm-2">Masa Berlaku STNK Lama</div>
                                                            @elseif ($dataUsulan->jenis_form == 4)
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">Bulan Pengadaan</div>
                                                            <div class="col-sm-2">Jenis AADB</div>
                                                            <div class="col-sm-2">No. Plat</div>
                                                            <div class="col-sm-3">Kendaraan</div>
                                                            <div class="col-sm-2">Kualifikasi</div>
                                                            @endif
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @if ($dataUsulan->jenis_form == 1)
                                                        @foreach($dataUsulan->usulanKendaraan as $i =>$dataPengadaan)
                                                        <div class="form-group row">
                                                            <div class="col-sm-1 text-center">{{ $i+1 }}</div>
                                                            <div class="col-sm-2">{{ $dataPengadaan->jenis_aadb }}</div>
                                                            <div class="col-sm-2">{{ $dataPengadaan->jenis_kendaraan }}</div>
                                                            <div class="col-sm-2">{{ $dataPengadaan->merk_tipe_kendaraan }}</div>
                                                            <div class="col-sm-2">{{ $dataPengadaan->tahun_kendaraan }}</div>
                                                            <div class="col-sm-1">{{ $dataPengadaan->jumlah_pengajuan }} kendaraan</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                        @elseif ($dataUsulan->jenis_form == 2)
                                                        @foreach($dataUsulan->usulanServis as $i =>$dataServis)
                                                        <div class="form-group row">
                                                            <div class="col-sm-1 text-center">{{ $i+1 }}</div>
                                                            <div class="col-sm-2">
                                                                {{ $dataServis->no_plat_kendaraan }} <br>
                                                                {{ $dataServis->merk_tipe_kendaraan.' '.$dataServis->tahun_kendaraan }}
                                                            </div>
                                                            <div class="col-sm-2">{{ $dataServis->kilometer_terakhir }} Km</div>
                                                            <div class="col-sm-2">
                                                                Terakhir Servis : <br>
                                                                {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                                                Jatuh Tempo Servis : <br>
                                                                {{ (int) $dataServis->jatuh_tempo_servis }} Km

                                                            </div>
                                                            <div class="col-sm-2">
                                                                Terakhir Ganti Oli : <br>
                                                                {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                                                Jatuh Tempo Servis : <br>
                                                                {{ (int) $dataServis->jatuh_tempo_ganti_oli }} Km
                                                            </div>
                                                            <div class="col-sm-2">{{ $dataServis->keterangan_servis }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                        @elseif ($dataUsulan->jenis_form == 3)
                                                        @foreach($dataUsulan->usulanStnk as $i =>$dataStnk)
                                                        <div class="form-group row">
                                                            <div class="col-sm-1 text-center">{{ $i+1 }}</div>
                                                            <div class="col-sm-2">{{ $dataStnk->no_plat_kendaraan }}</div>
                                                            <div class="col-sm-4">{{ $dataStnk->merk_tipe_kendaraan.' '.$dataStnk->tahun_kendaraan }}</div>
                                                            <div class="col-sm-3">{{ $dataStnk->pengguna }}</div>
                                                            <div class="col-sm-2">
                                                                @if ($dataStnk->mb_stnk_lama != null)
                                                                {{ \Carbon\carbon::parse($dataStnk->mb_stnk_lama)->isoFormat('DD MMMM Y') }}
                                                                @else
                                                                -
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                        @elseif ($dataUsulan->jenis_form == 4)
                                                        @foreach($dataUsulan->usulanVoucher as $i =>$dataVoucher)
                                                        <div class="form-group row">
                                                            <div class="col-sm-1 text-center">{{ $i+1 }}</div>
                                                            <div class="col-sm-2">{{ \Carbon\carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</div>
                                                            <div class="col-sm-2 text-capitalize">{{ $dataVoucher->jenis_aadb }}</div>
                                                            <div class="col-sm-2 text-capitalize">{{ $dataVoucher->no_plat_kendaraan }}</div>
                                                            <div class="col-sm-3 text-capitalize">{{ $dataVoucher->merk_tipe_kendaraan }} Kendaraan</div>
                                                            <div class="col-sm-2 text-capitalize">Kendaraan {{ $dataVoucher->kualifikasi }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                        @endif
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
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                "bVisible": false,
                "aTargets": [7, 8, 9, 10, 11, 12]
            }, ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Aadb',
                    exportOptions: {
                        columns: [0, 7, 8, 9, 10, 12],
                    },
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Aadb',
                    exportOptions: {
                        columns: [0, 7, 8, 9, 10, 11, 12]
                    },
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-pengajuan_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection

@endsection
