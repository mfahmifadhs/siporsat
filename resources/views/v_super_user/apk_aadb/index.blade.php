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
            <div class="col-md-5 form-group">
                <div class="row small">
                    <div class="col-md-3 form-group">
                        <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn-block btn btn-primary">
                            <i class="fas fa-car fa-2x"></i>
                            <h6 class="font-weight-bold mt-1"><small>Usulan <br> Pengadaan</small></h6>
                        </a>
                    </div>
                    <div class="col-md-3 form-group">
                        <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn-block btn btn-primary">
                            <i class="fas fa-tools fa-2x"></i>
                            <h6 class="font-weight-bold mt-1"><small>Usulan <br> Servis</small></h6>
                        </a>
                    </div>
                    <div class="col-md-3 form-group">
                        <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn-block btn btn-primary">
                            <i class="fas fa-id-card-alt fa-2x"></i>
                            <h6 class="font-weight-bold mt-1"><small>Usulan <br> Perpanjangan STNK</small></h6>
                        </a>
                    </div>
                    <div class="col-md-3 form-group">
                        <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn-block btn btn-primary">
                            <i class="fas fa-gas-pump fa-2x"></i>
                            <h6 class="font-weight-bold mt-1"><small>Usulan <br> Voucher BBM</small></h5>
                        </a>
                    </div>
                    <div class="col-md-12 form-group">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h4 class="card-title font-weight-bold small">Masa Jatuh Tempo STNK</h4>
                            </div>
                            <div class="card-body">
                                <table id="table-jatuh-tempo" class="table table-striped m-0">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Kendaraan</td>
                                            <td>Pengguna</td>
                                            <td>Masa Jatuh Tempo STNK</td>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach ($stnk as $jatuhTempo)
                                        @if(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($jatuhTempo->mb_stnk_plat_kendaraan)) < 365) <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                {{ $jatuhTempo->no_plat_kendaraan }} <br>
                                                <b>{{ $jatuhTempo->merk_tipe_kendaraan }}</b>
                                            </td>
                                            <td>
                                                {{ $jatuhTempo->pengguna }} <br>
                                                {{ $jatuhTempo->jabatan }}
                                            </td>
                                            <td>
                                                <span class="badge badge-sm badge-pill badge-danger">
                                                    {{ \Carbon\Carbon::parse($jatuhTempo->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                </span>
                                            </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><small class="font-weight-bold">Daftar Usulan</small></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-default btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pengusul</th>
                                    <th>Usulan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1;
                            $no2 = 1; ?>
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td class="text-center pt-3">{{ $no++ }}</td>
                                    <td class="small">
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        No. Surat : {{ $dataUsulan->no_surat_usulan }} <br>
                                        Pengusul : {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }} <br>
                                        Unit Kerja : {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                    </td>
                                    <td class="small text-capitalize">
                                        @if ($dataUsulan->jenis_form == 1)
                                        @foreach($dataUsulan->usulanKendaraan as $dataPengadaan)
                                        Kendaraan {{ $dataPengadaan->jenis_aadb }}<br>
                                        {{ ucfirst(strtolower($dataPengadaan->jenis_kendaraan)) }} <br>
                                        {{ $dataPengadaan->merk_tipe_kendaraan.' '.$dataPengadaan->tahun_kendaraan }} <br>
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 2)
                                        @foreach($dataUsulan->usulanServis as $dataServis)
                                        {{ $dataServis->merk_tipe_kendaraan.' '.$dataServis->tahun_kendaraan }} <br>
                                        Jatuh Tempo Servis : <br>{{ $dataServis->jatuh_tempo_servis }} KM<br>
                                        Jatuh Tempo Ganti Oli : <br>{{ $dataServis->jatuh_tempo_ganti_oli }} KM<br>
                                        Tanggal Terakhir Servis : <br>{{ $dataServis->tgl_servis_terakhir }} <br>
                                        Tanggal Terakhir Ganti Oli : <br>{{ $dataServis->tgl_ganti_oli_terakhir }} <br>
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 3)
                                        @foreach($dataUsulan->usulanSTNK as $dataStnk)
                                        {{ $dataStnk->merk_tipe_kendaraan.' '.$dataStnk->tahun_kendaraan }} <br>
                                        Masa Berlaku STNK lama : <br>{{ $dataStnk->mb_stnk_lama }} <br>
                                        Masa Berlaku STNK Baru : <br>{{ $dataStnk->mb_stnk_baru }} <br>
                                        Biaya Perpanjangan : <br>Rp {{ number_format($dataStnk->biaya_perpanjangan, 0, ',', '.') }} <br>
                                        @if($dataStnk->bukti_pembayaran != null)
                                        Bukti Pembayaran : <br><a href="{{ asset('gambar/kendaraan/stnk/'. $dataStnk->bukti_pembayaran) }}" class="font-weight-bold" download>Download</a>
                                        @endif
                                        @endforeach
                                        @endif

                                        @if ($dataUsulan->jenis_form == 4)
                                        @foreach($dataUsulan->usulanVoucher as $dataVoucher)
                                        {{ $dataVoucher->merk_tipe_kendaraan.' '.$dataVoucher->tahun_kendaraan }} <br>
                                        Bulan Pengadaan : {{ \Carbon\carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMM Y') }} <br>
                                        Voucher 25 : <br>{{ $dataVoucher->voucher_25 }} <br>
                                        Voucher 50 : <br>{{ $dataVoucher->voucher_50 }} <br>
                                        Voucher 100 : <br>{{ $dataVoucher->voucher_100 }} <br>
                                        Total Biaya : <br>Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }} <br>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="pt-2 small">
                                        Status Pengajuan : <br>
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                        @endif <br>
                                        Status Proses : <br>
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang diproses <br> petugas gudang</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataUsulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @if ($dataUsulan->status_pengajuan == '')
                                                @if($dataUsulan->status_proses == 'belum proses')
                                                <span class="border border-warning">
                                                    <b class="text-warning p-3">Menunggu Persetujuan</b>
                                                </span>
                                                @elseif($dataUsulan->status_proses == 'proses')
                                                <span class="border border-primary">
                                                    <b class="text-primary p-3">Proses</b>
                                                </span>
                                                @endif
                                                @elseif ($dataUsulan->status_pengajuan == 'diterima')

                                                @else

                                                @endif
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <h5>Detail Pengajuan Usulan {{ $dataUsulan->jenis_form_usulan }}
                                                            <hr>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-2">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Pengusul
                                                    </h6>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Nama Pengusul </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->nama_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->keterangan_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Unit Kerja</label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->unit_kerja }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if($dataUsulan->jenis_form == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-8">: {{ $dataUsulan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                <div class="form-group row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Kendaraan
                                                    </h6>
                                                </div>
                                                @if($dataUsulan->jenis_form == 1)
                                                @foreach($dataUsulan -> usulanKendaraan as $dataKendaraan )
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Jenis AADB </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->jenis_aadb }}</div>
                                                        <div class="col-md-4"><label>Jenis Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->jenis_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Merk/Tipe </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->merk_tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Tahun Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->tahun_kendaraan }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @elseif($dataUsulan->jenis_form == 2)
                                                @foreach($dataUsulan -> usulanServis as $dataServis)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataServis->merk_tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Kilometer Terakhir </label></div>
                                                        <div class="col-md-8">: {{ $dataServis->kilometer_terakhir }} KM</div>
                                                        <div class="col-md-4"><label>Tgl. Terakhir Servis </label></div>
                                                        <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_servis)->isoFormat('DD MMMM Y') }}</div>
                                                        <div class="col-md-4"><label>Jatuh Tempo Servis </label></div>
                                                        <div class="col-md-8">: {{ $dataServis->jatuh_tempo_servis }} KM</div>
                                                        <div class="col-md-4"><label>Tgl. Terakhir Ganti Oli </label></div>
                                                        <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_ganti_oli)->isoFormat('DD MMMM Y') }}</div>
                                                        <div class="col-md-4"><label>Jatuh Tempo Ganti Oli </label></div>
                                                        <div class="col-md-8">: {{ $dataServis->jatuh_tempo_ganti_oli }} KM</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @elseif($dataUsulan->jenis_form == 3)
                                                @foreach($dataUsulan -> usulanSTNK as $dataSTNK)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataSTNK->merk_tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>No Plat BBM </label></div>
                                                        <div class="col-md-8">: {{ $dataSTNK->no_plat_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Masa Berlaku STNK</label></div>
                                                        <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @elseif($dataUsulan->jenis_form == 4)
                                                @foreach($dataUsulan -> usulanVoucher as $dataVoucher)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->merk_tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Voucher 25 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_25 }}</div>
                                                        <div class="col-md-4"><label>Voucher 50 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_50 }}</div>
                                                        <div class="col-md-4"><label>Voucher 100 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_100 }}</div>
                                                        <div class="col-md-4"><label>Total </label></div>
                                                        <div class="col-md-8">: Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }}</div>
                                                        <div class="col-md-4"><label>Bulan Pengadaan </label></div>
                                                        <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <div class="col-md-12">
                                                    <span style="float: left;">
                                                        @if($dataUsulan->status_proses_id == 5)
                                                        <a href="{{ url('super-user/aadb/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                        @endif
                                                    </span>
                                                    <span style="float: right;">
                                                        <a href="{{ url('super-user/aadb/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-outline card-primary" style="height: 100%;">
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
                                    <option value="">Semua Unit Kerja</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="jenis_kendaraan">
                                    <option value="">Semua Jenis Kendaraan</option>
                                    @foreach ($jenisKendaraan as $item)
                                    <option value="{{$item->id_jenis_kendaraan}}">{{$item->jenis_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="merk_tipe_kendaraan">
                                    <option value="">Semua Merk Kendaraan</option>
                                    @foreach ($merk as $item)
                                    <option value="{{$item->merk_tipe_kendaraan}}">{{$item->merk_tipe_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tahun_kendaraan">
                                    <option value="">Semua Tahun Kendaraan</option>
                                    @foreach ($tahun as $item)
                                    <option value="{{$item->tahun_kendaraan}}">{{ $item->tahun_kendaraan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="pengguna">
                                    <option value="">Semua Pengguna</option>
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
                        <div id="konten-chart-google-chart">
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
                                        <td>{{ $dataKendaraan->merk_tipe_kendaraan }}</td>
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
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        $("#table-jatuh-tempo").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": false,
            pageLength: 4,
            lengthMenu: [
                [4, 10, 20, -1],
                [4, 10, 20, 'Semua']
            ]
        })

        $("#table-kendaraan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('super-user/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            })
        }
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
            titlePosition: 'none',
            is3D: false,
            height: 500,
            legend: {
                'position': 'top',
                'alignment': 'center',
                'maxLines': '2'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let unit_kerja = $('select[name="unit_kerja"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let merk_tipe_kendaraan = $('select[name="merk_tipe_kendaraan"').val()
        let tahun_kendaraan = $('select[name="tahun_kendaraan"').val()
        let pengguna = $('select[name="pengguna"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || unit_kerja || jenis_kendaraan || merk_tipe_kendaraan || tahun_kendaraan || pengguna) {
            url = '<?= url("/super-user/aadb/dashboard/grafik?jenis_aadb='+jenis_aadb+'&unit_kerja='+unit_kerja+'&jenis_kendaraan='+jenis_kendaraan+'&merk_tipe_kendaraan='+merk_tipe_kendaraan+'&tahun_kendaraan='+tahun_kendaraan+'&pengguna='+pengguna+'") ?>'
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
                            element.merk_tipe_kendaraan,
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
