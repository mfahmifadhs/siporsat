@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan BMN OLDAT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard OLDAT</li>
                </ol>
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
            <!-- Usulan Pengadaan -->
            <div class="col-md-5 form-group">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/pengadaan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/955/955063.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Pengadaan</small></h5>
                        </a>
                    </div>
                    <div class="col-md-6 form-group">
                        <a href="{{ url('super-user/oldat/pengajuan/form-usulan/perbaikan') }}" class="btn-block btn btn-primary">
                            <img src="https://cdn-icons-png.flaticon.com/512/1086/1086470.png" width="50" height="50">
                            <h5 class="font-weight-bold mt-1"><small>Usulan <br> Perbaikan</small></h5>
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
                                    <label class="col-md-5 col-6">ID Usulan</label>
                                    <div class="col-md-7 col-6">: {{ $dataPengajuan->id_form_usulan }}</div>
                                    <label class="col-md-5 col-6">Tujuan</label>
                                    <div class="col-md-7 col-6">: {{ $dataPengajuan->jenis_form }}</div>
                                    <label class="col-md-5 col-6">Nama Pengusul</label>
                                    <div class="col-md-7 col-6">: {{ $dataPengajuan->nama_pegawai }}</div>
                                    <label class="col-md-5 col-6">Jumlah Usulan</label>
                                    <div class="col-md-7 col-6">: {{ $dataPengajuan->total_pengajuan }} barang</div>
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
                                            <a href="{{ url('super-user/oldat/pengajuan/proses-ditolak/'. $dataPengajuan->kode_otp_usulan) }}" class="btn btn-danger btn-sm text-white mt-2" onclick="return confirm('Apakah pengajuan ini ditolak ?')">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            @endif
                                            @endif

                                            @if($dataPengajuan->status_proses_id == 2)
                                            @if(Auth::user()->pegawai->jabatan_id == 5)
                                            <a class="btn btn-success btn-sm text-white mt-2" href="{{ url('super-user/ppk/oldat/pengajuan/'. $dataPengajuan->jenis_form.'/'. $dataPengajuan->id_form_usulan) }}" onclick="return confirm('Usulan Selesai Di Proses ?')">
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
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body text-capitalize">
                                            <div class="text-uppercase text-center font-weight-bold mb-4">
                                                usulan {{ $dataPengajuan->jenis_form }} bmn alat pengolah data
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
                                                                <td style="width: 15%;">Jenis Barang</td>
                                                                <td style="width: 20%;">Merk Barang</td>
                                                                <td style="width: 40%;">Spesifikasi</td>
                                                                <td>Jumlah</td>
                                                                <td>Satuan</td>
                                                                @if($dataPengajuan->jenis_form == 'pengadaan')
                                                                <td style="width: 25%;">Estimasi Biaya</td>
                                                                @else
                                                                <td style="width: 25%;">Nilai Perolehan</td>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        @if($dataPengajuan->jenis_form == 'pengadaan')
                                                        <tbody>
                                                            @foreach($dataPengajuan->detailPengadaan as $dataBarangPengadaan)
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
                                                        @else
                                                        <tbody>
                                                            @foreach($dataPengajuan->detailPerbaikan as $dataBarangPerbaikan)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataBarangPerbaikan->kategori_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->merk_tipe_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->spesifikasi_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->jumlah_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->satuan_barang }}</td>
                                                                <td>Rp {{ number_format($dataBarangPerbaikan->nilai_perolehan, 0, ',', '.') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        @endif
                                                    </table>
                                                </div>
                                                @if(Auth::user()->pegawai->jabatan_id == 2 && $dataPengajuan->status_proses_id == 1)
                                                <div class="col-md-12">
                                                    <form action="{{ url('super-user/oldat/pengajuan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group row mt-4">
                                                            <label class="text-muted col-md-12">Verifikasi Pengajuan Diterima</label>
                                                        </div>
                                                        <div class="form-group row">
                                                            <!-- <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label> -->
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="kode_otp" id="inputOTP" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
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
                                                                <td style="width: 15%;">Jenis Barang</td>
                                                                <td style="width: 20%;">Merk Barang</td>
                                                                <td style="width: 40%;">Spesifikasi</td>
                                                                <td>Jumlah</td>
                                                                <td>Satuan</td>
                                                                @if($dataPengajuan == 'pengadaan')
                                                                <td style="width: 25%;">Estimasi Biaya</td>
                                                                @else
                                                                <td style="width: 25%;">Nilai Perolehan</td>
                                                                @endif
                                                            </tr>
                                                        </thead>
                                                        <?php $no = 1; ?>
                                                        @if($dataPengajuan->jenis_form == 'pengadaan')
                                                        <tbody>
                                                            @foreach($dataPengajuan->barang as $dataBarang)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataBarang->kategori_barang }}</td>
                                                                <td>{{ $dataBarang->merk_tipe_barang }}</td>
                                                                <td>{{ $dataBarang->spesifikasi_barang }}</td>
                                                                <td>{{ $dataBarang->jumlah_barang }}</td>
                                                                <td>{{ $dataBarang->satuan_barang }}</td>
                                                                <td>Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        @else
                                                        <tbody>
                                                            @foreach($dataPengajuan->detailPerbaikan as $dataBarangPerbaikan)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>{{ $dataBarangPerbaikan->kategori_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->merk_tipe_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->spesifikasi_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->jumlah_barang }}</td>
                                                                <td>{{ $dataBarangPerbaikan->satuan_barang }}</td>
                                                                <td>Rp {{ number_format($dataBarangPerbaikan->nilai_perolehan, 0, ',', '.') }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        @endif
                                                    </table>
                                                </div>
                                                @if(Auth::user()->pegawai_id == $dataPengajuan->pegawai_id && $dataPengajuan->status_proses_id == 3)
                                                <div class="col-md-12">
                                                    <form action="{{ url('super-user/oldat/pengajuan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
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
                                                                <input type="text" class="form-control" name="kode_otp" id="inputOTP" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
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
                                                    <form action="{{ url('super-user/oldat/pengajuan/proses-diterima/'. $dataPengajuan->id_form_usulan) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status_usulan" value="5">
                                                        <div class="form-group row mt-4">
                                                            <label class="text-muted col-md-12">Verifikasi Pengajuan Diterima</label>
                                                        </div>
                                                        <div class="form-group row">
                                                            <!-- <label class="col-sm-3 col-form-label">Verifikasi Kode OTP</label> -->
                                                            <div class="col-sm-6">
                                                                <input type="text" class="form-control" name="kode_otp" id="inputOTP" placeholder="Masukan Kode OTP" required>
                                                                <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
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
                        <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}">
                            <i class="fas fa-arrow-circle-right"></i> Seluruh pengajuan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <input type="number" class="form-control" name="tahun" placeholder="Tahun">
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">Semua Unit Kerja</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}" nama="{{$item->unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">Semua Tim Kerja</option>
                                    @foreach ($timKerja as $item)
                                    <option value="{{$item->id_tim_kerja}}" nama="{{$item->tim_kerja}}">{{$item->tim_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <div class="row">
                                    <button id="searchChartData" class="btn btn-primary ml-2">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('super-user/oldat/dashboard') }}" class="btn btn-danger ml-2"><i class="fas fa-undo"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart-google-chart">
                            <div id="piechart" style="height: 500px;"></div>
                        </div>
                        <div id="notif-konten-chart"></div>
                        <div class="table">
                            <table id="table-barang" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Barang</th>
                                        <th>Merk Barang</th>
                                        <th>Spesifikasi Barang</th>
                                        <th>Kondisi Barang</th>
                                        <th>Unit Kerja</th>
                                        <th>Tim Kerja</th>
                                        <th>Tahun</th>
                                        <th>Pengguna</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                    @foreach ($googleChartData1->barang as $item)
                                    <tr>
                                        <td>{{$no++}}</td>
                                        <td>{{$item->kategori_barang}}</td>
                                        <td>{{$item->merk_tipe_barang}}</td>
                                        <td>{{$item->spesifikasi_barang}}</td>
                                        <td>{{$item->kondisi_barang}}</td>
                                        <td>{{$item->unit_kerja}}</td>
                                        <td>{{$item->tim_kerja}}</td>
                                        <td>{{$item->tahun_perolehan}}</td>
                                        <td>{{$item->nama_pegawai}}</td>
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
<script>
    $(function() {
        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "Kepala Bagian Rumah Tangga"
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
            let inputOTP = $('#inputOTP').val()
            console.log(inputOTP)
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
            ['Kategori Barang', 'Jumlah']
        ]
        console.log(dataChart)
        dataChart.forEach(data => {
            chartData.push(data)
        })

        var data = google.visualization.arrayToDataTable(chartData);

        var options = {
            title: 'Total Barang',
            legend: {
                'position': 'left',
                'alignment': 'center'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let tahun       = $('input[name="tahun"').val()
        let unit_kerja  = $('select[name="unit_kerja"').val()
        let tim_kerja   = $('select[name="tim_kerja"').val()
        let url = ''
        if (tahun || unit_kerja || tim_kerja) {
            url = '<?= url("/super-user/oldat/grafik?tahun='+tahun+'&unit_kerja='+unit_kerja+'&tim_kerja='+tim_kerja+'") ?>'
        } else {
            url = '<?= url("/super-user/oldat/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-barang').DataTable()
                if (res.message == 'success') {
                    $('.notif-tidak-ditemukan').remove();
                    $('#konten-chart-google-chart').show();
                    let data = JSON.parse(res.data)
                    drawChart(data.chart)

                    dataTable.clear()
                    dataTable.draw()
                    let no = 1
                    console.log(res)
                    data.table.forEach(element => {
                        dataTable.row.add([
                            no++,
                            element.kategori_barang,
                            element.merk_tipe_barang,
                            element.spesifikasi_barang,
                            element.kondisi_barang,
                            element.unit_kerja,
                            element.tim_kerja,
                            element.tahun_perolehan,
                            element.nama_pegawai
                        ]).draw(false)
                    });

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
