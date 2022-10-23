@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Daftar Usulan ATK</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Daftar Usulan</li>
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
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12 float-right form-group">
                <div class="float-right">
                    <div class="card-tools">
                        <a class="btn btn-primary" href="{{ url('super-user/atk/usulan/pengadaan/barang') }}">
                            <i class="fa fa-shopping-cart fa-2x"></i> <br>
                            Usulan Pengadaan
                        </a>
                        <a class="btn btn-primary" href="">
                            <i class="fa fa-people-carry fa-2x"></i> <br>
                            Usulan Distribusi
                        </a>
                    </div>
                </div>

            </div>
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-body">
                        <table id="table-usulan" class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>Jenis Usulan</td>
                                    <td>Pengusul</td>
                                    <td>Unit Kerja</td>
                                    <td>Rencana Pengguna</td>
                                    <td>Status Pengajuan</td>
                                    <td>Status Proses</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($usulan as $dataUsulan)
                                <tr>
                                    <td>{{ $dataUsulan->tanggal_usulan }}</td>
                                    <td>{{ $dataUsulan->jenis_form }}</td>
                                    <td>{{ $dataUsulan->nama_pegawai }}</td>
                                    <td>{{ $dataUsulan->unit_kerja }}</td>
                                    <td>{{ $dataUsulan->rencana_pengguna }}</td>
                                    <td>{{ $dataUsulan->status_pengajuan }}</td>
                                    <td>{{ $dataUsulan->status_proses }}</td>
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

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
    })
</script>

@endsection
@endsection
