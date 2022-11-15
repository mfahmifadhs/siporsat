@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Daftar ATK</h1>
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
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-body">
                        <table id="table-atk" class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>Jenis Barang</td>
                                    <td>Nama Barang</td>
                                    <td>Merk/Tipe</td>
                                </tr>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($atk as $dataAtk)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $dataAtk->KategoriATK->JenisATK->jenis_atk }}</td>
                                    <td>{{ $dataAtk->KategoriATK->kategori_atk }}</td>
                                    <td>{{ $dataAtk->merk_atk }}</td>
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
        $("#table-atk").DataTable({
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
