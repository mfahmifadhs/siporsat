@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h4 class="m-0">Pemeliharaan Gedung / Bangunan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/gdn/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/gdn/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Proses Persetujuan</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container">
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
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Proses Persetujuan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/gdn/usulan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ $usulan->no_surat_usulan }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pengusul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->total_pengajuan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">Informasi Kerusakan</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 1%;">No</th>
                                        <th style="width: 20%;">Bidang Kerusakan</th>
                                        <th style="width: 20%;">Lokasi Perbaikan</th>
                                        <th>Lokasi Spesifik</th>
                                        <th style="width: 20%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->detailUsulanGdn as $i => $dataPerbaikan)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="text-uppercase">{{ $dataPerbaikan->lokasi_bangunan}}</td>
                                        <td>{!! nl2br(e($dataPerbaikan->lokasi_spesifik)) !!}</td>
                                        <td>{{ $dataPerbaikan->bid_kerusakan }}</td>
                                        <td>{!! nl2br(e($dataPerbaikan->keterangan)) !!}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-success" id="btnSubmit" onclick="return confirm('Pengajuan Diterima ?')">
                                <i class="fas fa-check-circle"></i> Terima
                            </button>
                            <a class="btn btn-danger" data-toggle="modal" data-target="#tolakUsulan">
                                <i class="fas fa-times-circle"></i> Tolak
                            </a>
                        </div>
                    </div>
                </form>
                <!-- Modal -->
                <div class="modal fade" id="tolakUsulan" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ url('super-user/gdn/usulan/proses-ditolak/'. $usulan->id_form_usulan) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label class="col-form-label">Keterangan Penolakan</label>
                                    <textarea name="keterangan" class="form-control" id="" cols="30" rows="10"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Pengajuan Ditolak ?')">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
