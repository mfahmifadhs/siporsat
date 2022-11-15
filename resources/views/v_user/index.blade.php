@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0"> Selamat Datang, {{ Auth::user()->pegawai->nama_pegawai }}</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid text-center">
        <div class="card-header">
            <h4>Prosedur Pengajuan Usulan</h4>
        </div>
        <div class="card-body">
            <img src="{{ asset('gambar/sop/sop.png') }}" style="width: 50%;">
        </div>
    </div>
</section>
<!-- /.content -->

@endsection
