@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Usulan Pengajuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}"> Dashboard</a></li>
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
                <table id="table-pengajuan" class="table table-bordered text-capitalize text-center small">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Usulan</th>
                            <th>Pengusul</th>
                            <th>Usulan Pengajuan</th>
                            <th>Total Pengajuan</th>
                            <th>Rencana Pengguna</th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach($usulan as $dataUsulan)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                            <td>{{ $dataUsulan->nama_pegawai }}</td>
                            <td>{{ $dataUsulan->jenis_form }} barang</td>
                            <td>{{ $dataUsulan->total_pengajuan }} barang</td>
                            <td>{{ $dataUsulan->rencana_pengguna }}</td>
                            <td class="text-center">
                                @if ($dataUsulan->lampiran != null)
                                <span>Bukti Kwitansi : </span> <br>
                                <a href="{{ asset('gambar/kwitansi/oldat_perbaikan/'. $dataUsulan->lampiran) }}" class="btn btn-primary btn-xs" download>
                                    <i class="fas fa-download"></i> Unduh
                                </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <span>Status Pengajuan : </span> <br>
                                @if($dataUsulan->status_proses_id == 1)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                @elseif ($dataUsulan->status_proses_id == 2)
                                <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                @elseif ($dataUsulan->status_proses_id == 3)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                @elseif ($dataUsulan->status_proses_id == 4)
                                <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi kabag rt</span>
                                @elseif ($dataUsulan->status_proses_id == 5)
                                <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                @endif <br>
                                <span>Status Proses</span> <br>
                                @if($dataUsulan->status_pengajuan_id == 1)
                                <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                @elseif($dataUsulan->status_pengajuan_id == 2)
                                <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                    <a class="dropdown-item btn" href="{{ url('super-admin/oldat/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin menghapus usulan ini ?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        @if($dataUsulan->status_pengajuan_id == '')
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu persetujuan</b>
                                        </span>
                                        @elseif ($dataUsulan->status_pengajuan_id == 1)
                                        @if($dataUsulan->status_proses_id == 2)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">usulan sedang diproses</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 3)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi pengusul</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 4)
                                        <span class="border border-warning">
                                            <b class="text-warning p-3">menunggu konfirmasi kabag rt</b>
                                        </span>
                                        @elseif($dataUsulan->status_proses_id == 5)
                                        <span class="border border-success">
                                            <b class="text-success p-3">selesai</b>
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
                                                <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form_usulan }}
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
                                            <div class="col-md-8">: {{ $dataUsulan->jabatan.' '.$dataUsulan->keterangan_pegawai }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Unit Kerja</label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->unit_kerja }}</div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                            <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                        </div>
                                        @if($dataUsulan->jenis_form == 'pengadaan')
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                            <div class="col-md-8">: {{ $dataUsulan->rencana_pengguna }}</div>
                                        </div>
                                        @else
                                        <div class="form-group row mb-0">
                                            <div class="col-md-4"><label>Biaya Perbaikan :</label></div>
                                            <div class="col-md-8">: Rp {{ number_format($dataUsulan->total_biaya, 0, ',', '.') }}</div>
                                        </div>
                                        @endif
                                        <div class="form-group row mt-4">
                                            <h6 class="col-md-12 font-weight-bold text-muted">
                                                Informasi Barang
                                            </h6>
                                        </div>
                                        @if($dataUsulan->jenis_form == 'pengadaan')
                                        @foreach($dataUsulan->detailPengadaan as $dataPengadaan )
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
                                        @foreach($dataUsulan->detailPerbaikan as $dataPerbaikan)
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
                                                @if($dataUsulan->status_proses_id == 5)
                                                <a href="{{ url('super-admin/oldat/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat BAST
                                                </a>
                                                @endif
                                            </span>
                                            <span style="float: right;">
                                                <a href="{{ url('super-admin/oldat/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
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
