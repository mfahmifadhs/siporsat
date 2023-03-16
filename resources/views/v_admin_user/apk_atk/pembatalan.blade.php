@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Tulis Kantor (ATK)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Penyerahan ATK</li>
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
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title pt-1">Penyerahan ATK </h3>
                <div class="card-tools">
                    <a href="{{ url('admin-user/cetak-surat/penyerahan-atk/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger btn-sm pdf">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>
            <form action="{{ url('admin-user/atk/usulan/batal-permintaan/'. $permintaan->id_permintaan) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Pengusul</label>
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Jabatan</label>
                            <input type="text" class="form-control" value="{{ $usulan->keterangan_pegawai }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Bulan Pengadaan</label>
                            <input type="text" class="form-control" value="{{ $usulan->rencana_pengguna }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Nomor Surat Usulan</label>
                            <input type="text" class="form-control" value="{{ $usulan->no_surat_usulan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-12 col-form-label text-muted mb-2">Informasi Barang</label>
                        <div class="col-md-6 col-12 mt-3">
                            <label class="col-form-label">Nama Barang</label>
                            <input type="text" class="form-control" value="{{ $permintaan->nama_barang }}" readonly>
                        </div>
                        <div class="col-md-6 col-12 mt-3">
                            <label class="col-form-label">Merk/Tipe</label>
                            <input type="text" class="form-control" value="{{ $permintaan->spesifikasi }}" readonly>
                        </div>
                        <div class="col-md-6 col-12 mt-3">
                            <label class="col-form-label">Jumlah yang dibatalkan</label>
                            <input type="number" class="form-control" name="jumlah_batal"
                            value="{{ (int) $permintaan->jumlah_disetujui - (int) $permintaan->jumlah_penyerahan }}"
                            max="{{ (int) $permintaan->jumlah_disetujui - (int) $permintaan->jumlah_penyerahan }}">
                        </div>
                        <div class="col-md-6 col-12 mt-3">
                            <label class="col-form-label">Satuan</label>
                            <input type="text" class="form-control" value="{{ $permintaan->satuan }}" readonly>
                        </div>
                        <div class="col-md-12 col-12 mt-3">
                            <label class="col-form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" placeholder="Keterangan Penolakan"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-danger font-weight-bold" id="btnSubmit" onclick="return confirm('Membatalkan Permintaan ?')">
                            <i class="fas fa-times-circle"></i> Batalkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        $('#selectAll').click(function() {
            if ($(this).prop('checked')) {
                $('.confirm-check').prop('checked', true);
            } else {
                $('.confirm-check').prop('checked', false);
            }
        })
    })
</script>
@endsection

@endsection
