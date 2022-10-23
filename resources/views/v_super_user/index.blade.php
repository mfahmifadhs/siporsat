@extends('v_super_user.app')

@section('content')

<!-- content-wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Selamat Datang, <small>{{ Auth::user()->pegawai->nama_pegawai }}</small></h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-12 form-group">
                    <div class="card bg-primary" style="height: 100%;">
                        <div class="card-body text-center">
                            <p class="font-weight-bold mt-4">
                                <span class="fa-lg"> PENGELOLAAN <br> OLAH DATA BMN & MEUBELAIR <br> (OLDAT)</span>
                            </p>
                            <b>Pengajuan Usulan : {{ $usulanOldat }} <br> <small> (Pengajuan Diproses) </small></b> <br>
                        </div>
                        <a href="{{ url('super-user/oldat/dashboard') }}" class="small-box-footer text-white">
                            <div class="card-footer text-center">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-12 form-group">
                    <div class="card bg-primary" style="height: 100%;">
                        <div class="card-body text-center">
                            <p class="font-weight-bold mt-4">
                                <span class="fa-lg"> PENGELOLAAN <br> ALAT ANGKUTAN DARAT BERMOTOR (AADB)</span>
                            </p>
                            <b>Pengajuan Usulan : {{ $usulanAadb }} <br> <small> (Pengajuan Belum Diproses) </small></b> <br>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ url('super-user/aadb/dashboard') }}" class="small-box-footer text-white">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 form-group">
                    <div class="card bg-primary" style="height: 100%;">
                        <div class="card-body text-center">
                            <p class="font-weight-bold mt-4">
                                <span class="fa-lg"> PENGELOLAAN <br> ALAT TULIS KANTOR <br> (ATK)</span>
                            </p>
                            <b>Pengajuan Usulan : 0 </b> <br>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ url('super-user/atk/dashboard') }}" class="small-box-footer text-white">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 form-group">
                    <div class="card bg-primary" style="height: 100%;">
                        <div class="card-body text-center">
                            <p class="font-weight-bold mt-4">
                                <span class="fa-lg"> PENGELOLAAN <br> PEMELIHARAAN GEDUNG <br> (GDG)</span>
                            </p>
                            <b>Pengajuan Usulan : 0</b> <br>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="small-box-footer text-white">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-12 form-group">
                    <div class="card bg-primary" style="height: 100%;">
                        <div class="card-body text-center">
                            <p class="font-weight-bold mt-4">
                                <span class="fa-lg"> PENGELOLAAN <br> RUMAH DINAS NEGARA <br>(RDN)</span>
                            </p>
                            <b>Total Rumah Dinas : 0 <br> <small> (Masa Berlaku SIP akan Habis) </small></b>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="small-box-footer text-white">
                                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Main Content -->
</div>
<!-- /.content-wrapper -->

@endsection
