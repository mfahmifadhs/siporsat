@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0">Berita Acara Serah Terima</h1>
            </div>
            <div class="col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/'.$modul.'/dashboard') }}">Dashboard {{ $modul }}</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/'.$modul.'/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Berita Acara Serah Terima</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
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
                <a href="{{ url('admin-user/'.$modul.'/usulan/daftar/seluruh-usulan') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Berita Acara Serah Terima</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5 col-6">
                                <div class="form-group row">
                                    <label class="col-md-5">Tanggal Bast</label>
                                    <div class="col-md-7">: {{ \Carbon\carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                    <label class="col-md-5">Nomor Surat Usulan</label>
                                    <div class="col-md-7 text-capitalize">:
                                        {{ $bast->no_surat_usulan }}
                                    </div>
                                    <label class="col-md-5">Nomor Surat BAST</label>
                                    <div class="col-md-7 text-capitalize">:
                                        {{ $modul == 'atk' ? $bast->nomor_bast : $bast->no_surat_bast }}
                                    </div>
                                    <label class="col-md-5">Jenis Usulan</label>
                                    <div class="col-md-7 text-capitalize">: {{ $bast->jenis_form.' '.$modul }}</div>
                                    <label class="col-md-5">Status</label>
                                    <div class="col-md-7">:
                                        @if (!$bast->otp_bast_ppk)
                                        Menunggu Konfirmasi PPK
                                        @elseif (!$bast->otp_bast_pengusul)
                                        Menunggu Konfirmasi Pengusul
                                        @elseif (!$bast->otp_bast_kabag)
                                        Menunggu Konfirmasi Kabag RT
                                        @else
                                        Sudah Selesai BAST
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 col-6">
                                <div class="form-group row text-capitalize">
                                    <label class="col-md-4">Nama Pengusul</label>
                                    <div class="col-md-8">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                                    <label class="col-md-4">Jabatan Pengusul</label>
                                    <div class="col-md-8">: {{ $bast->keterangan_pegawai }}</div>
                                    <label class="col-md-4">Unit Kerja</label>
                                    <div class="col-md-8">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                    <label class="col-md-4">Status</label>
                                    <div class="col-md-8">:
                                        @if (!$bast->otp_bast_ppk)
                                        Menunggu Konfirmasi PPK
                                        @elseif (!$bast->otp_bast_pengusul)
                                        Menunggu Konfirmasi Pengusul
                                        @elseif (!$bast->otp_bast_kabag)
                                        Menunggu Konfirmasi Kabag RT
                                        @else
                                        Sudah Selesai BAST
                                        @endif
                                    </div>
                                    <label class="col-md-4">Aksi</label>
                                    <div class="col-md-8">:
                                        @if ($modul == 'atk')
                                        <a href="{{ url('admin-user/cetak-surat/bast-'.$modul.'/'. $bast->id_bast) }}" class="btn btn-danger btn-sm" rel="noopener" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        @else
                                        <a href="{{ url('admin-user/cetak-surat/bast-'.$modul.'/'. $bast->id_form_usulan) }}" class="btn btn-danger btn-sm" rel="noopener" target="_blank">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-md-12 col-12">
                                <label class="text-muted">Informasi Barang/Pekerjaan</label>
                                <table class="table table-bordered">
                                    <!-- Modul Oldat -->
                                    @if ($modul == 'oldat')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe Barang</th>
                                            @if($bast->jenis_form == 'pengadaan')
                                            <th>Spesifikasi</th>
                                            <th>Tahun </th>
                                            @else
                                            <th>Tahun</th>
                                            <th>Keterangan</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if($bast->jenis_form == 'pengadaan')
                                        @foreach($bast->detailPengadaan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kategori_barang_id }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_barang }}</td>
                                            <td>{{ $dataBarang->jumlah_barang.' '.$dataBarang->satuan_barang }}</td>
                                            <td>Rp {{ number_format($dataBarang->estimasi_biaya, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($bast->detailPerbaikan as $dataBarang)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataBarang->kode_barang.'.'.$dataBarang->nup_barang }}</td>
                                            <td>{{ $dataBarang->kategori_barang }}</td>
                                            <td>{{ $dataBarang->merk_tipe_barang }}</td>
                                            <td>
                                                @if (\Carbon\carbon::parse($dataBarang->tahun_perolehan)->isoFormat('Y') != -1)
                                                {{ \Carbon\carbon::parse($dataBarang->tahun_perolehan)->isoFormat('Y') }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>{{ $dataBarang->keterangan_perbaikan }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    @endif
                                    <!-- Modul ATK -->
                                    @if ($modul == 'atk')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Permintaan</th>
                                            <th>Penyerahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($bast->jenis_form == 'distribusi')
                                        @foreach($bast->detailBast()->orderBy('nama_barang', 'ASC')->orderBy('spesifikasi','ASC')->get() as $i => $detailAtk)
                                        <tr class="text-capitalize">
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->nama_barang)) }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->spesifikasi)) }}</td>
                                            <td>{{ $detailAtk->jumlah_disetujui.' '.$detailAtk->satuan }}</td>
                                            <td>{{ $detailAtk->jumlah_bast_detail.' '.$detailAtk->satuan }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($bast->detailBast2 as $i => $detailAtk)
                                        <tr>
                                            <td class="text-center">{{ $i + 1 }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->deskripsi_barang)) }}</td>
                                            <td>{{ ucfirst(strtolower($detailAtk->catatan)) }}</td>
                                            <td>{{ $detailAtk->jumlah_disetujui.' '.$detailAtk->satuan_barang }}</td>
                                            <td>{{ $detailAtk->jumlah_bast_detail.' '.$detailAtk->satuan_barang }}</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    @endif
                                    @if ($modul == 'aadb')
                                    @if($bast->jenis_form == 1)
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis AADB</th>
                                            <th>Jenis Kendaraan</th>
                                            <th>Merk / Tipe</th>
                                            <th>Tahun Kendaraan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-capitalize">
                                        @foreach($bast->usulanKendaraan as $dataPengadaan)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataPengadaan->jenis_aadb }}</td>
                                            <td>{{ $dataPengadaan->jenis_kendaraan }}</td>
                                            <td>{{ $dataPengadaan->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataPengadaan->tahun_kendaraan }}</td>
                                            <td>{{ $dataPengadaan->jumlah_pengajuan }} kendaraan</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @elseif($bast->jenis_form == '2')
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
                                    <tbody>
                                        @foreach($bast->usulanServis as $dataServis)
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
                                    @elseif($bast->jenis_form == '3')
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
                                        @foreach($bast->usulanSTNK as $dataSTNK)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataSTNK->no_plat_kendaraan }}</td>
                                            <td>{{ $dataSTNK->merk_tipe_kendaraan }}</td>
                                            <td>{{ $dataSTNK->pengguna }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_baru)->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @elseif($bast->jenis_form == '4')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan Pengadaan</th>
                                            <th>Jenis AADB</th>
                                            <th>No. Plat</th>
                                            <th>Kendaraan</th>
                                            <th>Kualifikasi</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-capitalize">
                                        @foreach($bast->usulanVoucher as $dataVoucher)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                                            <td>{{ $dataVoucher->jenis_aadb }}</td>
                                            <td>{{ $dataVoucher->no_plat_kendaraan }}</td>
                                            <td>{{ $dataVoucher->merk_tipe_kendaraan }}</td>
                                            <td>Kendaraan {{ $dataVoucher->kualifikasi }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                    @endif
                                    <!-- Modul Gedung dan Bangunan -->
                                    @if ($modul == 'gdn')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Lokasi Perbaikan</th>
                                            <th>Lokasi Spesifik</th>
                                            <th>Bidang Kerusakan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @foreach($bast->detailUsulanGdn as $dataGdn)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataGdn->lokasi_bangunan }}</td>
                                            <td>{!! nl2br(e($dataGdn->lokasi_spesifik)) !!}</td>
                                            <td>{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</td>
                                            <td>{!! nl2br(e($dataGdn->keterangan)) !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                    <!-- Modul Kerumahtanggaan -->
                                    @if ($modul == 'ukt')
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th style="width: 1%;">No</th>
                                            <th style="width: 20%;">Pekerjaan</th>
                                            <th>Spesifikasi Pekerjaan</th>
                                            <th style="width: 15%;">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody class="text-uppercase">
                                        @foreach($bast->detailUsulanUkt as $dataUkt)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataUkt->lokasi_pekerjaan }}</td>
                                            <td>{!! $dataUkt->spesifikasi_pekerjaan !!}</td>
                                            <td>{{ $dataUkt->keterangan }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                            </div>
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
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
        $("#table-alkom").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
    })
</script>

@endsection
@endsection
