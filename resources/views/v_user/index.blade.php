@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                    <h1 class="m-0"> Selamat Datang, <small>{{ Auth::user()->pegawai->nama_pegawai }}</small></h1>
            </div>
        </div>
    </div>
</div>

@endsection
