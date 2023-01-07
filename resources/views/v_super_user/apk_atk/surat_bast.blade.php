@extends('v_super_user.layout.app')

@section('css')
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0mm;
        /* this affects the margin in the printer settings */
        margin-top: -22vh;
        margin-left: -1.8vh;
    }

    .header-confirm .header-text-confirm {
        padding-top: 8vh;
        line-height: 2vh;
    }

    .header-confirm img {
        margin-top: 3vh;
        height: 2vh;
        width: 2vh;
    }

    .print,
    .pdf,
    .logo-header,
    .nav-right {
        display: none;
    }

    nav,
    footer {
        display: none;
    }
</style>
@endsection

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Berita Acara Serah Terima</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/atk/dashboard/') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/usulan/daftar/seluruh-pengajuan') }}">Daftar Pengajuan Barang</a></li>
                    <li class="breadcrumb-item active">BAST {{ $bast->otp_bast_pengusul }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<!-- Content Header -->

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 form-group">
                <a href="{{ url('super-user/atk/dashboard/pengadaan') }}" class="btn btn-primary print mr-2">
                    <i class="fas fa-home"></i>
                </a>
                @if($bast->status_proses_id == 4)
                <a href="{{ url('super-user/atk/surat/print-surat-bast/'. $bast->id_form_usulan) }}" rel="noopener" target="_blank" class="btn btn-danger pdf">
                    <i class="fas fa-print"></i>
                </a>
                @endif
            </div>
            <div class="col-md-12 form-group ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h2 class="page-header ml-4">
                                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}">
                                </h2>
                            </div>
                            <div class="col-md-8 text-center">
                                <h2 class="page-header">
                                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>kementerian kesehatan republik indonesia</b></h5>
                                    <h5 style="font-size: 30px;text-transform:uppercase;"><b>{{ $bast->unit_utama }}</b></h5>
                                    <p style="font-size: 20px;"><i>Jl. H.R. Rasuna Said Blok X.5 Kav. 4-9, Blok A, 2nd Floor, Jakarta 12950<br>Telp.: (62-21) 5201587, 5201591 Fax. (62-21) 5201591</i></p>
                                </h2>
                            </div>
                            <div class="col-md-2">
                                <h2 class="page-header">
                                    <img src="{{ asset('dist_admin/img/logo-germas.png') }}" style="width: 128px; height: 128px;">
                                </h2>
                            </div>
                            <div class="col-md-12">
                                <hr style="border-width: medium;border-color: black;">
                            </div>
                        </div>
                        <div class="row text-capitalize">
                            <div class="col-md-12 form-group">
                                <div class="form-group row mb-3 text-center">
                                    <div class="col-md-12 text-uppercase">
                                        berita acara serah terima <br>
                                        nomor surat : {{ $bast->no_surat_bast }}
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Pengusul</div>
                                    <div class="col-md-10">: {{ ucfirst(strtolower($bast->nama_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Jabatan</div>
                                    <div class="col-md-9">: {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Unit Kerja</div>
                                    <div class="col-md-9">: {{ ucfirst(strtolower($bast->unit_kerja)) }}</div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Tanggal BAST</div>
                                    <div class="col-md-9">: {{ \Carbon\Carbon::parse($bast->tanggal_bast)->isoFormat('DD MMMM Y') }}</div>
                                </div>
                                @if($bast->rencana_pengguna != null)
                                <div class="form-group row mb-0">
                                    <div class="col-md-2">Rencana Pengguna</div>
                                    <div class="col-md-9">: {{ $bast->rencana_pengguna }}</div>
                                </div>
                                @endif
                            </div>
                            <div class="col-12 table-responsiv mb-5">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Merk/Tipe</th>
                                            <th>Permintaan</th>
                                            <th>Disetujui</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody>
                                        @if ($form->jenis_form == 'pengadaan')
                                        @foreach($bast->pengadaanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td>{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                                            <td>{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                                            <td>
                                                {{ $dataAtk->status }}
                                                @if ($dataAtk->keterangan != null)
                                                ({{ $dataAtk->keterangan }})
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        @foreach($bast->permintaanAtk as $dataAtk)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                                            <td>{{ $dataAtk->spesifikasi }}</td>
                                            <td>{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                                            <td>{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                                            <td>
                                                {{ $dataAtk->status }}
                                                @if ($dataAtk->keterangan != null)
                                                ({{ $dataAtk->keterangan }})
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Yang Menyerahkan, <br> Pejabat Pembuat Komitmen</label>
                                    <label class="col-sm-4">Yang Menerima, <br> {{ ucfirst(strtolower($bast->keterangan_pegawai)) }}</label>
                                    <label class="col-sm-4">Mengetahui, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-atk/'.$bast->otp_bast_pengusul) !!}</label>
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-atk/'.$bast->otp_bast_pengusul) !!}</label>
                                    <label class="col-sm-4">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/bast-atk/'.$bast->otp_bast_pengusul) !!}</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row text-center">
                                    <label class="col-sm-4">Marten Avero, Skm</label>
                                    <label class="col-sm-4">{{ ucfirst(strtolower($bast->nama_pegawai)) }}</label>
                                    <label class="col-sm-4">{{ ucfirst(strtolower($pimpinan->nama_pegawai)) }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
