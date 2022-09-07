@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rekapitulasi Pengelolaan Olah Data (OLDAT)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"> Dashboard OLDAT</a></li>
                    <li class="breadcrumb-item active">Dashboard OLDAT</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Rekapitulasi Barang Bersarkan Tahun Perolehan dan Tim Kerja</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                @foreach($rekap as $kategori => $kategoriBarang)
                                <th>{{ $kategori }}</th>
                                <th class="text-center">Jumlah Barang</th>
                                @foreach($kategoriBarang as $tiker => $total)
                                <tr>
                                    <td>{{ $tiker }}</td>
                                    <td class="text-center">{{ $total }} </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
