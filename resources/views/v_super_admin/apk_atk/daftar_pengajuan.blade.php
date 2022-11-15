@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usulan Pengajuan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row form-group">
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
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Pengajuan Pengadaan Kendaraan Baru/Sewa</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered table-striped">
                            <thead>
                                <th>No</th>
                                <th>Pengusul</th>
                                <th style="width: 30%;">Usulan</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody class="small">
                                @foreach($pengajuan as $dataUsulan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($dataUsulan->tanggal_usulan)->isoFormat('DD MMMM Y') }} <br>
                                        No. Surat : {{ $dataUsulan->no_surat_usulan }} <br>
                                        Pengusul : {{ ucfirst(strtolower($dataUsulan->nama_pegawai)) }} <br>
                                        Unit Kerja : {{ ucfirst(strtolower($dataUsulan->unit_kerja)) }}
                                    </td>
                                    <td>
                                        @foreach($dataUsulan->detailUsulanAtk as $detailUsulan)
                                        {{ ucfirst(strtolower($detailUsulan->merk_atk)) }}<br>
                                        Jumlah Pengajuan : {{ $detailUsulan->jumlah_pengajuan.' '.$detailUsulan->satuan }}<br>
                                        Keterangan : {{ $detailUsulan->keterangan }}<br><br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($dataUsulan->lampiranAtk as $dataLampiran)
                                        Nomor Kontrak : {{ $dataLampiran->nomor_kontrak }} <br>
                                        Nomor Kwitansi : {{ $dataLampiran->nomor_kwitansi }} <br>
                                        Total Biaya : Rp {{ number_format($dataLampiran->nilai_kwitansi, 0, ',', '.') }} <br>
                                        <span>Bukti Kwitansi : </span> <br>
                                        <a href="{{ asset('gambar/kwitansi/atk_pengadaan/'. $dataLampiran->file_kwitansi) }}" class="btn btn-primary btn-xs" download>
                                            <i class="fas fa-download"></i> Unduh
                                        </a>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <span>Status Pengajuan : </span> <br>
                                        @if($dataUsulan->status_proses_id == 1)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> persetujuan</span>
                                        @elseif ($dataUsulan->status_proses_id == 2)
                                        <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                        @elseif ($dataUsulan->status_proses_id == 3)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                        @elseif ($dataUsulan->status_proses_id == 4)
                                        <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi kabag rt</span>
                                        @elseif ($dataUsulan->status_proses_id == 5)
                                        <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                        @endif <br>
                                        <span>Status Proses</span> <br>
                                        @if($dataUsulan->status_pengajuan_id == 1)
                                        <span class="badge badge-sm badge-pill badge-success">disetujui</span>
                                        @elseif($dataUsulan->status_pengajuan_id == 2)
                                        <span class="badge badge-sm badge-pill badge-danger">ditolak</span>
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
                                            <a class="dropdown-item btn" href="{{ url('super-admin/atk/usulan/hapus/'. $dataUsulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin menghapus usulan ini ?')">
                                                <i class="fas fa-trash"></i> Hapus
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
                                                        <div class="col-md-4"><label>Jenis atk </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->jenis_atk }}</div>
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
                                                        <a href="{{ url('super-user/atk/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                        @endif
                                                    </span>
                                                    <span style="float: right;">
                                                        <a href="{{ url('super-user/atk/surat/surat-usulan/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary">
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
