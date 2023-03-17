@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0">Berita Acara Serah Terima</h1>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/'.$modul.'/dashboard') }}">Dashboard {{ $modul }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/'.$modul.'/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Berita Acara Serah Terima</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
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
                <a href="{{ url('admin-user/atk/usulan/daftar/seluruh-usulan') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Berita Acara Serah Terima</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group row">
                                    <label class="col-md-4">Tanggal Bast</label>
                                    <div class="col-md-8">: {{ \Carbon\carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                    <label class="col-md-4">Nomor Surat</label>
                                    <div class="col-md-8 text-capitalize">: {{ $bast->nomor_bast }}</div>
                                    <label class="col-md-4">Jenis Usulan</label>
                                    <div class="col-md-8 text-capitalize">: {{ $bast->jenis_form.' '.$modul }}</div>
                                    <label class="col-md-4">Status</label>
                                    <div class="col-md-8">:
                                        @if (!$bast->otp_bast_ppk)
                                        Menunggu Konfirmasi PPK
                                        @elseif (!$bast->otp_bast_pengusul)
                                        Menunggu Konfirmasi Pengusul
                                        @elseif (!$bast->otp_bast_kabag)
                                        Menunggu Konfirmasi Kabag RT
                                        @else
                                        Sudah Selesai BAST
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-6">
                                <div class="form-group row text-capitalize">
                                    <label class="col-md-4">Nama Pengusul</label>
                                    <div class="col-md-8">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                                    <label class="col-md-4">Jabatan Pengusul</label>
                                    <div class="col-md-8">: {{ $bast->keterangan_pegawai }}</div>
                                    <label class="col-md-4">Unit Kerja</label>
                                    <div class="col-md-8">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                    <label class="col-md-4">Status</label>
                                    <div class="col-md-8">:
                                        @if (!$bast->otp_bast_ppk)
                                        Menunggu Konfirmasi PPK
                                        @elseif (!$bast->otp_bast_pengusul)
                                        Menunggu Konfirmasi Pengusul
                                        @elseif (!$bast->otp_bast_kabag)
                                        Menunggu Konfirmasi Kabag RT
                                        @else
                                        Sudah Selesai BAST
                                        @endif
                                    </div>
                                    <label class="col-md-4">Aksi</label>
                                    <div class="col-md-8">:
                                        <a href="{{ url('admin-user/cetak-surat/bast-'.$modul.'/'. $bast->id_bast) }}" class="btn btn-danger btn-sm" rel="noopener" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-md-12 col-12">
                                <label class="text-muted">Informasi Barang/Pekerjaan</label>
                                <table class="table table-bordered">
                                    @if ($modul == 'atk')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Permintaan</th>
                                            <th>Penyerahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bast->detailBast as $i => $detailAtk)
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->nama_barang)) }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->spesifikasi)) }}</td>
                                            <td>{{ $detailAtk->jumlah_disetujui.' '.$detailAtk->satuan }}</td>
                                            <td>{{ $detailAtk->jumlah_bast_detail.' '.$detailAtk->satuan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
                        </div>
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
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
        $("#table-alkom").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
    })
</script>

@endsection
@endsection
