@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Proses Persetujuan Usulan Oldat</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Proses Persetujuan Usulan Oldat</li>
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
            <div class="card-body">
                <form action="{{ url('super-user/oldat/pengajuan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
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
                        <div class="col-md-12">
                            <label class="text-muted">Informasi Barang</label>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-sm-2">&nbsp;</div>
                        @if ($usulan->jenis_form == 'pengadaan')
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Barang</th>
                                        <th>Merk Barang</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Estimasi Biaya</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->detailPengadaan as $dataBarangPengadaan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataBarangPengadaan->kategori_barang }}</td>
                                        <td>{{ $dataBarangPengadaan->merk_barang }}</td>
                                        <td>{{ $dataBarangPengadaan->spesifikasi_barang }}</td>
                                        <td>{{ $dataBarangPengadaan->jumlah_barang }}</td>
                                        <td>{{ $dataBarangPengadaan->satuan_barang }}</td>
                                        <td>Rp {{ number_format($dataBarangPengadaan->estimasi_biaya, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <td>No</td>
                                        <td>Jenis Barang</td>
                                        <td>Merk Barang</td>
                                        <td>Unit Kerja</td>
                                        <td>Tahun Perolehan</td>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->detailPerbaikan as $dataBarangPerbaikan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataBarangPerbaikan->kategori_barang }}</td>
                                        <td>{{ $dataBarangPerbaikan->merk_tipe_barang }}</td>
                                        <td>{{ $dataBarangPerbaikan->unit_kerja }}</td>
                                        <td>{{ $dataBarangPerbaikan->tahun_perolehan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
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
                            <form action="{{ url('super-user/oldat/pengajuan/proses-ditolak/'. $usulan->id_form_usulan) }}" method="POST">
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
