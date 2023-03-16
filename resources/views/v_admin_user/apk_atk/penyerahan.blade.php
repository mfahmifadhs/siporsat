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
                    <li class="breadcrumb-item active">Proses Persetujuan Usulan ATK</li>
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
                <h3 class="card-title">Penyerahan ATK </h3>
            </div>
            <form action="{{ url('admin-user/atk/usulan/penyerahan/'. $usulan->id_form_usulan) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Nomor BAST</label>
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.04/2/'.$idBast.'/'.Carbon\carbon::now()->isoFormat('Y') }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Tanggal BAST</label>
                            @if ($usulan->tanggal_bast)
                            <input type="date" class="form-control" name="tanggal_bast" value="{{ \Carbon\carbon::parse($usulan->tanggal_bast)->isoFormat('DD MMMM') }}">
                            @else
                            <input type="date" class="form-control" name="tanggal_bast" required>
                            @endif
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
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('MMMM Y') }}" readonly>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="col-form-label">Jumlah Belum Diserahkan</label>
                            <input type="text" class="form-control" value="{{ $usulan->permintaanAtk->where('status','diterima')->count() }} Barang" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-12 col-form-label text-muted mb-2">Informasi Barang</label>
                        <div class="col-sm-12">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width: 0%;">No</th>
                                        <th style="width: 15%;">Jenis Barang</th>
                                        <th style="width: 30%;">Nama Barang</th>
                                        <th style="width: 30%;">Deskripsi</th>
                                        <th style="width: 10%;">Permintaan</th>
                                        <th style="width: 0%;">Satuan</th>
                                        <th style="width: 15%;">Diserahkan</th>
                                        <th style="width: 0%;">Satuan</th>
                                        <!-- <th>
                                            Diserahkan <br>
                                            <input type="checkbox" id="selectAll">
                                        </th> -->
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->permintaanAtk->where('status','diterima') as $i => $dataPermintaan)
                                    @php $permintaan = $dataPermintaan->jumlah_disetujui; @endphp
                                    <tr>
                                        <td> {{ $i + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="modul" value="distribusi">
                                            <input type="hidden" name="id_permintaan[{{$i}}]" value="{{ $dataPermintaan->id_permintaan }}">
                                            {{ $dataPermintaan->jenis_barang }}
                                        </td>
                                        <td>{{ $dataPermintaan->nama_barang }}</td>
                                        <td>{{ $dataPermintaan->spesifikasi }}</td>
                                        <td>{{ $permintaan }}</td>
                                        <td>{{ $dataPermintaan->satuan }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center" name="jumlah_penyerahan[{{$i}}]"
                                            value="0" oninput="this.value = Math.abs(this.value)" max="{{ $permintaan }}">
                                        </td>
                                        <td>{{ $dataPermintaan->satuan }}</td>
                                        <!-- <td class="text-center">
                                            <input type="hidden" value="false" name="konfirmasi_penyerahan[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" name="konfirmasi_penyerahan[{{$i}}]" id="checkbox_id{{$i}}" value="true"> <br>
                                        </td> -->
                                    </tr>
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
