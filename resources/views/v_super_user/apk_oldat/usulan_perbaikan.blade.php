@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Buat Usulan Perbaikan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/pengajuan/daftar/semua-pengajuan') }}"> Daftar Pengajuan</a></li>
                    <li class="breadcrumb-item active">Buat Usulan Perbaikan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
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
        </div>
    </div>
</section>

@endsection
