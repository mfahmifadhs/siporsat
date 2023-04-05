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
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title pt-1">Validasi Permintaan ATK</h3>
                    </div>
                    <form action="{{ url('admin-user/atk/usulan/validasi/'. $usulan->id_form_usulan) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label class="col-form-label">Nomor Usulan</label>
                                    <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ $usulan->no_surat_usulan }}" readonly>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="col-form-label">Tanggal Usulan</label>
                                    <input type="text" class="form-control" name="tanggal_usulan" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
                                </div>
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
                                    <label class="col-form-label">Jumlah Belum Diserahkan </label>
                                    <input type="text" class="form-control" value="{{ $usulan->total_pengajuan }} Barang" readonly>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <label class="col-sm-12 col-form-label text-muted mb-2">Informasi Barang</label>
                                <small class="col-sm-12 mb-2">
                                    ▪️ Jika Barang <b>Disetujui</b>, silahkan di centang (✔️) <br>
                                    ▪️ Jika Barang <b>Ditolak</b>, kolom centang dikosongkan 🔳 <br>
                                    ▪️ Jika Barang <b>Disetujui, namun jumlah permintaan diubah</b>, silahkan pilih centang (✔️) <br>
                                    ▪ <span class="text-danger">Barang yang tidak di centang (✔️) akan dianggap ditolak</span>
                                </small>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th style="width: 0%;" class="text-center">No</th>
                                                <th>Nama Barang</th>
                                                <th>Catatan</th>
                                                @if ($usulan->jenis_form == 'permintaan')
                                                <th style="width: 10%;">Stok Gudang</th>
                                                @endif
                                                <th style="width: 15%;" class="text-center">Permintaan</th>
                                                <th style="width: 10%;">Satuan</th>
                                                <th style="width: 15%;">Keterangan</th>
                                                <th style="width: 8%;" class="text-center">
                                                    <input type="checkbox" id="selectAll" style="scale: 1.7;">
                                                </th>
                                            </tr>
                                        </thead>
                                        <?php $no = 1; ?>
                                        <tbody>
                                            @if ($usulan->jenis_form == 'permintaan')
                                            @foreach ($usulan->permintaan2Atk as $i => $dataAtk)
                                            <tr>
                                                <td class="text-center"> {{ $no++ }}</td>
                                                <td>
                                                    <input type="hidden" name="modul" value="permintaan">
                                                    <input type="hidden" name="id_permintaan[{{$i}}]" value="{{ $dataAtk->id_permintaan }}">
                                                    {{ $dataAtk->deskripsi_barang }}
                                                </td>
                                                <td>{{ $dataAtk->catatan }}</td>
                                                <td>
                                                    @php
                                                    $masuk = $dataAtk->atk->riwayatTransaksi->where('status_riwayat', 'masuk')->first();
                                                    $keluar = $dataAtk->atk->riwayatTransaksi->where('status_riwayat', 'keluar')->first();
                                                    $stok = $masuk['stok'] - $keluar['stok'];
                                                    @endphp

                                                    {{ (int) $stok.' '.$dataAtk->satuan_barang }}
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="permintaanAtk[]" class="form-control input-border-bottom" value="{{ $dataAtk->jumlah }}" max="{{ $dataAtk->jumlah }}">
                                                </td>
                                                <td>{{ $dataAtk->satuan_barang }}</td>
                                                <td>
                                                    <input type="text" name="keterangan[]" class="form-control input-border-bottom text-left">
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" value="" name="status_pengajuan[{{$i}}]">
                                                    <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            @foreach ($usulan->permintaanAtk as $i => $dataAtk)
                                            <tr>
                                                <td class="text-center"> {{ $no++ }}</td>
                                                <td>
                                                    <input type="hidden" name="modul" value="distribusi">
                                                    <input type="hidden" name="id_permintaan[{{$i}}]" value="{{ $dataAtk->id_permintaan }}">
                                                    <input type="hidden" name="id_pengadaan[{{$i}}]" value="{{ $dataAtk->pengadaan_id }}">
                                                    {{ $dataAtk->nama_barang }}
                                                </td>
                                                <td>{{ $dataAtk->spesifikasi }}</td>
                                                <td class="text-center">
                                                    <input type="number" name="permintaanAtk[]" class="form-control input-border-bottom" value="{{ $dataAtk->jumlah }}" max="{{ $dataAtk->jumlah }}">
                                                </td>
                                                <td class="text-center">{{ $dataAtk->satuan }}</td>
                                                <td><input type="text" name="keterangan[]" class="form-control input-border-bottom text-left"></td>
                                                <td class="text-center">
                                                    <input type="hidden" value="" name="status_pengajuan[{{$i}}]">
                                                    <input type="checkbox" class="confirm-check" style="scale: 1.7;" name="status_pengajuan[{{$i}}]" id="checkbox_id{{$i}}" value="true">
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Usulan yang sudah di validasi tidak dapat diubah, apakah proses validasi sudah selesai?')">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
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
