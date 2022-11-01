@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Dashboard Gedung & Bangunan</h4>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Usulan Pengajuan</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <small>28 Oktober 2022</small><br>
                                <a href="#" class="text-primary font-weight-bold">Biro Perencanaan & Anggaran</a>
                                <p class="small">Samsung 32" 1080p 60Hz LED Smart HDTV.</p>
                            </div>
                            <div class="col-md-4">
                            <div class="mt-3 p-1 bg-warning btn-xs text-dark small text-center font-weight-bold">Menunggu Persetujuan</div>
                                <!-- <span class="badge badge-xs badge-pill badge-warning mt-4">Menunggu Persetujuan</span> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
