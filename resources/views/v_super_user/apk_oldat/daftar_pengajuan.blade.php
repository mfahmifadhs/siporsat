@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Usulan Pengajuan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan Oldat</li>
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
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @elseif ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p style="color:white;margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>

            <div class="col-md-12 float-right form-group">
                <div class="float-right">
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/oldat/pengajuan/usulan/pengadaan') }}">
                        <i class="fa fa-laptop fa-2x py-1"></i> <br>
                        Usulan Pengadaan
                    </a>
                    <a class="btn btn-primary btn-sm" href="{{ url('super-user/oldat/pengajuan/usulan/perbaikan') }}">
                        <i class="fa fa-tools fa-2x py-1"></i> <br>
                        Usulan Perbaikan
                    </a>
                </div>
            </div>

            <div class="col-md-12 form-group">
                <div class="card card-primary card-outline" id="accordion">
                    <div class="card-header">
                        <b class="font-weight-bold text-primary card-title" style="font-size:medium;">
                            <i class="fas fa-table"></i> TABEL USULAN OLDAT & MEUBELAIR
                        </b>
                        <div class="card-tools">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                <span class="btn btn-primary btn-md">
                                    <i class="fas fa-filter"></i> Filter
                                </span>
                            </a>
                        </div>
                    </div>
                    <div id="collapseTwo" class="collapse" data-parent="#accordion">
                        <div class="card-header">
                            <form method="POST" action="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label>Pilih Tanggal</label>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <small>Tanggal Awal</small>
                                                <input type="date" class="form-control border-dark" name="start_date">
                                            </div>
                                            <div class="col-md-2 text-center" style="margin-top: 30px;"> ➖ </div>
                                            <div class="col-md-5">
                                                <small>Tanggal Akhir</small>
                                                <input type="date" class="form-control border-dark" name="end_date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Unit Kerja</label> <br>
                                        <small>Pilih Unit Kerja</small>
                                        <select name="unit_kerja_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Unit Kerja --</option>
                                            @foreach ($uker as $dataUker)
                                            <option value="{{ $dataUker->id_unit_kerja }}">{{ $dataUker->unit_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Status Proses</label> <br>
                                        <small>Pilih Status Proses Pengajuan</small>
                                        <select name="status_proses_id" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Status Proses --</option>
                                            <option value="1">1 - Menunggu Persetujuan Kabag RT</option>
                                            <option value="2">2 - Sedang Diproses oleh PPK</option>
                                            <option value="3">3 - Menunggu Konfirmasi BAST Pengusul</option>
                                            <option value="4">4 - Menunggu Konfirmasi BAST Kabag RT</option>
                                            <option value="5">5 - Selesai BAST</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Jenis Usulan</label> <br>
                                        <small>Pilih Jenis Usulan Oldat & Meubelair</small>
                                        <select name="jenis_form" class="form-control text-capitalize border-dark">
                                            <option value="">-- Pilih Jenis Usulan --</option>
                                            <option value="pengadaan">Pengadaan Baru/Sewa</option>
                                            <option value="perbaikan">Perbaikan</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <label class="mt-4">&nbsp;</label> <br>
                                        <button class="btn btn-primary">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                        <a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}" class="btn btn-danger">
                                            <i class="fas fa-undo"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered text-capitalize">
                            <thead>
                                <tr>
                                    <th style="width: 1%;">No</th>
                                    <th style="width: 10%;">Tanggal</th>
                                    <th style="width: 5%;">No. Surat</th>
                                    <th style="width: 15%;">Pengusul</th>
                                    <th style="width: 15%;">Usulan</th>
                                    <th class="text-center" style="width: 11%;">Status Pengajuan</th>
                                    <th class="text-center" style="width: 10%;">Status Proses</th>
                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                </tr>
                            </thead>
                            <?php $no = 1; ?>
                            <tbody>
                                @foreach($formUsulan as $usulan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMM Y | HH:mm') }}</td>
                                    <td class="text-uppercase">
                                        {{ $usulan->no_surat_usulan }} <br>
                                        {{ $usulan->jenis_form }}
                                    </td>
                                    <td>{{ $usulan->nama_pegawai }} <br> {{ $usulan->unit_kerja }}</td>
                                    <td class="text-uppercase">
                                        @if ($usulan->jenis_form == 'pengadaan')
                                        @foreach ($usulan->detailPengadaan as $detailOldat)
                                        {!! nl2br(e(Str::limit($detailOldat->kategori_barang, 50))) !!}
                                        @endforeach
                                        @else
                                        @foreach ($usulan->detailPerbaikan as $detailOldat)
                                        {!! nl2br(e(Str::limit($detailOldat->merk_tipe_barang, 50))) !!}
                                        @endforeach
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <h6 class="mt-2">
                                            @if($usulan->status_pengajuan_id == 1)
                                            <span class="badge badge-sm badge-pill badge-success">
                                                Disetujui
                                            </span>
                                            @elseif($usulan->status_pengajuan_id == 2)
                                            <span class="badge badge-sm badge-pill badge-danger">Ditolak</span>
                                            @if ($usulan->keterangan != null)
                                            <p class="small mt-2 text-danger">{{ $usulan->keterangan }}</p>
                                            @endif
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center text-capitalize">
                                        <h6 class="mt-2">
                                            @if($usulan->status_proses_id == 1)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu persetujuan <br> kabag RT</span>
                                            @elseif ($usulan->status_proses_id == 2)
                                            <span class="badge badge-sm badge-pill badge-warning">sedang <br> diproses ppk</span>
                                            @elseif ($usulan->status_proses_id == 3)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu <br> konfirmasi pengusul</span>
                                            @elseif ($usulan->status_proses_id == 4)
                                            <span class="badge badge-sm badge-pill badge-warning">menunggu konfirmasi BAST <br> kabag RT</span>
                                            @elseif ($usulan->status_proses_id == 5)
                                            <span class="badge badge-sm badge-pill badge-success">selesai</span>
                                            @endif
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            @if ($usulan->status_proses_id == 3 && $usulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-oldat/'. $usulan->id_form_usulan) }}"
                                                onclick="return confirm('Apakah barang telah diterima?')">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $usulan->status_proses_id == 1)
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/pengajuan/persetujuan/'. $usulan->id_form_usulan) }}">
                                                <i class="fas fa-arrow-alt-circle-right"></i> Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 5 && $usulan->status_proses_id == 2 )
                                            <a class="dropdown-item btn" href="{{ url('super-user/ppk/oldat/pengajuan/'.$usulan->jenis_form.'/'. $usulan->id_form_usulan) }}" onclick="return confirm('Selesai Proses Usulan')">
                                                <i class="fas fa-check-circle"></i> Selesai Proses
                                            </a>
                                            @elseif (Auth::user()->pegawai->jabatan_id == 2 && $usulan->status_proses_id == 4)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-oldat/'. $usulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Konfirmasi
                                            </a>
                                            @endif
                                            <a class="dropdown-item btn" type="button" data-toggle="modal" data-target="#modal-info-{{ $usulan->id_form_usulan }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                            @if ($usulan->otp_usulan_pengusul == null && $usulan->pegawai_id == Auth::user()->pegawai_id)
                                            <a class="dropdown-item btn" href="{{ url('super-user/verif/usulan-oldat/'. $usulan->id_form_usulan) }}">
                                                <i class="fas fa-file-signature"></i> Verifikasi
                                            </a>
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/pengajuan/proses-pembatalan/'. $usulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-times-circle"></i> Batal
                                            </a>
                                            @endif
                                            @if (Auth::user()->pegawai->jabatan_id == 2 && $usulan->status_pengajuan_id == NULL)
                                            <a class="dropdown-item btn" href="{{ url('super-user/oldat/pengajuan/hapus/'. $usulan->id_form_usulan) }}" onclick="return confirm('Apakah anda ingin membatalkan usulan ini ?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-info-{{ $usulan->id_form_usulan }}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-body text-capitalize">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <h5>Detail Pengajuan Usulan {{ $usulan->jenis_form_usulan }}
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
                                                    <div class="col-md-2"><label>Nomor Surat </label></div>
                                                    <div class="col-md-10 text-uppercase">: {{ $usulan->no_surat_usulan }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Nama Pengusul </label></div>
                                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Jabatan Pengusul </label></div>
                                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Unit Kerja</label></div>
                                                    <div class="col-md-10">: {{ ucfirst(strtolower($usulan->unit_kerja)) }}</div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Tanggal Usulan </label></div>
                                                    <div class="col-md-10">: {{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
                                                </div>
                                                @if($usulan->jenis_form == 'pengadaan')
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Rencana Pengguna </label></div>
                                                    <div class="col-md-10">: {{ $usulan->rencana_pengguna }}</div>
                                                </div>
                                                @endif
                                                @if ($usulan->otp_usulan_pengusul != null)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Surat Usulan </label></div>
                                                    <div class="col-md-10">:
                                                        <a href="{{ url('super-user/oldat/surat/surat-usulan/'. $usulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat Usulan Pengajuan
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($usulan->status_proses_id > 3 && $usulan->status_pengajuan_id == 1)
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-2"><label>Surat BAST </label></div>
                                                    <div class="col-md-10">:
                                                        <a href="{{ url('super-user/oldat/surat/surat-bast/'. $usulan->id_form_usulan) }}">
                                                            <i class="fas fa-file"></i> Surat BAST
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="form-group row mt-4">
                                                    <h6 class="col-md-12 font-weight-bold text-muted">
                                                        Informasi Barang
                                                    </h6>
                                                </div>
                                                <div class="form-group row">
                                                    @if ($usulan->jenis_form == 'pengadaan')
                                                    <div class="col-md-12">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">Nama Barang</div>
                                                            <div class="col-sm-2">Merk/Tipe</div>
                                                            <div class="col-sm-3">Spesifikasi</div>
                                                            <div class="col-sm-2">Jumlah</div>
                                                            <div class="col-sm-2">Estimasi Biaya</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($usulan->detailPengadaan as $i => $dataOldat)
                                                        <div class="form-group row text-uppercase small">
                                                            <div class="col-sm-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->kategori_barang }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->merk_barang }}</div>
                                                            <div class="col-sm-3">{{ $dataOldat->spesifikasi_barang }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->jumlah_barang.' '.$dataOldat->satuan_barang }}</div>
                                                            <div class="col-sm-2">Rp {{ number_format($dataOldat->estimasi_biaya, 0, ',', '.') }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                    @elseif ($usulan->jenis_form == 'perbaikan')
                                                    <div class="col-md-12">
                                                        <hr class="bg-secondary">
                                                        <div class="form-group row font-weight-bold">
                                                            <div class="col-sm-1 text-center">No</div>
                                                            <div class="col-sm-2">Nama Barang</div>
							    <div class="col-sm-2">Kode Barang</div>
                                                            <div class="col-sm-3">Merk/Tipe</div>
                                                            <div class="col-sm-2">Pengguna</div>
                                                            <div class="col-sm-2">Keterangan</div>
                                                        </div>
                                                        <hr class="bg-secondary">
                                                        @foreach($usulan->detailPerbaikan as $i => $dataOldat)
                                                        <div class="form-group row text-uppercase small">
                                                            <div class="col-sm-1 text-center">{{ $i + 1 }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->kategori_barang }}</div>
							    <div class="col-sm-2">{{ $dataOldat->kode_barang.'.'.$dataOldat->nup_barang }}</div>
                                                            <div class="col-sm-3">{{ $dataOldat->merk_tipe_barang.' '.Carbon\carbon::parse($dataOldat->tahun_perolehan)->isoFormat('Y') }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->pengguna_barang }}</div>
                                                            <div class="col-sm-2">{{ $dataOldat->keterangan_perbaikan }}</div>
                                                        </div>
                                                        <hr>
                                                        @endforeach
                                                    </div>
                                                    @endif
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
            ]
        })
    })
</script>
@endsection

@endsection
