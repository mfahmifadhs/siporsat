@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pengajuan</h1>
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
                                <th>Status</th>
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
                                    <td>{{ $dataPengajuan->status_pengajuan.' '.$dataPengajuan->status_proses }}</td>
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
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @if ($dataPengajuan->status_pengajuan == '')
                                                    @if($dataPengajuan->status_proses == 'belum proses')
                                                        <a class="btn btn-outline-warning disabled">Belum Diproses</a>
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
                                                        <h5>Detail Pengajuan Usulan {{ $dataPengajuan->jenis_form_usulan }} <hr></h5>
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
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-4"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-8">: {{ $dataPengajuan->rencana_pengguna }}</div>
                                                </div>
                                                <div class="form-group row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Kendaraan
                                                    </h6>
                                                </div>
                                                @if($dataPengajuan->jenis_form == 1)
                                                    @foreach($dataPengajuan -> usulanKendaraan as $dataKendaraan )
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-4"><label>Jenis AADB </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->jenis_aadb }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-4"><label>Jenis Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->jenis_kendaraan }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-4"><label>Merk </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->merk_kendaraan }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-4"><label>Tipe </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->tipe_kendaraan }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-4"><label>Tahun Kendaraan </label></div>
                                                        <div class="col-md-8">: {{ $dataKendaraan->tahun_kendaraan }}</div>
                                                    </div>
                                                    @endforeach
                                                @elseif($dataPengajuan->jenis_form == 2)
                                                    @foreach($dataPengajuan -> usulanServis as $dataServis)
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Kendaraan </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->merk_kendaraan.' '.$dataServis->tipe_kendaraan }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Kilometer Terakhir </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->kilometer_terakhir }} KM</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Tgl. Terakhir Servis </label></div>
                                                        <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_servis)->isoFormat('DD MMMM Y') }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Jatuh Tempo Servis </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->jatuh_tempo_servis }} KM</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Tgl. Terakhir Ganti Oli </label></div>
                                                        <div class="col-md-7">: {{ \Carbon\Carbon::parse($dataServis->tgl_terakhir_ganti_oli)->isoFormat('DD MMMM Y') }}</div>
                                                    </div>
                                                    <div class="form-group row mb-0">
                                                        <div class="col-md-5"><label>Jatuh Tempo Ganti Oli </label></div>
                                                        <div class="col-md-7">: {{ $dataServis->jatuh_tempo_ganti_oli }} KM</div>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <a href="{{ url('super-user/aadb/usulan/surat/'. $dataPengajuan->id_form_usulan) }}" class="btn btn-primary">
                                                    <i class="fas fa-file"></i> Surat Pengajuan
                                                </a>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
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
