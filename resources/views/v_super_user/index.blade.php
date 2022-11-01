@extends('v_super_user.layout.app')

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-3 form-group">
                <h5 class="col-md-12 m-0 mb-3 text-center">Usulan Pengadaan/Pemeliharaan <br> Olah Data BMN & Meubelair</h5>
                <div class="col-md-12">
                    <a href="{{ url('super-user/oldat/pengajuan/status/1') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Menunggu Persetujuan</h5>
                                <h1>{{ $usulanOldat->where('status_proses_id', 1)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/oldat/pengajuan/status/2') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Sedang Diproses</h5>
                                <h1>{{ $usulanOldat->where('status_proses_id', 2)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/oldat/pengajuan/status/4') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Konfirmasi BAST</h5>
                                <h1>{{ $usulanOldat->where('status_proses_id', 4)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/oldat/pengajuan/status/5') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Selesai</h5>
                                <h1>{{ $usulanOldat->where('status_proses_id', 5)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <h5 class="col-md-12 m-0 mb-3 text-center">Usulan Pemeliharaan <br> Alat Angkutan Darat Bermotor (AADB)</h5>
                <div class="col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/status/1') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Menunggu Persetujuan</h5>
                                <h1>{{ $usulanAadb->where('status_proses_id', 1)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/status/2') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Sedang Diproses</h5>
                                <h1>{{ $usulanAadb->where('status_proses_id', 2)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/status/4') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Konfirmasi BAST</h5>
                                <h1>{{ $usulanAadb->where('status_proses_id', 4)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/aadb/usulan/status/5') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Selesai</h5>
                                <h1>{{ $usulanAadb->where('status_proses_id', 5)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <h5 class="col-md-12 m-0 mb-3 text-center">Usulan Pengadaan/Distribusi<br> Alat Tulis Kantor (ATK)</h5>
                <div class="col-md-12">
                    <a href="{{ url('super-user/atk/usulan/status/1') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Menunggu Persetujuan</h5>
                                <h1>{{ $usulanAtk->where('status_proses_id', 1)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/atk/usulan/status/2') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Sedang Diproses</h5>
                                <h1>{{ $usulanAtk->where('status_proses_id', 2)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/atk/usulan/status/4') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Konfirmasi BAST</h5>
                                <h1>{{ $usulanAtk->where('status_proses_id', 4)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/atk/usulan/status/5') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Selesai</h5>
                                <h1>{{ $usulanAtk->where('status_proses_id', 5)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <h5 class="col-md-12 m-0 mb-3 text-center">Usulan Pemeliharan <br> Gedung dan Bangunan</h5>
                <div class="col-md-12">
                    <a href="{{ url('super-user/gdn/usulan/status/1') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Menunggu Persetujuan</h5>
                                <h1>{{ $usulanGdn->where('status_proses_id', 1)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/gdn/usulan/status/2') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Sedang Diproses</h5>
                                <h1>{{ $usulanGdn->where('status_proses_id', 2)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/gdn/usulan/status/4') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Konfirmasi BAST</h5>
                                <h1>{{ $usulanGdn->where('status_proses_id', 4)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="{{ url('super-user/gdn/usulan/status/5') }}" style="color: black;">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <h5>Selesai</h5>
                                <h1>{{ $usulanGdn->where('status_proses_id', 5)->count() }}</h1>
                                <p>Usulan</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
