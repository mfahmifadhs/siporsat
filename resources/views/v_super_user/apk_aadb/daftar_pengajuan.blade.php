@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pengajuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
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
                        <h4 class="card-title">Daftar Pengajuan Pengadaan Kendaraan Baru/Sewa</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered table-striped">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>ID Form</th>
                                <th>Kode Form</th>
                                <th>Pengusul</th>
                                <th>Jenis Pengajuan</th>
                                <th>Rencana Pengguna</th>
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
                                    <td>{{ $dataPengajuan->id_form_usulan }}</td>
                                    <td>{{ $dataPengajuan->kode_form }}</td>
                                    <td>{{ $dataPengajuan->nama_pegawai }}</td>
                                    <td>{{ $dataPengajuan->jenis_form_usulan }}</td>
                                    <td>{{ $dataPengajuan->rencana_pengguna }}</td>
                                    <td class="text-center">
                                        @if($dataPengajuan->status_pengajuan_id == 1)
                                        <span class="border border-success text-success p-1">disetujui</span>
                                        @elseif($dataPengajuan->status_pengajuan_id == 2)
                                        <span class="border border-danger text-danger p-1">ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($dataPengajuan->status_proses_id == 1)
                                        <span class="border border-warning text-warning p-1">menunggu persetujuan</span>
                                        @elseif ($dataPengajuan->status_proses_id == 2)
                                        <span class="border border-warning text-warning p-1">usulan sedang diproses</span>
                                        @elseif ($dataPengajuan->status_proses_id == 3)
                                        <span class="border border-success text-success p-1">menunggu konfirmasi pengusul</span>
                                        @elseif ($dataPengajuan->status_proses_id == 4)
                                        <span class="border border-success text-success p-1">menunggu konfirmasi kabag rt</span>
                                        @elseif ($dataPengajuan->status_proses_id == 5)
                                        <span class="border border-success text-success p-1">selesai</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $dataPengajuan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
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
                                                    <div class="col-md-8">: {{ $dataPengajuan->nama_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-8">: {{ $dataPengajuan->jabatan.' '.$dataPengajuan->keterangan_pegawai }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Unit Kerja</label></div>
                                                    <div class="col-md-8">: {{ $dataPengajuan->unit_kerja }}</div>
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
                                                        <div class="col-md-4"><label>Merk </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->merk_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Tipe </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Tahun Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->tahun_kendaraan }}</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @elseif($dataPengajuan->jenis_form == 2)
                                                @foreach($dataPengajuan -> usulanServis as $dataServis)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-5"><label>Kendaraan </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->merk_kendaraan.' '.$dataServis->tipe_kendaraan }}</div>
                                                        <div class="col-md-5"><label>Kilometer Terakhir </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->kilometer_terakhir }} KM</div>
                                                        <div class="col-md-5"><label>Tgl. Terakhir Servis </label></div>
                                                        <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_servis)->isoFormat('DD MMMM Y') }}</div>
                                                        <div class="col-md-5"><label>Jatuh Tempo Servis </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->jatuh_tempo_servis }} KM</div>
                                                        <div class="col-md-5"><label>Tgl. Terakhir Ganti Oli </label></div>
                                                        <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_ganti_oli)->isoFormat('DD MMMM Y') }}</div>
                                                        <div class="col-md-5"><label>Jatuh Tempo Ganti Oli </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->jatuh_tempo_ganti_oli }} KM</div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @elseif($dataPengajuan->jenis_form == 3)
                                                @foreach($dataPengajuan -> usulanSTNK as $dataSTNK)
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-4"><label>Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataSTNK->merk_kendaraan.' '.$dataSTNK->tipe_kendaraan }}</div>
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
                                                        <div class="col-md-8">: {{ $dataVoucher->merk_kendaraan.' '.$dataVoucher->tipe_kendaraan }}</div>
                                                        <div class="col-md-4"><label>Voucher 25 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_25 }}</div>
                                                        <div class="col-md-4"><label>Voucher 50 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_50 }}</div>
                                                        <div class="col-md-4"><label>Voucher 100 </label></div>
                                                        <div class="col-md-8">: {{ $dataVoucher->voucher_100 }} L</div>
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
                                                        @if($dataPengajuan->status_proses_id == 5)
                                                        <a href="{{ url('super-user/aadb/surat/surat-bast/'. $dataPengajuan->otp_bast_ppk) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                        @endif
                                                    </span>
                                                    <span style="float: right;">
                                                        <a href="{{ url('super-user/aadb/surat/surat-usulan/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-primary">
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
        </div>
    </div>
</section>


@section('js')
<script>
    $(function() {
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["pdf", "excel"]
        }).buttons().container().appendTo('#table-rekap_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
