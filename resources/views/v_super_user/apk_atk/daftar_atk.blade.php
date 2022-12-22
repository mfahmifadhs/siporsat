@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar ATK</li>
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
            <div class="col-md-6 col-12 form-group">
                <div class="card card-primary card-outline" style="height: 100%;">
                    <div class="card-header border-0">
                        <h3 class="card-title">Daftar Pengadaan ATK</h3>
                    </div>
                    <div class="card-body table-responsive p-4">
                        <table id="table-atk-1" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $row)
                                @if($row->jenis_form == 'pengadaan')
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row->tanggal_usulan }}</td>
                                    <td>{{ $row->merk_atk }}</td>
                                    <td>{{ $row->total_atk }}</td>
                                    <td>{{ $row->satuan }}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12 form-group">
                <div class="card card-primary card-outline" style="height: 100%;">
                    <div class="card-header border-0">
                        <h3 class="card-title">Daftar Distribusi ATK</h3>
                    </div>
                    <div class="card-body table-responsive p-4">
                        <table id="table-atk-2" class="table table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $row)
                                @if($row->jenis_form == 'distribusi')
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $row->tanggal_usulan }}</td>
                                    <td>{{ $row->merk_atk }}</td>
                                    <td>{{ $row->total_atk }}</td>
                                    <td>{{ $row->satuan }}</td>
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

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        for (let i = 1; i <= 2; i++) {
            $("#table-atk-" + i).DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "info": false,
                "paging": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ]
            })
        }
    })
</script>

@endsection
@endsection
