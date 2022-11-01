@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->username == 'roum_pgudang')
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
            <div class="col-md-12 form-group">
                <h4 class="m-0">Usulan Pengajuan</h4>
            </div>
            <div class="col-md-6 form-group">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Menunggu Persetujuan PPK</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="" class="table m-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengusul</th>
                                    <th>Unit Kerja</th>
                                    <th>Status</th>
                                    @if(Auth::user()->jabatan_id == 5)
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataUsulan)
                                @if($dataUsulan->status_proses_id == 1)
                                <tr>
                                    <td>{{ $dataUsulan->tanggal_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td><span class="badge badge-warning">MENUNGGU PERSETUJUAN PPK</span></td>
                                    @if(Auth::user()->pegawai->jabatan_id == 5)
                                    <td>
                                        <a type="button" class="btn btn-success btn-sm text-white mt-2" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                        <a href="{{ url('super-user/atk/usulan/proses-ditolak/'. $dataUsulan->id_form_usulan) }}" class="btn btn-danger btn-sm text-white mt-2" onclick="return confirm('Apakah pengajuan ini ditolak ?')">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Sedang Proses Pembelian</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="" class="table m-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengusul</th>
                                    <th>Unit Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataUsulan)
                                @if($dataUsulan->status_proses_id == 2)
                                <tr>
                                    <td>{{ $dataUsulan->tanggal_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td><span class="badge badge-warning">SEDANG PROSES PEMBELIAN</span></td>
                                    @if(Auth::user()->pegawai->jabatan_id == 5)
                                    <td>
                                        <a type="button" class="btn btn-success btn-sm text-white mt-2" data-toggle="modal" data-target="#modal-info-{{ $dataUsulan->id_form_usulan }}">
                                            <i class="fas fa-check-circle"></i>
                                        </a>
                                        <a href="{{ url('super-user/atk/usulan/proses-ditolak/'. $dataUsulan->id_form_usulan) }}" class="btn btn-danger btn-sm text-white mt-2" onclick="return confirm('Apakah pengajuan ini ditolak ?')">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Sedang Proses Input</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="" class="table m-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengusul</th>
                                    <th>Unit Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataUsulan)
                                @if($dataUsulan->status_proses_id == 4)
                                <tr>
                                    <td>{{ $dataUsulan->tanggal_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td><span class="badge badge-warning">SEDANG PROSES INPUT</span></td>
                                    @if(Auth::user()->username == 'roum_pgudang')
                                    <td>
                                        <a href="{{ url('admin-user/atk/usulan/input/'. $dataUsulan->id_form_usulan) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus-circle"></i> Input Barang
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Selesai</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="" class="table m-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pengusul</th>
                                    <th>Unit Kerja</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataUsulan)
                                @if($dataUsulan->status_proses_id == 5)
                                <tr>
                                    <td>{{ $dataUsulan->tanggal_usulan }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td><span class="badge badge-success">SELESAI</span></td>
                                    <td>
                                        <a href="{{ url('admin-user/atk/surat/surat-bast/'. $dataUsulan->id_form_usulan) }}" class="btn btn-primary" title="Surat Bast">
                                            <i class="fas fa-file"></i>
                                        </a>
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
</section>
@else
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-lg-6 form-group">
                <a href="{{ url('super-admin/oldat/dashboard') }}">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN BMN <br> OLAH DATA <br> (OLDAT)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN BMN <br> ALAT ANGKUTAN DARAT BERMOTOR <br> (AADT)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN <br> ALAT TULIS KANTOR <br> (ATK)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold" style="margin-top: 40px;">PENGELOLAAN PEMELIHARAAN</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection
