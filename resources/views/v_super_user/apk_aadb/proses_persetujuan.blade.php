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
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Proses Persetujuan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ $usulan->no_surat_usulan }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pengusul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
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
                                        <th>Kualifikasi</td>
                                        <th>Merk/Tipe Kendaraan</td>
                                        <th>Jumlah Pengajuan </td>
                                        <th>Tahun</td>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->usulanKendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>Kendaraan {{ $dataKendaraan->kualifikasi }}</td>
                                        <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->jumlah_pengajuan }} UNIT</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @elseif ($usulan->jenis_form == 2)
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>No</th>
                                        <th>No. Plat</th>
                                        <th>Kendaraan</th>
                                        <th>Kilometer</th>
                                        <th>Jadwal Servis</th>
                                        <th>Jadwal Ganti Oli</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody class="text-capitalize">
                                    @foreach($usulan->usulanServis as $dataServis)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataServis->no_plat_kendaraan }}</td>
                                        <td>{{ $dataServis->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataServis->kilometer_terakhir }} Km</td>
                                        <td>
                                            Terakhir Servis : <br>
                                            {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                            Jatuh Tempo Servis : <br>
                                            {{ (int) $dataServis->jatuh_tempo_servis }} Km
                                        </td>
                                        <td>
                                            Terakhir Ganti Oli : <br>
                                            {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }} <br>
                                            Jatuh Tempo Servis : <br>
                                            {{ (int) $dataServis->jatuh_tempo_ganti_oli }} Km
                                        </td>
                                        <td>{{ $dataServis->keterangan_servis }}</td>
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
                                        <th>No. Plat</th>
                                        <th>Kendaraan</th>
                                        <th>Pengguna</th>
                                        <th>Masa Berlaku STNK</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody>
                                    @foreach($usulan->usulanSTNK as $dataSTNK)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                        <td>{{ $dataSTNK->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataSTNK->pengguna }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="text-center" style="width: 1%;">No</th>
                                        <th style="width: 15%;">Bulan Pengadaan</th>
                                        <th style="width: 15%;">Jenis AADB</th>
                                        <th style="width: 20%;">No. Plat</th>
                                        <th>Kendaraan</th>
                                        <th style="width: 15%;" >Kualifikasi</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody class="text-capitalize">
                                    @foreach($usulan->usulanVoucher as $dataVoucher)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                        <td>{{ $dataVoucher->jenis_aadb }}</td>
                                        <td>{{ $dataVoucher->no_plat_kendaraan }}</td>
                                        <td>{{ $dataVoucher->merk_tipe_kendaraan }}</td>
                                        <td>{{ $dataVoucher->kualifikasi }}</td>
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
