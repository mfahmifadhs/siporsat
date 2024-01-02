@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">PEMELIHARAAN ALAT ANGKUTAN DARAT BERMOTOR (AADB)</h4>
            </div>
        </div>
    </div>
</div>

<section class="content text-capitalize form-group">
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
            <div class="col-md-12 col-12 form-group">
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
            <div class="col-md-4 col-12 form-group">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Servis Record</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-servis" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kendaraan</th>
                                    <th>Servis Record</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($jadwalServis as $dataJadwal)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        <a type="button" class="font-weight-bold" data-toggle="modal" data-target="#servis{{ $dataJadwal->id_jadwal_servis }}">
                                            @if ($dataJadwal->no_plat_kendaraan != '-')
                                            {{ $dataJadwal->no_plat_kendaraan }} <br>
                                            @endif
                                            {{ $dataJadwal->merk_tipe_kendaraan }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">Kilometer</div>
                                            <div class="col-md-6">:
                                                @if ($dataJadwal->km_terakhir == null) 0 @endif
                                                {{ $dataJadwal->km_terakhir }} Km
                                            </div>
                                            <div class="col-md-6">Waktu Servis</div>
                                            <div class="col-md-6">:
                                                {{ $dataJadwal->km_terakhir + $dataJadwal->km_servis }} Km
                                            </div>
                                            <div class="col-md-6">Waktu Ganti Oli</div>
                                            <div class="col-md-6">:
                                                {{ $dataJadwal->km_terakhir + $dataJadwal->km_ganti_oli }} Km
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="servis{{ $dataJadwal->id_jadwal_servis }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    @if ($dataJadwal->no_plat_kendaraan != '-' || $dataJadwal->no_plat_kendaraan != NULL)
                                                    {{ $dataJadwal->no_plat_kendaraan }} -
                                                    @endif
                                                    {{ $dataJadwal->merk_tipe_kendaraan }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('unit-kerja/aadb/kendaraan/servis-record/'. $dataJadwal->id_jadwal_servis) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Terakhir</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_terakhir" class="form-control" value="{{ $dataJadwal->km_terakhir }}" placeholder="Contoh: 25000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Servis</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_servis" class="form-control" value="{{ $dataJadwal->km_servis }}" placeholder="Contoh: 3000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Ganti Oli</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_ganti_oli" class="form-control" value="{{ $dataJadwal->km_ganti_oli }}" placeholder="Contoh: 2000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan servis record ?')">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12 form-group">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Usulan</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 1%;">No</th>
                                    <th class="text-left" style="width: 25%;">Tanggal / No. Surat</th>
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
                                    <td class="text-center pt-3" style="width: 5vh;">{{ $no++ }}</td>
                                    <td class="text-left">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y | HH:mm') }} <br>
                                        {{ $dataUsulan->jenis_form_usulan }} <br>
                                        No. Surat :
                                        @if ($dataUsulan->status_pengajuan_id == 1)
                                        {{ strtoupper($dataUsulan->no_surat_usulan) }}
                                        @else
                                        -
                                        @endif
                                    </td>
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
                                            <span class="badge badge-sm badge-pill badge-warning">Sedang Diproses <br> oleh PPK</span>
                                            @elseif ($dataUsulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning">Konfirmasi Pekerjaan <br> telah Diterima</span>
                                            @elseif ($dataUsulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning">Menunggu Konfirmasi BAST <br> Kabag RT</span>
                                            @elseif ($dataUsulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">Selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center pt-4">
                                        <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->status_proses_id == 3 && $dataUsulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah pekerjaan telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($dataUsulan->otp_usulan_pengusul == null)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/aadb/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
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
                                                @if($dataUsulan->rencana_pengguna)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat Usulan </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('unit-kerja/surat/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($dataUsulan->status_proses_id == 5 && $dataUsulan->status_pengajuan_id == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Surat BAST </label></div>
                                                    <div class="col-md-8">:
                                                        <a href="{{ url('unit-kerja/surat/detail-bast-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
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
                                                            <div class="col-sm-1">{{ $dataPengadaan->jumlah_pengajuan }} Kendaraan</div>
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
                                                                {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }}
                                                                Jatuh Tempo Servis : <br>
                                                                {{ (int) $dataServis->jatuh_tempo_servis }} Km

                                                            </div>
                                                            <div class="col-sm-2">
                                                                Terakhir Ganti Oli : <br>
                                                                {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }}
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

<section class="content text-capitalize form-group">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-12 col-12 form-group">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <h3 class="card-title mt-1 font-weight-bold">Daftar Alat Angkutan Darat Bermotor (AADB)</h3>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Jenis AADB</label> <br>
                                    <select id="jenis_aadb" class="form-control" name="jenis_aadb" style="width: 100%;">
                                        <option value="">-- JENIS AADB --</option>
                                        <option value="sewa">SEWA</option>
                                        <option value="bmn">BMN</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Nama Kendaraan</label> <br>
                                    <select name="jenis_kendaraan" id="kendaraan`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- NAMA KENDARAAN --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('unit-kerja/aadb/dashboard') }}" class="btn btn-danger">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="notif-konten-chart"></div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card-body border border-default">
                                <div id="konten-chart-google-chart">
                                    <div id="piechart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="card-body border border-default">
                                <table id="table-aadb" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            <th>Pengguna</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->kendaraan as $dataAadb)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <b class="text-primary">{{ $dataAadb->kode_barang.'.'.$dataAadb->nup_barang }}</b> <br>
                                                {{ $dataAadb->merk_tipe_kendaraan.' '.$dataAadb->tahun_kendaraan }} <br>
                                                @if ($dataAadb->no_plat_kendaraan != null)
                                                {{ $dataAadb->no_plat_kendaraan }} <br>
                                                @endif
                                                {{ $dataAadb->jenis_aadb }} <br>

                                                @if ($dataAadb->kualifikasi != null)
                                                Kendaraan {{ $dataAadb->kualifikasi }}
                                                @endif
                                            </td>
                                            <td>
                                                No. BPKB : {{ $dataAadb->no_bpkb }} <br>
                                                No. Rangka : {{ $dataAadb->no_rangka }} <br>
                                                No. Mesin : {{ $dataAadb->no_mesin }} <br>
                                                Masa Berlaku STNK : <br>
                                                @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)) < 365 && $dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-danger">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @elseif ($dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-success">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @endif<br>
                                            </td>
                                            <td>
                                                Unit Kerja : {{ $dataAadb->unit_kerja }} <br>
                                                Pengguna : {{ $dataAadb->pengguna }} <br>
                                                Jabatan : {{ $dataAadb->jabatan }} <br>
                                                Pengemudi : {{ $dataAadb->pengemudi }}
                                            </td>
                                            <td class="text-center">
                                                @if ($dataAadb->status_kendaraan_id == 1)
                                                <span class="badge badge-sm badge-pill badge-success">Aktif</span>
                                                @elseif ($dataAadb->status_kendaraan_id == 2)
                                                <span class="badge badge-sm badge-pill badge-warning">Perbaikan</span>
                                                @elseif ($dataAadb->status_kendaraan_id == 3)
                                                <span class="badge badge-sm badge-pill badge-warning">Proses Penghapusan</span>
                                                @else
                                                <span class="badge badge-sm badge-pill badge-danger">Sudah Dihapuskan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ url('unit-kerja/aadb/kendaraan/detail/'. $dataAadb->id_kendaraan) }}">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Servis</h6>
                        </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Perpanjangan STNK</h6>
                        </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Voucher BBM</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
	var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()

        $('#jenis_aadb').select2();
        $('.dataTables_filter input[type="search"]').css({
            'width': '50px',
            'display': 'inline-block'
        });

        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
            buttons: [{
                    text: '(+) Usulan Pengadaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/aadb/usulan/pengadaan/baru') }}";
                    }
                },
                {
                    text: '(+) Usulan Pemeliharaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        $('#upload').modal('show');
                    }
                }
            ]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');

        $("#table-servis").DataTable({
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

        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
	    buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Kendaraan AADB',
		    exportOptions: {
                        columns: [0,1,2,3]
		    },
                    messageTop: datetime
            }],
        }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');

        let j = 0

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
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

    // Chart
    let chart
    let chartData = JSON.parse(`<?php echo $googleChartData; ?>`)
    let dataChart = chartData.all
    google.charts.load('current', {
        'packages': ['corechart']
    })
    google.charts.setOnLoadCallback(function() {
        drawChart(dataChart)
    })

    function drawChart(dataChart) {

        chartData = [
            ['Jenis Kendaraan', 'Jumlah']
        ]

        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Kendaraan',
            titlePosition: 'none',
            is3D: false,
            legend: {
                'position': 'top',
                'alignment': 'center',
                'maxLines': '5'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || jenis_kendaraan) {
            url = '<?= url("/unit-kerja/aadb/grafik?jenis_aadb='+jenis_aadb+'&jenis_kendaraan='+jenis_kendaraan+'") ?>'
        } else {
            url = '<?= url("/unit-kerja/aadb/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                let dataTable = $('#table-aadb').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTable.clear()
                    dataTable.draw()
                    let no = 1

                    data.table.forEach(element => {
                        let noplat = element.no_plat_kendaraan != null ? '<br> <span class="text-uppercase">' + element.no_plat_kendaraan + '</span>' : ''
                        let kualifikasi = element.kualifikasi != null ? '<br> <span class="text-capitalize">Kendaraan ' + element.kualifikasi + '</span>' : ''
                        dataTable.row.add([
                            no++,
                            '<b class="text-primary">' + element.kode_barang + '.' + element.nup_barang + '</b>' +
                            '<br>' + element.merk_tipe_kendaraan + ' ' + element.tahun_kendaraan +
                            noplat +
                            '<br>' + element.jenis_aadb +
                            kualifikasi,
                            'No. BPKB :' + element.no_bpkb +
                            '<br> No. Rangka :' + element.no_rangka +
                            '<br> No. Mesin :' + element.no_mesin +
                            '<br> Masa Berlaku STNK :' + element.mb_stnk_plat_kendaraan,
                            'Unit Kerja :' + element.unit_kerja +
                            '<br> Pengguna :' + element.pengguna +
                            '<br> Jabatan :' + element.jabatan +
                            '<br> Pengemudi :' + element.pengemudi,
                            element.status_kendaraan_id == 1 ? '<span class="badge badge-sm badge-pill badge-success">Aktif</span>' :
                            (element.status_kendaraan_id == 2 ? '<span class="badge badge-sm badge-pill badge-warning">Perbaikan</span>' :
                                (element.status_kendaraan_id == 3 ? '<span class="badge badge-sm badge-pill badge-warning">Proses Penghapusan</span>' :
                                    '<span class="badge badge-sm badge-pill badge-danger">Sudah Dihapuskan</span>')),
                            `<td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('unit-kerja/aadb/kendaraan/detail/` + element.id_kendaraan + `') }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>`
                        ]).draw(false)
                    })

                } else {
                    dataTable.clear()
                    dataTable.draw()
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').hide();
                    var html = ''
                    html += '<div class="notif-tidak-ditemukan">'
                    html += '<div class="card bg-secondary py-4">'
                    html += '<div class="card-body text-white">'
                    html += '<h5 class="mb-4 font-weight-bold text-center">'
                    html += 'Data tidak dapat ditemukan'
                    html += '</h5>'
                    html += '</div>'
                    html += '</div>'
                    html += '</div>'
                    $('#notif-konten-chart').append(html)
                }
            },
        })
    })
</script>
@endsection

@endsection
