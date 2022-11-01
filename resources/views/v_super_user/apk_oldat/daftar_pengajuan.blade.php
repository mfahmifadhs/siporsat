@extends('v_super_user.layout.app')

@section('content')

<?php $user = Auth()->user();
$pegawai = $user->pegawai;
$jabatan = $user->jabatan; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Usulan Pengajuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan Oldat</li>
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
        </div>
        <div class="card">
            <div class="card-body">
                <table id="table-pengajuan" class="table table-bordered text-capitalize text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Usulan</th>
                            <th>Pengusul</th>
                            <th>Usulan Pengajuan</th>
                            <th>Total Pengajuan</th>
                            <th>Rencana Pengguna</th>
                            <th>Status Pengajuan</th>
                            <th>Status Usulan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($formUsulan as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>{{ $row->jenis_form }} barang</td>
                            <td>{{ $row->total_pengajuan }} barang</td>
                            <td>{{ $row->rencana_pengguna }}</td>
                            <td class="text-center">
                                @if($row->status_pengajuan_id == 1)
                                <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                @elseif($row->status_pengajuan_id == 2)
                                <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status_proses_id == 1)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                @elseif ($row->status_proses_id == 2)
                                <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                @elseif ($row->status_proses_id == 3)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                @elseif ($row->status_proses_id == 4)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi kabag rt</span>
                                @elseif ($row->status_proses_id == 5)
                                <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                @endif
                            </td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>

                                <div class="dropdown-menu">
                                    @if (Auth::user()->pegawai->jabatan_id == 2 && $row->status_proses_id == 1)
                                    <a class="dropdown-item btn" href="{{ url('super-user/oldat/pengajuan/persetujuan/'. $row->id_form_usulan) }}">
                                        <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                    </a>
                                    @elseif (Auth::user()->pegawai->jabatan_id == 5 && $row->status_proses_id == 2)
                                    <a class="dropdown-item btn" href="{{ url('super-user/ppk/oldat/pengajuan/'. $row->jenis_form.'/'. $row->id_form_usulan) }}">
                                        <i class="fas fa-arrow-alt-circle-right"></i> Proses Penyerahan
                                    </a>
                                    @elseif ($row->status_proses_id == 4)
                                    <a class="dropdown-item btn" href="{{ url('super-user/oldat/surat/surat-bast/'. $row->id_form_usulan) }}">
                                        <i class="fas fa-arrow-alt-circle-right"></i> BAST
                                    </a>
                                    @endif
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $row->id_form_usulan }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-info-{{ $row->id_form_usulan }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        @if($row->status_pengajuan_id == '')
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu persetujuan</b>
                                        </span>
                                        @elseif ($row->status_pengajuan_id == 1)
                                        @if($row->status_proses_id == 2)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">usulan sedang diproses</b>
                                        </span>
                                        @elseif($row->status_proses_id == 3)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi pengusul</b>
                                        </span>
                                        @elseif($row->status_proses_id == 4)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi kabag rt</b>
                                        </span>
                                        @elseif($row->status_proses_id == 5)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">selesai</b>
                                        </span>
                                        @endif
                                        @endif
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-capitalize">
                                        <div class="form-group row">
                                            <div class="col-md-12 text-center">
                                                <h5>Detail Pengajuan Usulan {{ $row->jenis_form_usulan }}
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
                                            <div class="col-md-8">: {{ $row->nama_pegawai }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                            <div class="col-md-8">: {{ $row->jabatan.' '.$row->keterangan_pegawai }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Unit Kerja</label></div>
                                            <div class="col-md-8">: {{ $row->unit_kerja }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                            <div class="col-md-8">: {{ \Carbon\Carbon::parse($row->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                        </div>
                                        @if($row->jenis_form == 'pengadaan')
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                            <div class="col-md-8">: {{ $row->rencana_pengguna }}</div>
                                        </div>
                                        @else
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Biaya Perbaikan :</label></div>
                                            <div class="col-md-8">: Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</div>
                                        </div>
                                        @endif
                                        <div class="form-group row mt-4">
                                            <h6 class="col-md-12 font-weight-bold text-muted">
                                                Informasi Barang
                                            </h6>
                                        </div>
                                        @if($row->jenis_form == 'pengadaan')
                                        @foreach($row->detailPengadaan as $dataPengadaan )
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4"><label>Nama Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->kategori_barang }}</div>
                                                <div class="col-md-4"><label>Merk </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->merk_barang }}</div>
                                                <div class="col-md-4"><label>Jumlah </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->jumlah_barang.' '.$dataPengadaan->satuan_barang }}</div>
                                                <div class="col-md-4"><label>Estimasi Biaya</label></div>
                                                <div class="col-md-8">: Rp {{ number_format($dataPengadaan->estimasi_biaya, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        @foreach($row->detailPerbaikan as $dataPerbaikan)
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4"><label>Nama Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->kategori_barang }}</div>
                                                <div class="col-md-4"><label>Merk Barang </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->merk_tipe_barang }}</div>
                                                <div class="col-md-4"><label>Pengguna </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->nama_pegawai }}</div>
                                                <div class="col-md-4"><label>Unit Kerja</label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->unit_kerja }}</div>
                                                <div class="col-md-4"><label>Tahun Perolehan</label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->tahun_perolehan }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <div class="col-md-12">
                                            <span style="float: left;">
                                                @if($row->status_proses_id == 5)
                                                <a href="{{ url('super-user/oldat/surat/surat-bast/'. $row->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat BAST
                                                </a>
                                                @endif
                                            </span>
                                            <span style="float: right;">
                                                <a href="{{ url('super-user/oldat/surat/surat-usulan/'. $row->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                </a>
                                            </span>
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
</section>

@section('js')
<script>
    $(function() {
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-workunit_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
