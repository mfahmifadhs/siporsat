@extends('v_super_user.layout.app')

@section('content')

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
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
