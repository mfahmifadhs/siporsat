@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Dashboard Pengelolaan Alat Angkutan Darat Bermotor (AADB)</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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
            </div>
            <div class="col-md-6 form-group">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/2962/2962303.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Pengadaan</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/4659/4659844.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Servis</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/3389/3389596.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Perpanjangan STNK</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/2915/2915450.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Voucher BBM</small></h5>
                        </a>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">DAFTAR USULAN PENGAJUAN</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            @foreach($pengajuan as $dataPengajuan)
                            <small>
                                <div class="form-group row">
                                    <label class="col-md-5 col-6">{{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</label>
                                    <div class="col-md-7 col-6 text-right">
                                        @if($dataPengajuan->status_proses_id == 1)
                                        <span class="badge badge-warning py-1">menunggu persetujuan</span>
                                        @elseif($dataPengajuan->status_proses_id == 2)
                                        <span class="badge badge-warning py-1">usulan diproses</span>
                                        @elseif($dataPengajuan->status_proses_id == 3)
                                        <span class="badge badge-warning py-1">konfirmasi penerimaan</span>
                                        @elseif($dataPengajuan->status_proses_id == 4)
                                        <span class="badge badge-warning py-1">konfirmasi kabag rt</span>
                                        @endif
                                    </div>
                                    <label class="col-md-4 col-6">ID Usulan</label>
                                    <div class="col-md-8 col-6">: {{ $dataPengajuan->id_form_usulan }}</div>
                                    <label class="col-md-4 col-6">Tujuan</label>
                                    <div class="col-md-8 col-6 text-capitalize">: {{ $dataPengajuan->jenis_form_usulan }}</div>
                                    <label class="col-md-4 col-6">Pengusul</label>
                                    <div class="col-md-8 col-6">: {{ $dataPengajuan->nama_pegawai }}</div>
                                    <label class="col-md-4 col-6">Jumlah Usulan</label>
                                    <div class="col-md-8 col-6">: {{ $dataPengajuan->total_pengajuan }} barang</div>
                                    <div class="col-md-12 col-12">
                                        <small>
                                            <a class="btn btn-primary btn-sm mt-2" data-toggle="modal" data-target="#modal-info-{{ $dataPengajuan->id_form_usulan }}" title="Detail Usulan">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <!-- Aksi untuk Kepala Bagian RT -->
                                            @if($dataPengajuan->status_proses_id == 1)
                                            @if(Auth::user()->pegawai->jabatan_id == 2)
                                            <a type="button" class="btn btn-success btn-sm text-white mt-2" data-toggle="modal" data-target="#modal-info-{{ $dataPengajuan->id_form_usulan }}">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                            <a href="{{ url('super-user/aadb/usulan/proses-ditolak/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-danger btn-sm text-white mt-2" onclick="return confirm('Apakah pengajuan ini ditolak ?')">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            @endif
                                            @endif

                                            @if($dataPengajuan->status_proses_id == 2)
                                            @if(Auth::user()->pegawai->jabatan_id == 5)
                                            <a class="btn btn-success btn-sm text-white mt-2" href="{{ url('super-user/ppk/aadb/pengajuan/'. $dataPengajuan->jenis_form.'/'. $dataPengajuan->id_form_usulan) }}" onclick="return confirm('Usulan Selesai Di Proses ?')">
                                                <i class="fas fa-people-carry"></i> Menyerahkan Barang
                                            </a>
                                            @endif
                                            @endif

                                            @if($dataPengajuan->status_proses_id == 3)
                                            @if(Auth::user()->pegawai_id == $dataPengajuan->pegawai_id)
                                            <a class="btn btn-success btn-sm mt-2" data-toggle="modal" data-target="#konfirmasi_pengusul{{ $dataPengajuan->id_form_usulan }}" title="Detail Usulan">
                                                <i class="fas fa-check-circle"></i> <small>barang diterima</small>
                                            </a>
                                            @endif
                                            @endif

                                            @if($dataPengajuan->status_proses_id == 4)
                                            @if(Auth::user()->pegawai->jabatan_id == 2)
                                            <a class="btn btn-success btn-sm mt-2" data-toggle="modal" data-target="#konfirmasi_pengusul{{ $dataPengajuan->id_form_usulan }}" title="Detail Usulan">
                                                <i class="fas fa-check-circle"></i> <small>usulan diterima</small>
                                            </a>
                                            @endif
                                            @endif

                                        </small>
                                    </div>
                                </div>
                            </small>
                            <div class="col-md-12">
                                <hr>
                            </div>
                            <!-- Modal Detail Pengajuan -->
                            <div class="modal fade" id="modal-info-{{ $dataPengajuan->id_form_usulan }}">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-body text-capitalize">
                                            <div class="text-uppercase text-center font-weight-bold mb-4">
                                                usulan {{ $dataPengajuan->jenis_form_usulan }} bmn alat pengolah data
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->nama_pegawai }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Jabatan Pengusul :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->jabatan }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Unit Kerja :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->unit_kerja }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Tanggal Usulan :</label></div>
                                                <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                            </div>
                                            @if($dataPengajuan->jenis_form == 1)
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Rencana Pengguna :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->rencana_pengguna }}</div>
                                            </div>
                                            @endif
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <!-- Informasi Kendaraan : Pengajuan Usulan Pengadaan -->
                                                    @if($dataPengajuan->jenis_form == 1)
                                                    <table class="table table-responsive table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <td>No</td>
                                                                <td style="width: 20%;">Jenis AADB</td>
                                                                <td style="width: 30%;">Merk Kendaraan</td>
                                                                <td style="width: 30%;">Tipe Kendaraan</td>
                                                                <td style="width: 20%;">Tahun Kendaraan</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody>
                                                            @foreach($dataPengajuan->usulanKendaraan as $dataPengadaan)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataPengadaan->jenis_aadb }}</td>
                                                                <td>{{ $dataPengadaan->merk_kendaraan }}</td>
                                                                <td>{{ $dataPengadaan->tipe_kendaraan }}</td>
                                                                <td>{{ $dataPengadaan->tahun_kendaraan }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                    <!-- Informasi Kendaraan : Pengajuan Servis -->
                                                    @if($dataPengajuan->jenis_form == 2)
                                                    <table class="table table-responsive table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <td style="width: 5%;">No</td>
                                                                <td>Kendaraan</td>
                                                                <td style="width: 12%;">Kilometer Terakhir</td>
                                                                <td style="width: 12%;">Tanggal Terakhir Servis</td>
                                                                <td style="width: 15%;">Tanggal Jatuh Tempo Servis</td>
                                                                <td style="width: 12%;">Tanggal Terakhir Ganti Oli</td>
                                                                <td style="width: 12%;">Jatuh Tempo Ganti Oli</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody class="text-capitalize">
                                                            @foreach($dataPengajuan->usulanServis as $dataServis)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataServis->merk_kendaraan.' '.$dataServis->tipe_kendaraan }}</td>
                                                                <td>{{ $dataServis->kilometer_terakhir }}</td>
                                                                <td>{{ $dataServis->tgl_servis_terakhir }}</td>
                                                                <td>{{ $dataServis->jatuh_tempo_servis }}</td>
                                                                <td>{{ $dataServis->tgl_ganti_oli_terakhir }}</td>
                                                                <td>{{ $dataServis->jatuh_tempo_ganti_oli }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                    <!-- Informasi Kendaraan : Pengajuan Perpanjangan STNK -->
                                                    @if($dataPengajuan->jenis_form == 3)
                                                    <table class="table table-responsive table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <td style="width: 5%;">No</td>
                                                                <td>Kendaraan</td>
                                                                <td style="width: 12%;">Masa Berlaku STNK</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody>
                                                            @foreach($dataPengajuan->usulanSTNK as $dataServis)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataServis->merk_kendaraan.' '.$dataServis->tipe_kendaraan }}</td>
                                                                <td>{{ $dataServis->mb_stnk_lama }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                    <!-- Informasi Kendaraan : Pengajuan Voucher BBM -->
                                                    @if($dataPengajuan->jenis_form == 4)
                                                    <table class="table table-responsive table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <td style="width: 5%;">No</td>
                                                                <td>Kendaraan</td>
                                                                <td style="width: 12%;">Harga /Liter</td>
                                                                <td style="width: 12%;">Jumlah Kebutuhan (L)</td>
                                                                <td style="width: 15%;">Jenis BBMN</td>
                                                                <td style="width: 12%;">Total Biaya</td>
                                                                <td style="width: 12%;">Bulan Pengadaan</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody>
                                                            @foreach($dataPengajuan->usulanVoucher as $dataVoucher)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataVoucher->merk_kendaraan.' '.$dataVoucher->tipe_kendaraan }}</td>
                                                                <td>Rp {{ number_format($dataVoucher->harga_perliter, 0, ',', '.') }}</td>
                                                                <td>{{ $dataVoucher->jumlah_kebutuhan }}</td>
                                                                <td>{{ $dataVoucher->jenis_bbm }}</td>
                                                                <td>Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                </div>
                                                @if(Auth::user()->pegawai->jabatan_id == 2 && $dataPengajuan->status_proses_id == 1)
                                                <div class="col-md-12">
                                                    <form action="{{ url('super-user/aadb/usulan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group row mt-4">
                                                            <label class="text-muted col-md-12">Verifikasi Pengajuan Diterima</label>
                                                        </div>
                                                        <div class="form-group row">
                                                            <!-- <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label> -->
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="kode_otp" id="inputOTP{{ $dataPengajuan->id_form_usulan }}" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP" data-idtarget="{{ $dataPengajuan->id_form_usulan }}">Cek OTP</a>
                                                                <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6">
                                                                <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Apakah data sudah terisi dengan benar ?')" disabled>Submit</button>
                                                                <button type="reset" class="btn btn-default">BATAL</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Detail Barang -->
                            <div class="modal fade" id="konfirmasi_pengusul{{ $dataPengajuan->id_form_usulan }}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-capitalize">
                                            <div class="text-uppercase text-center font-weight-bold mb-4">
                                                konfirmasi penerimaan barang
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->nama_pegawai }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Jabatan Pengusul :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->jabatan }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Unit Kerja :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->unit_kerja }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Tanggal Usulan :</label></div>
                                                <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                            </div>
                                            <div class="form-group row mb-0">
                                                <div class="col-md-4"><label>Rencana Pengguna :</label></div>
                                                <div class="col-md-8">: {{ $dataPengajuan->rencana_pengguna }}</div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <table class="table table-responsive table-bordered text-center">
                                                        <thead>
                                                            <tr>
                                                                <td>No</td>
                                                                <td style="width: 15%;">Jenis AADB</td>
                                                                <td style="width: 15%;">Merk</td>
                                                                <td style="width: 25%;">Tipe</td>
                                                                <td style="width: 10%;">Tahun</td>
                                                                <td style="width: 15%;">No. Plat</td>
                                                                <td style="width: 20%;">Masa Berlaku STNK</td>
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        <tbody>
                                                            @foreach($dataPengajuan->kendaraan as $dataKendaraan)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                                                <td>{{ $dataKendaraan->merk_kendaraan }}</td>
                                                                <td>{{ $dataKendaraan->tipe_kendaraan }}</td>
                                                                <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                                                <td>{{ $dataKendaraan->no_plat_kendaraan }}</td>
                                                                <td>{{ $dataKendaraan->mb_stnk_plat_kendaraan }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                @if(Auth::user()->pegawai_id == $dataPengajuan->pegawai_id && $dataPengajuan->status_proses_id == 3)
                                                <div class="col-md-12">
                                                    <form action="{{ url('super-user/aadb/usulan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status_usulan" value="4">
                                                        <div class="form-group row mt-4">
                                                            <div class="col-sm-10">
                                                                <label>Apakah semua barang telah diterima dengan baik ?</label><br>
                                                                <input type="radio" name="konfirmasi" value="1"> Ya
                                                                <input type="radio" name="konfirmasi" value="1"> Tidak
                                                            </div>
                                                        </div>
                                                        <div class="form-group row mt-4">
                                                            <label class="text-muted col-md-12">Verifikasi Pengajuan Diterima</label>
                                                        </div>
                                                        <div class="form-group row">
                                                            <!-- <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label> -->
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="kode_otp" id="inputOTP{{ $dataPengajuan->id_form_usulan }}" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP" data-idtarget="{{ $dataPengajuan->id_form_usulan }}">Cek OTP</a>
                                                                <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6">
                                                                <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Apakah data sudah terisi dengan benar ?')" disabled>Submit</button>
                                                                <button type="reset" class="btn btn-default">BATAL</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @endif
                                                @if(Auth::user()->pegawai->jabatan_id == 2 && $dataPengajuan->status_proses_id == 4)
                                                <div class="col-md-12">
                                                    <form action="{{ url('super-user/aadb/usulan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status_usulan" value="5">
                                                        <div class="form-group row">
                                                            <!-- <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label> -->
                                                            <div class="col-sm-6">
                                                            <input type="text" class="form-control" name="kode_otp" id="inputOTP{{ $dataPengajuan->id_form_usulan }}" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP" data-idtarget="{{ $dataPengajuan->id_form_usulan }}">Cek OTP</a>
                                                                <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6">
                                                                <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Apakah data sudah terisi dengan benar ?')" disabled>Submit</button>
                                                                <button type="reset" class="btn btn-default">BATAL</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            {{ $pengajuan->links("pagination::bootstrap-4") }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url('super-user/aadb/pengajuan/daftar/seluruh-pengajuan') }}">
                            <i class="fas fa-arrow-circle-right"></i> Seluruh pengajuan
                        </a>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold">MASA JATUH TEMPO STNK</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-6 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_aadb">
                                    <option value="">-- Pilih Jenis AADB --</option>
                                    <option value="bmn">BMN</option>
                                    <option value="sewa">SEWA</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_kendaraan">
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    @foreach ($jenisKendaraan as $item)
                                    <option value="{{$item->id_jenis_kendaraan}}">{{$item->jenis_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="merk_kendaraan">
                                    <option value="">-- Pilih Merk Kendaraan --</option>
                                    @foreach ($merk as $item)
                                    <option value="{{$item->merk_kendaraan}}">{{$item->merk_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tahun_kendaraan">
                                    <option value="">-- Pilih Tahun Kendaraan --</option>
                                    @foreach ($tahun as $item)
                                    <option value="{{$item->tahun_kendaraan}}">{{$item->tahun_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="pengguna">
                                    <option value="">-- Pilih Pengguna --</option>
                                    @foreach ($pengguna as $item)
                                    <option value="{{$item->pengguna}}">{{$item->pengguna}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group mr-2">
                                <div class="row">
                                    <button id="searchChartData" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-user/aadb/dashboard') }}" class="btn btn-danger ml-2">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                        <div class="table">
                            <table id="table-kendaraan" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis AADB</th>
                                        <th>Unit Kerja</th>
                                        <th>Jenis Kendaraan</th>
                                        <th>Merk Kendaraan</th>
                                        <th>Tahun Kendaraan</th>
                                        <th>Pengguna</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                    @foreach ($googleChartData1->kendaraan as $dataKendaraan)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $dataKendaraan->jenis_aadb }}</td>
                                        <td>{{ $dataKendaraan->unit_kerja }}</td>
                                        <td>{{ $dataKendaraan->jenis_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->merk_kendaraan.' '.$dataKendaraan->tipe_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->tahun_kendaraan }}</td>
                                        <td>{{ $dataKendaraan->pengguna }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        $("#table-kendaraan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "Usulan AADB"
            jQuery.ajax({
                url: '/super-user/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasi mengirim kode OTP')
                    resOTP = res
                }
            });
        });

        $(document).on('click', '#btnCheckOTP', function() {
            let idUsulan = $(this).data('idtarget')
            let inputOTP = $('#inputOTP' + idUsulan).val()
            console.log(idUsulan)
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            } else if (inputOTP == resOTP) {
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit').prop('disabled', false)
            } else {
                alert('Kode OTP Salah')
                $('#btnSubmit').prop('disabled', true)
            }
        })
    })

    let chart
    let chartData = JSON.parse(`<?php echo $googleChartData; ?>`)
    let dataChart = chartData.all
    google.charts.load('current', {
        'packages': ['corechart']
    })
    google.charts.setOnLoadCallback(function() {
        drawChart(dataChart)
    })

    function drawChart(dataChart) {

        chartData = [
            ['Jenis Kendaraan', 'Jumlah']
        ]
        console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Kendaraan',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let merk_kendaraan = $('select[name="merk_kendaraan"').val()
        let tahun_kendaraan = $('select[name="tahun_kendaraan"').val()
        let pengguna = $('select[name="pengguna"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || unit_kerja || jenis_kendaraan || merk_kendaraan || merk_kendaraan || tahun_kendaraan || pengguna) {
            url = '<?= url("/super-user/aadb/dashboard/grafik?jenis_aadb='+jenis_aadb+'&unit_kerja='+unit_kerja+'&jenis_kendaraan='+jenis_kendaraan+'&merk_kendaraan='+merk_kendaraan+'&tahun_kendaraan='+tahun_kendaraan+'&pengguna='+pengguna+'") ?>'
        } else {
            url = '<?= url("/super-user/aadb/dashboard/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-kendaraan').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTable.clear()
                    dataTable.draw()
                    let no = 1

                    data.table.forEach(element => {
                        dataTable.row.add([
                            no++,
                            element.jenis_aadb,
                            element.unit_kerja,
                            element.jenis_kendaraan,
                            element.merk_kendaraan,
                            element.tahun_kendaraan,
                            element.pengguna
                        ]).draw(false)
                    })

                } else {
                    dataTable.clear()
                    dataTable.draw()
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').hide();
                    var html = ''
                    html += '<div class="notif-tidak-ditemukan">'
                    html += '<div class="card bg-secondary py-4">'
                    html += '<div class="card-body text-white">'
                    html += '<h5 class="mb-4 font-weight-bold text-center">'
                    html += 'Data tidak dapat ditemukan'
                    html += '</h5>'
                    html += '</div>'
                    html += '</div>'
                    html += '</div>'
                    $('#notif-konten-chart').append(html)
                }
            },
        })
    })
</script>

@endsection
@endsection
