@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0 ml-2">PEMELIHARAAN ALAT ANGKUTAN DARAT BERMOTOR (AADB)</h4>
            </div>
        </div>
    </div>
</div>

<section class="content text-capitalize form-group">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12 form-group">
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
            <div class="col-md-12 col-12 form-group">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 1)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Menunggu Persetujuan</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 2)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Sedang Diproses</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 4)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Selesai Diproses</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="card bg-default border border-primary">
                            <div class="card-body">
                                <h5>{{ $usulan->where('status_proses_id', 5)->count() }} <small>usulan</small> </h5>
                                <h6 class="font-weight-bold">Selesai BAST</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12 form-group">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Servis Record</h3>
                        <div class="card-tools">
                            <input type="text" id="myInputTextField">
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-servis" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kendaraan</th>
                                    <th>Servis Record</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($jadwalServis as $dataJadwal)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>
                                        <a type="button" class="font-weight-bold" data-toggle="modal" data-target="#servis{{ $dataJadwal->id_jadwal_servis }}">
                                            @if ($dataJadwal->no_plat_kendaraan != '-')
                                            {{ $dataJadwal->no_plat_kendaraan }} <br>
                                            @endif
                                            {{ $dataJadwal->merk_tipe_kendaraan }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">Kilometer</div>
                                            <div class="col-md-6">:
                                                @if ($dataJadwal->km_terakhir == null) 0 @endif
                                                {{ $dataJadwal->km_terakhir }} Km
                                            </div>
                                            <div class="col-md-6">Waktu Servis</div>
                                            <div class="col-md-6">:
                                                {{ $dataJadwal->km_terakhir + $dataJadwal->km_servis }} Km
                                            </div>
                                            <div class="col-md-6">Waktu Ganti Oli</div>
                                            <div class="col-md-6">:
                                                {{ $dataJadwal->km_terakhir + $dataJadwal->km_ganti_oli }} Km
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="servis{{ $dataJadwal->id_jadwal_servis }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    @if ($dataJadwal->no_plat_kendaraan != '-' || $dataJadwal->no_plat_kendaraan != NULL)
                                                    {{ $dataJadwal->no_plat_kendaraan }} -
                                                    @endif
                                                    {{ $dataJadwal->merk_tipe_kendaraan }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ url('unit-kerja/aadb/kendaraan/servis-record/'. $dataJadwal->id_jadwal_servis) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Terakhir</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_terakhir" class="form-control" value="{{ $dataJadwal->km_terakhir }}" placeholder="Contoh: 25000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Servis</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_servis" class="form-control" value="{{ $dataJadwal->km_servis }}" placeholder="Contoh: 3000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-4">Kilometer Ganti Oli</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="km_ganti_oli" class="form-control" value="{{ $dataJadwal->km_ganti_oli }}" placeholder="Contoh: 2000 Km">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text">
                                                                        <b>Km</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Simpan servis record ?')">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-12 form-group">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Daftar Usulan</h3>
                    </div>
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered m-0" style="font-size: 80%;">
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
                                    <td class="text-center pt-3" style="width: 5vh;">{{ $no++ }}</td>
                                    <td style="width: 15vh;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}
                                            </div>
                                            <div class="col-md-3">No. Surat :</div>
                                            <div class="col-md-12">{{ $dataUsulan->no_surat_usulan }}</div>
                                        </div>
                                    </td>
                                    <td class="text-capitalize" style="width: 50vh;">
                                        <div class="row">
                                            @if ($dataUsulan->jenis_form == 1)
                                            @foreach($dataUsulan->usulanKendaraan as $dataPengadaan)
                                            <div class="col-md-6">Kendaraan {{ $dataPengadaan->jenis_aadb }} </div>
                                            <div class="col-md-6">{{ ucfirst(strtolower($dataPengadaan->jenis_kendaraan)) }} </div>
                                            <div class="col-md-6">{{ $dataPengadaan->merk_tipe_kendaraan.' '.$dataPengadaan->tahun_kendaraan }} </div> <br>
                                            @endforeach
                                            @endif

                                            @if ($dataUsulan->jenis_form == 2)
                                            @foreach($dataUsulan->usulanServis as $dataServis)
                                            <div class="col-md-12">{{ $dataServis->merk_tipe_kendaraan.' '.$dataServis->tahun_kendaraan }}</div>
                                            <div class="col-md-6">Jatuh Tempo Servis</div>
                                            <div class="col-md-6">: {{ $dataServis->jatuh_tempo_servis }} Km</div>
                                            <div class="col-md-6">Jatuh Tempo Ganti Oli</div>
                                            <div class="col-md-6">: {{ $dataServis->jatuh_tempo_ganti_oli }} Km</div>
                                            <div class="col-md-6">Tanggal Terakhir Servis</div>
                                            <div class="col-md-6">: {{ $dataServis->tgl_servis_terakhir }}</div>
                                            <div class="col-md-6">Tanggal Terakhir Ganti Oli</div>
                                            <div class="col-md-6">: {{ $dataServis->tgl_ganti_oli_terakhir }}</div>
                                            @endforeach
                                            @endif

                                            @if ($dataUsulan->jenis_form == 3)
                                            @foreach($dataUsulan->usulanSTNK as $dataStnk)
                                            <div class="col-md-12">{{ $dataStnk->merk_tipe_kendaraan.' '.$dataStnk->tahun_kendaraan }} </div>
                                            <div class="col-md-6">Masa Berlaku STNK lama</div>
                                            <div class="col-md-6">: {{ $dataStnk->mb_stnk_lama }} </div>
                                            <div class="col-md-6">Masa Berlaku STNK Baru </div>
                                            <div class="col-md-6">: {{ $dataStnk->mb_stnk_baru }} </div>
                                            <div class="col-md-6">Biaya Perpanjangan </div>
                                            <div class="col-md-6">: Rp {{ number_format($dataStnk->biaya_perpanjangan, 0, ',', '.') }} <br>
                                                @if($dataStnk->bukti_pembayaran != null)
                                                Bukti Pembayaran : <br><a href="{{ asset('gambar/kendaraan/stnk/'. $dataStnk->bukti_pembayaran) }}" class="font-weight-bold" download>Download</a>
                                                @endif
                                            </div>
                                            @endforeach
                                            @endif

                                            @if ($dataUsulan->jenis_form == 4)
                                            @foreach($dataUsulan->usulanVoucher as $dataVoucher)
                                            <div class="col-md-12">{{ $dataVoucher->merk_tipe_kendaraan.' '.$dataVoucher->tahun_kendaraan }} </div>
                                            <div class="col-md-6">Bulan Pengadaan</div>
                                            <div class="col-md-6">: {{ \Carbon\carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMM Y') }}</div>
                                            <div class="col-md-6">Voucher 25 </div>
                                            <div class="col-md-6">: {{ $dataVoucher->voucher_50 }} </div>
                                            <div class="col-md-6">Voucher 100 </div>
                                            <div class="col-md-6">: {{ $dataVoucher->voucher_100 }} </div>
                                            <div class="col-md-6">Total Biaya </div>
                                            <div class="col-md-6">: Rp {{ number_format($dataVoucher->total_biaya, 0, ',', '.') }} </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td class="pt-2" style="width: 15vh;">
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
                                    <td class="text-center pt-4">
                                        <a type="button" class="btn btn-primary btn-sm" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($dataUsulan->otp_usulan_pengusul != null)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Usulan
                                            </a>
                                            @else
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/verif/usulan-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/aadb/usulan/proses-pembatalan/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                            @if ($dataUsulan->status_proses_id == 5)
                                            <a class="dropdown-item btn" href="{{ url('unit-kerja/surat/bast-aadb/'. $dataUsulan->id_form_usulan) }}">
                                                <i class="fas fa-file"></i> Surat Bast
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content text-capitalize form-group">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-12">
                <div id="notif-konten-chart"></div>
            </div>
            <div class="col-md-12 col-12 form-group">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <h3 class="card-title mt-1 font-weight-bold">Daftar Alat Angkutan Darat Bermotor (AADB)</h3>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label>Jenis AADB</label> <br>
                                    <select id="" class="form-control" name="jenis_aadb" style="width: 100%;">
                                        <option value="">-- JENIS AADB --</option>
                                        <option value="bmn">BMN</option>
                                        <option value="sewa">SEWA</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Nama Kendaraan</label> <br>
                                    <select name="jenis_kendaraan" id="kendaraan`+ i +`" class="form-control text-capitalize select2-2" style="width: 100%;">
                                        <option value="">-- NAMA KENDARAAN --</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 mt-2 text-right">
                                    <button id="searchChartData" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ url('unit-kerja/aadb/dashboard') }}" class="btn btn-danger">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="notif-konten-chart"></div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card-body border border-default">
                                <div id="konten-chart-google-chart">
                                    <div id="piechart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="card-body border border-default">
                                <table id="table-aadb" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            <th>Pengguna</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; $googleChartData1 = json_decode($googleChartData) @endphp
                                        @foreach ($googleChartData1->kendaraan as $dataAadb)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <b class="text-primary">{{ $dataAadb->kode_barang.'.'.$dataAadb->nup_barang }}</b> <br>
                                                {{ $dataAadb->merk_tipe_kendaraan.' '.$dataAadb->tahun_kendaraan }} <br>
                                                {{ $dataAadb->no_plat_kendaraan }} <br>
                                                {{ $dataAadb->jenis_aadb }}
                                            </td>
                                            <td>
                                                No. BPKB : {{ $dataAadb->no_bpkb }} <br>
                                                No. Rangka : {{ $dataAadb->no_rangka }} <br>
                                                No. Mesin : {{ $dataAadb->no_mesin }} <br>
                                                Masa Berlaku STNK : <br>
                                                @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)) < 365 && $dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-danger">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @elseif ($dataAadb->mb_stnk_plat_kendaraan != null)
                                                    <span class="badge badge-sm badge-pill badge-success">
                                                        {{ \Carbon\Carbon::parse($dataAadb->mb_stnk_plat_kendaraan)->isoFormat('DD MMMM Y') }}
                                                    </span>
                                                    @endif<br>
                                            </td>
                                            <td>
                                                Unit Kerja : {{ $dataAadb->unit_kerja }} <br>
                                                Pengguna : {{ $dataAadb->pengguna }} <br>
                                                Jabatan : {{ $dataAadb->jabatan }} <br>
                                                Pengemudi : {{ $dataAadb->pengemudi }}
                                            </td>
                                            <td class="text-center">
                                                @if ($dataAadb->status_kendaraan_id == 1)
                                                <span class="badge badge-sm badge-pill badge-success">Aktif</span>
                                                @elseif ($dataAadb->status_kendaraan_id == 2)
                                                <span class="badge badge-sm badge-pill badge-warning">Perbaikan</span>
                                                @elseif ($dataAadb->status_kendaraan_id == 3)
                                                <span class="badge badge-sm badge-pill badge-warning">Proses Penghapusan</span>
                                                @else
                                                <span class="badge badge-sm badge-pill badge-danger">Sudah Dihapuskan</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ url('unit-kerja/aadb/kendaraan/detail/'. $dataAadb->id_kendaraan) }}">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </a>
                                                </div>
                                            </td>
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
    </div>
</section>

<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Servis</h6>
                        </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Perpanjangan STNK</h6>
                        </a>
                    </div>
                    <div class="col-md-4 form-group">
                        <a href="{{ url('unit-kerja/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-plus-circle fa-2x"></i>
                            <h6 class="font-weight-bold">Usulan Voucher BBM</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {

        $('.dataTables_filter input[type="search"]').css({
            'width': '50px',
            'display': 'inline-block'
        });

        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
            buttons: [{
                    text: '(+) Usulan Pengadaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        window.location.href = "{{ url('unit-kerja/aadb/usulan/pengadaan/baru') }}";
                    }
                },
                {
                    text: '(+) Usulan Pemeliharaan',
                    className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                    action: function(e, dt, node, config) {
                        $('#upload').modal('show');
                    }
                }
            ]

        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');

        $("#table-servis").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        })

        $("#table-aadb").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
        })

        let j = 0

        for (let i = 1; i <= 3; i++) {
            $(".select2-" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/aadb/select2-dashboard/` + i + `/kendaraan') }}`,
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

    // Chart
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
            legend: {
                'position': 'top',
                'alignment': 'center',
                'maxLines': '5'
            },
        }

        chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }

    $('body').on('click', '#searchChartData', function() {
        let jenis_aadb = $('select[name="jenis_aadb"').val()
        let jenis_kendaraan = $('select[name="jenis_kendaraan"').val()
        let table = $('#table-kendaraan').DataTable();
        let url = ''

        if (jenis_aadb || jenis_kendaraan) {
            url = '<?= url("/unit-kerja/aadb/grafik?jenis_aadb='+jenis_aadb+'&jenis_kendaraan='+jenis_kendaraan+'") ?>'
        } else {
            url = '<?= url("/unit-kerja/aadb/grafik") ?>'
        }

        jQuery.ajax({
            url: url,
            type: "GET",
            success: function(res) {
                // console.log(res.message);
                let dataTable = $('#table-aadb').DataTable()
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
                            '<b class="text-primary">' + element.kode_barang + '.' + element.nup_barang + '</b>' +
                            '<br>' + element.merk_tipe_kendaraan + ' ' + element.tahun_kendaraan +
                            '<br>' + element.no_plat_kendaraan +
                            '<br>' + element.jenis_aadb,
                            'No. BPKB :' + element.no_bpkb +
                            '<br> No. Rangka :' + element.no_rangka +
                            '<br> No. Mesin :' + element.no_mesin +
                            '<br> Masa Berlaku STNK :' + element.mb_stnk_plat_kendaraan,
                            'Unit Kerja :' + element.unit_kerja +
                            '<br> Pengguna :' + element.pengguna +
                            '<br> Jabatan :' + element.jabatan +
                            '<br> Pengemudi :' + element.pengemudi,
                            element.status_kendaraan_id == 1 ? '<span class="badge badge-sm badge-pill badge-success">Aktif</span>' :
                            (element.status_kendaraan_id == 2 ? '<span class="badge badge-sm badge-pill badge-warning">Perbaikan</span>' :
                                (element.status_kendaraan_id == 3 ? '<span class="badge badge-sm badge-pill badge-warning">Proses Penghapusan</span>' :
                                    '<span class="badge badge-sm badge-pill badge-danger">Sudah Dihapuskan</span>')),
                            `<td class="text-center">
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ url('unit-kerja/aadb/kendaraan/detail/` + element.id_kendaraan + `') }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>`
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
