@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Usulan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan AADB</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row form-group" id="rekap-default">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <b class="font-weight-bold text-primary card-title mt-3" style="font-size:medium;">
                            <i class="fas fa-table"></i> TABEL USULAN AADB
                        </b>
                        <div class="card-tools">
                            <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn btn-primary btn-xs mr-1">
                                <i class="fas fa-car fa-2x py-2"></i> <br>
                                Usulan Pengadaan
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary btn-xs mr-1">
                                <i class="fas fa-tools fa-2x py-2"></i> <br>
                                Usulan Servis
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary btn-xs mr-1">
                                <i class="fas fa-id-card-alt fa-2x py-2"></i> <br>
                                Usulan Perpanjang STNK
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary btn-xs">
                                <i class="fas fa-gas-pump fa-2x py-2"></i> <br>
                                Usulan Voucher BBM
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered table-striped">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No. Surat</th>
                                <th>No. Surat</th>
                                <th>Pengusul</th>
                                <th>Unit Kerja</th>
                                <th>Jenis Usulan</th>
                                <th>Status</th>
                                <th>Status Pengajuan</th>
                                <th>Status Usulan</th>
                                <th>Aksi</th>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($pengajuan as $dataPengajuan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td class="text-uppercase">{{ $dataPengajuan->no_surat_usulan }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        <span class="text-uppercase">{{ $dataPengajuan->no_surat_usulan }}</span>
                                    </td>
                                    <td>{{ ucfirst(strtolower($dataPengajuan->nama_pegawai)) }}</td>
                                    <td>{{ $dataPengajuan->unit_kerja }}</td>
                                    <td>{{ $dataPengajuan->jenis_form_usulan }}</td>
                                    <td>
                                        <span>Status Pengajuan : </span> <br>
                                        @if($dataPengajuan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataPengajuan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
                                        @endif
                                        <br>
                                        <span>Status Proses : </span> <br>
                                        @if($dataPengajuan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                        @elseif ($dataPengajuan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                        @elseif ($dataPengajuan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataPengajuan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi kabag rt</span>
                                        @elseif ($dataPengajuan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif
                                    </td>
                                    <td class="text-uppercase">{{ $dataPengajuan->status_pengajuan }}</td>
                                    <td class="text-uppercase">{{ $dataPengajuan->status_proses }} </td>
                                    <td>
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>

                                        <div class="dropdown-menu">
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $dataPengajuan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/aadb/usulan/persetujuan/'. $dataPengajuan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $dataPengajuan->status_proses_id == 2)
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/aadb/usulan/'. $dataPengajuan->jenis_form.'/'. $dataPengajuan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses Penyerahan
                                            </a>
                                            @elseif ($dataPengajuan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/surat/surat-bast/'. $dataPengajuan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> BAST
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataPengajuan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>

                                            @if ($dataPengajuan->otp_usulan_pengusul == null && $dataPengajuan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-oldat/'. $dataPengajuan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/aadb/usulan/proses-pembatalan/'. $dataPengajuan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $dataPengajuan->id_form_usulan }}">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @if ($dataPengajuan->status_pengajuan == '')
                                                @if($dataPengajuan->status_proses == 'belum proses')
                                                <span class="border border-warning">
                                                    <b class="text-warning p-3">Menunggu Persetujuan</b>
                                                </span>
                                                @elseif($dataPengajuan->status_proses == 'proses')
                                                <span class="border border-primary">
                                                    <b class="text-primary p-3">Proses</b>
                                                </span>
                                                @endif
                                                @elseif ($dataPengajuan->status_pengajuan == 'diterima')

                                                @else

                                                @endif
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <h5>Detail Pengajuan Usulan {{ $dataPengajuan->jenis_form_usulan }}
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
                                                    <div class="col-md-8">: {{ ucfirst(strtolower($dataPengajuan->nama_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-8">: {{ ucfirst(strtolower($dataPengajuan->keterangan_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Unit Kerja</label></div>
                                                    <div class="col-md-8">: {{ ucfirst(strtolower($dataPengajuan->unit_kerja)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-8">: {{ \Carbon\Carbon::parse($dataPengajuan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if($dataPengajuan->jenis_form == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-8">: {{ $dataPengajuan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                <div class="form-group row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Kendaraan
                                                    </h6>
                                                </div>
                                                @if($dataPengajuan->jenis_form == 1)
                                                @foreach($dataPengajuan -> usulanKendaraan as $dataKendaraan )
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
                                                @elseif($dataPengajuan->jenis_form == 2)
                                                @foreach($dataPengajuan -> usulanServis as $dataServis)
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
                                                @elseif($dataPengajuan->jenis_form == 3)
                                                @foreach($dataPengajuan -> usulanSTNK as $dataSTNK)
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
                                                @elseif($dataPengajuan->jenis_form == 4)
                                                @foreach($dataPengajuan -> usulanVoucher as $dataVoucher)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->merk_tipe_kendaraan }}</div>
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
                                                        @if($dataPengajuan->status_proses_id == 5)
                                                        <a href="{{ url('super-user/aadb/surat/surat-bast/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                        @endif
                                                    </span>
                                                    <span style="float: right;">
                                                        @if ($dataPengajuan->otp_usulan_pengusul != null)
                                                        <a href="{{ url('super-user/aadb/surat/surat-usulan/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                        @endif
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
        </div>
    </div>
</section>


@section('js')
<script>
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                "bVisible": false,
                "aTargets": [1, 2, 8, 9]
            }, ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan AADB',
                    exportOptions: {
                        columns: [0, 3, 4, 5, 6, 8, 9]
                    },
                    messageTop: datetime
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan AADB',
                    exportOptions: {
                        columns: [0, 1, 2, 4, 5, 6, 8, 9]
                    },
                    messageTop: datetime
                }
            ]
        }).buttons().container().appendTo('#table-pengajuan_wrapper .col-md-6:eq(0)');
    })
</script>
@endsection

@endsection
