@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Proses Persetujuan Usulan ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
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
            <div class="card-header">
                <h3 class="card-title">Persetujuan Usulan ATK </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/atk/usulan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" value="{{ $usulan->no_surat_usulan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pengusul</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="{{ $usulan->keterangan_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" value="{{ $usulan->tanggal_usulan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->total_pengajuan }} Barang" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">Informasi Barang</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Merk/Tipe</th>
                                        <th style="width: 20%;">Jumlah</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @if ($usulan->jenis_form == 'pengadaan')
                                    @foreach($usulan->detailUsulanAtk as $dataPengadaan)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="detail_form_id[]" value="{{ $dataPengadaan->id_form_usulan_detail }}">
                                            {{ $dataPengadaan->kategori_atk }}
                                        </td>
                                        <td>{{ $dataPengadaan->merk_atk }}</td>
                                        <td>
                                            <input type="hidden" class="text-center form-control" name="jumlah_pengajuan[]" value="{{ $dataPengadaan->jumlah_pengajuan }}">
                                            {{ $dataPengadaan->jumlah_pengajuan }}
                                        </td>
                                        <td>{{ $dataPengadaan->satuan }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @foreach($usulan->detailUsulanAtk as $dataDistribusi)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="detail_form_id[]" value="{{ $dataDistribusi->id_form_usulan_detail }}">
                                            {{ $dataDistribusi->kategori_atk }}
                                        </td>
                                        <td>{{ $dataDistribusi->merk_atk }}</td>
                                        <td>
                                            <input type="text" class="text-center form-control" name="jumlah_pengajuan[]" value="{{ $dataDistribusi->jumlah_pengajuan }}">
                                        </td>
                                        <td>{{ $dataDistribusi->satuan }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
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
                            <a href="{{ url('super-user/aadb/usulan/proses-ditolak/'. $usulan->id_form_usulan) }}" class="btn btn-danger" onclick="return confirm('Pengajuan Ditolak ?')">
                                <i class="fas fa-times-circle"></i> Tolak
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
