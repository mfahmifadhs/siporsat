@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Tulis Kantor</h1>
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

@foreach($usulan->permintaanAtk->where('status','diterima') as $i => $dataPermintaan)
@php
$permintaan = $dataPermintaan->jumlah_disetujui;
$belum_diserahkan = $dataPermintaan->jumlah_disetujui - $dataPermintaan->jumlah_penyerahan;
@endphp
@if ($belum_diserahkan != 0)
@php $itemTotal[] = $dataPermintaan->id_permintaan ; @endphp
@endif
@endforeach

<section class="content">
    <div class="container">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @elseif ($message = Session::get('failed'))
        <div class="alert alert-danger">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @endif
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title pt-1">Penyerahan ATK </h3>
                <div class="card-tools">
                    <a href="{{ url('admin-user/cetak-surat/penyerahan-atk/'. $usulan->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger btn-sm pdf">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
            </div>
            <form action="{{ url('admin-user/atk/usulan/proses-edit/'. $usulan->id_form_usulan) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Nomor BAST</label>
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.04/2/'.$idBast.'/'.Carbon\carbon::now()->isoFormat('Y') }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Tanggal BAST</label>
                            <input type="date" class="form-control" name="tanggal_bast" required>
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
                            <input type="text" class="form-control" value="{{ count($itemTotal) }} Barang" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-12 col-form-label text-muted mb-2">Informasi Barang</label>
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width: 0%;" class="text-center">No</th>
                                        <th style="width: 10%;" class="text-center">Jenis Barang</th>
                                        <th>Deskripsi</th>
                                        <th style="width: 10%;" class="text-center">Permintaan</th>
                                        <th style="width: 13%;" class="text-center">Belum Diserahkan</th>
                                        <th style="width: 0%;" class="text-center">Satuan</th>
                                        <th style="width: 15%;" class="text-center">Diserahkan</th>
                                        <th style="width: 0%;" class="text-center">Satuan</th>
                                        <th style="width: 0%;" class="text-center">Status</th>
                                        <!-- <th>
                                            Diserahkan <br>
                                            <input type="checkbox" id="selectAll">
                                        </th> -->
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->permintaanAtk->where('status','diterima') as $i => $dataPermintaan)
                                    @php
                                    $permintaan = $dataPermintaan->jumlah_disetujui;
                                    $belum_diserahkan = $dataPermintaan->jumlah_disetujui - $dataPermintaan->jumlah_penyerahan;
                                    @endphp
                                    @if ($belum_diserahkan != 0)
                                    <tr>
                                        <td class="text-center"> {{ $no++ }}</td>
                                        <td class="text-center">
                                            <input type="hidden" name="modul" value="distribusi">
                                            <input type="hidden" name="id_permintaan[{{$i}}]" value="{{ $dataPermintaan->id_permintaan }}">
                                            {{ $dataPermintaan->jenis_barang }}
                                        </td>
                                        <td>{{ $dataPermintaan->nama_barang.' '.$dataPermintaan->spesifikasi }}</td>
                                        <td class="text-center">{{ $permintaan }}</td>
                                        <td class="text-center">{{ $belum_diserahkan }}</td>
                                        <td class="text-center">{{ $dataPermintaan->satuan }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center" name="jumlah_penyerahan[{{$i}}]" value="{{ $belum_diserahkan }}" oninput="this.value = Math.abs(this.value)" max="{{ $belum_diserahkan }}">
                                        </td>
                                        <td class="text-center">{{ $dataPermintaan->satuan }}</td>
                                        <td class="text-center">
                                            <a href="{{ url('admin-user/atk/usulan/pembatalan/'. $dataPermintaan->id_permintaan) }}" class="btn btn-danger" rel="noopener" target="_blank">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                        </td>
                                        <!-- Modal -->
                                        <div class="modal fade" id="deleteItem-{{ $dataPermintaan->id_permintaan }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ url('admin-user/atk/usulan/batal-permintaan/'. $dataPermintaan->id_permintaan) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group row">
                                                                <div class="col-md-5 col-form-label">Nama Barang</div>
                                                                <div class="col-md-7 col-form-label">Merk/Tipe</div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-5 col-form-label">{{ $dataPermintaan->nama_barang }}</div>
                                                                <div class="col-md-7 col-form-label">{{ $dataPermintaan->spesifikasi }}</div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-md-9 col-form-label">Jumlah yang dibatalkan</div>
                                                                <div class="col-md-3 col-form-label">Satuan</div>
                                                                <div class="col-md-9 col-form-label">
                                                                    <input type="number" class="form-control" name="jumlah_batal" value="{{ (int) $dataPermintaan->jumlah_disetujui - (int) $dataPermintaan->jumlah_penyerahan }}" max="{{ (int) $dataPermintaan->jumlah_disetujui - (int) $dataPermintaan->jumlah_penyerahan }}">
                                                                </div>
                                                                <div class="col-md-3 col-form-label">
                                                                    <input type="text" class="form-control" value="{{ $dataPermintaan->satuan }}" readonly>
                                                                </div>
                                                                <div class="col-md-12 col-form-label">Keterangan</div>
                                                                <div class="col-md-12">
                                                                    <textarea class="form-control" name="keterangan" placeholder="Keterangan Penolakan"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Membatalkan Permintaan ?')">
                                                                <i class="fas fa-times-circle"></i> Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah barang sudah diserahkan ?')">
                            <i class="fas fa-paper-plane"></i> Menyerahkan
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
