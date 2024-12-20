@extends('v_user.layout.app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0">Detail Usulan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('ukt.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('ukt.show', ['aksi' => 'pengajuan', 'id' => '*']) }}"> Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div>
            <div class="col-sm-6 text-right my-auto">
                @if (!$usulan->otp_usulan_kabag && $usulan->status_pengajuan_id != 2)
                <a href="{{ route('ukt.edit', $id) }}" class="btn btn-default border-dark btn-sm pdf">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endif
                @if($usulan->otp_usulan_pengusul)
                <a href="{{ url('unit-kerja/cetak-surat/usulan-ukt/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-default border-dark btn-sm pdf">
                    <i class="fas fa-file-alt"></i> Surat Usulan
                </a>
                @endif
                @if($usulan->otp_usulan_kabag)
                <a href="{{ url('unit-kerja/cetak-surat/bast-ukt/'. $usulan->id_form_usulan) }}" class="btn btn-default border-dark btn-sm pdf" target="_blank">
                    <i class="fas fa-file-alt"></i> Berita Acara
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @elseif ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-center font-weight-bold pt-2">
                            Detail Usulan
                        </h5>
                    </div>
                    <div class="card-body border border-dark">
                        <form action="{{ url('unit-kerja/atk/usulan/preview-pengadaan/preview') }}">
                            <div class="row">
                                <div class="col-md-7 col-12 text-sm">
                                    <div class="form-group row">
                                        <div class="col-md-3">Tanggal</div>
                                        <div class="col-md-9">:
                                            {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                                        </div>
                                        <div class="col-md-3">Hal</div>
                                        <div class="col-md-9 text-capitalize">:
                                            pemeliharaan gedung dan bangunan
                                        </div>
                                        <div class="col-md-3">Nomor Surat</div>
                                        <div class="col-md-9 text-uppercase">:
                                            {{ $usulan->no_surat_usulan }}
                                        </div>
                                        <div class="col-md-3">Pengusul</div>
                                        <div class="col-md-9">: {{ $usulan->pegawai->nama_pegawai }}</div>
                                        <div class="col-md-3">Jabatan</div>
                                        <div class="col-md-9">: {{ $usulan->pegawai->keterangan_pegawai }}</div>
                                        <div class="col-md-3">Unit Kerja</div>
                                        <div class="col-md-9">: {{ ucwords(strtolower($usulan->pegawai->unitKerja->unit_kerja)) }}</div>
                                        <div class="col-md-3">Jumlah</div>
                                        <div class="col-md-9">: {{ $usulan->detailUsulanUkt?->count() }} pekerjaan</div>
                                        @if($usulan->rencana_pengguna != null)
                                        <div class="col-md-3">Keterangan</div>
                                        <div class="col-md-9">: {{ $usulan->rencana_pengguna }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5 col-12 text-right">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            @if($usulan->status_pengajuan_id == 1)
                                            <span class="badge badge-sm badge-success p-2">
                                                Usulan Disetujui
                                            </span>
                                            @elseif($usulan->status_pengajuan_id == 2)
                                            <span class="badge badge-sm badge-danger p-2">Usulan Ditolak</span>
                                            @if ($usulan->keterangan != null)
                                            <p class="small mt-2 text-danger p-2">{{ $usulan->keterangan }}</p>
                                            @endif
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <h6 class="mt-2">
                                                @if($usulan->status_proses_id == 1)
                                                <span class="badge badge-sm badge-warning p-2">Menunggu Persetujuan Kabag RT</span>
                                                @elseif ($usulan->status_proses_id == 2)
                                                <span class="badge badge-sm badge-warning p-2">Sedang Diproses oleh PPK</span>
                                                @elseif ($usulan->status_proses_id == 3)
                                                <span class="badge badge-sm badge-warning p-2">Konfirmasi Pekerjaan telah Diterima</span>
                                                @elseif ($usulan->status_proses_id == 4)
                                                <span class="badge badge-sm badge-warning p-2">Menunggu Konfirmasi BAST Kabag RT</span>
                                                @elseif ($usulan->status_proses_id == 5)
                                                <span class="badge badge-sm badge-success p-2">Selesai</span>
                                                @endif
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 mt-4 mb-5">
                                <table class="table table-bordered m-0 table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%;">No</th>
                                            <th style="width: 20%;">Pekerjaan</th>
                                            <th>Spesifikasi Pekerjaan</th>
                                            <th style="width: 15%;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($usulan->detailUsulanUkt as $dataUkt)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                            <td>{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</td>
                                            <td>{{ $dataUkt->keterangan }}</td>
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


@endsection
