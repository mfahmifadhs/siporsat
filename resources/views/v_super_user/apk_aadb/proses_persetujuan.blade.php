@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Proses Persetujuan Usulan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/aadb/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Proses Persetujuan Usulan AADB</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<section class="content text-capitalize">
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
                <h3 class="card-title">Proses Persetujuan Usulan AADB </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
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
                    @if ($usulan->jenis_form == 4)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('MMMM Y') }}" readonly>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Kendaraan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->total_pengajuan }} Kendaraan" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">Informasi Kendaraan</label>
                        @if ($usulan->jenis_form == 1)
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>No</td>
                                        <th>Jenis AADB</td>
                                        <th>Merk/Tipe Kendaraan</td>
                                        <th>Tahun Kendaraan</td>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->usulanKendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @elseif ($usulan->jenis_form == 2)
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>Kilometer Terakhir</th>
                                        <th>Tanggal Terakhir Servis</th>
                                        <th>Tanggal Jatuh Tempo Servis</th>
                                        <th>Tanggal Terakhir Ganti Oli</th>
                                        <th>Jatuh Tempo Ganti Oli</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody class="text-capitalize">
                                    @foreach($usulan->usulanServis as $dataServis)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataServis->kilometer_terakhir }}</td>
                                        <td>{{ $dataServis->tgl_servis_terakhir }}</td>
                                        <td>{{ $dataServis->jatuh_tempo_servis }}</td>
                                        <td>{{ $dataServis->tgl_ganti_oli_terakhir }}</td>
                                        <td>{{ $dataServis->jatuh_tempo_ganti_oli }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @elseif ($usulan->jenis_form == 3)
                        <div class="col-sm-10">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>Masa Berlaku STNK</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->usulanSTNK as $dataServis)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataServis->mb_stnk_lama }}</td>
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
                                        <th>No</th>
                                        <th>Kendaraan</th>
                                        <th>Voucer 25</th>
                                        <th>Voucer 50</th>
                                        <th>Voucer 100</th>
                                        <th>Total Biaya</th>
                                        <th>Bulan Pengadaan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->usulanVoucher as $dataVoucher)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <input type="hidden" name="detail_usulan_id[]" value="{{ $dataVoucher->id_form_usulan_voucher_bbm  }}">
                                            {{ $dataVoucher->merk_tipe_kendaraan }}
                                        </td>
                                        <td><input type="text" class="form-control text-center" name="voucher_25[]" value="{{ $dataVoucher->voucher_25 }}"></td>
                                        <td><input type="text" class="form-control text-center" name="voucher_50[]" value="{{ $dataVoucher->voucher_50 }}"></td>
                                        <td><input type="text" class="form-control text-center" name="voucher_100[]" value="{{ $dataVoucher->voucher_100 }}"></td>
                                        <td>Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
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
                            <form action="{{ url('super-user/aadb/usulan/proses-ditolak/'. $usulan->id_form_usulan) }}" method="POST">
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
