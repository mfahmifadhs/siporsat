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
                    <li class="breadcrumb-item"><a href="#"> Dashboard OLDAT</a></li>
                    <li class="breadcrumb-item active">Dashboard OLDAT</li>
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
                                <span class="border border-success text-success p-1">disetujui</span>
                                @elseif($row->status_pengajuan_id == 2)
                                <span class="border border-danger text-danger p-1">ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($row->status_proses_id == 1)
                                <span class="border border-warning text-warning p-1">menunggu persetujuan</span>
                                @elseif ($row->status_proses_id == 2)
                                <span class="border border-warning text-warning p-1">usulan sedang diproses</span>
                                @elseif ($row->status_proses_id == 3)
                                <span class="border border-success text-success p-1">menunggu konfirmasi pengusul</span>
                                @elseif ($row->status_proses_id == 4)
                                <span class="border border-success text-success p-1">menunggu konfirmasi kabag rt</span>
                                @elseif ($row->status_proses_id == 5)
                                <span class="border border-success text-success p-1">selesai</span>
                                @endif
                            </td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $row->id_form_usulan }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-info-{{ $row->id_form_usulan }}">
                            <div class="modal-dialog">
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
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                            <div class="col-md-8">: {{ $row->rencana_pengguna }}</div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <h6 class="col-md-12 font-weight-bold text-muted">
                                                Informasi Kendaraan
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
                                                <div class="col-md-4"><label>Spesifikasi </label></div>
                                                <div class="col-md-8">: {{ $dataPengadaan->spesifikasi_barang }}</div>
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
                                                <div class="col-md-4"><label>Spesifikasi </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->spesifikasi_barang }}</div>
                                                <div class="col-md-4"><label>Jumlah </label></div>
                                                <div class="col-md-8">: {{ $dataPerbaikan->jumlah_barang.' '.$dataPerbaikan->satuan_barang }}</div>
                                                <div class="col-md-4"><label>Nilai Perolehan</label></div>
                                                <div class="col-md-8">: Rp {{ number_format($dataPerbaikan->nilai_perolehan, 0, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <div class="col-md-12">
                                            <span style="float: left;">
                                                @if($row->status_proses_id == 5)
                                                <a href="{{ url('super-user/oldat/surat/surat-bast/'. $row->otp_bast_ppk) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat BAST
                                                </a>
                                                @endif
                                            </span>
                                            <span style="float: right;">
                                                <a href="{{ url('super-user/oldat/surat/surat-usulan/'. $row->otp_usulan_pengusul) }}" class="btn btn-primary">
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
