<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPORSAT KEMENKES RI</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ url('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('dist_admin/css/adminlte.css') }}">
</head>

<body class="hold-transition sidebar-collapse layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="{{ url('super-user/dashboard') }}" class="navbar-brand">
                    <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}" alt="Siporsat" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Sistem Informasi Pengelolaan Perkantoran Terpusat</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link" data-toggle="dropdown" href="#">
                                <i class="far fa-user-circle"></i>
                                <b>{{ Auth::user()->pegawai->nama_pegawai }}</b>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <span class="dropdown-item dropdown-header">
                                    <p class="text-capitalize">
                                        @if( Auth::user()->pegawai->jabatan_id == '1' || Auth::user()->pegawai->jabatan_id == '2')
                                        {{ Auth::user()->pegawai->nama_pegawai }} <br>
                                        {{ Auth::user()->pegawai->jabatan->jabatan }} {{ Auth::user()->pegawai->keterangan_pegawai }}
                                        @else
                                        {{ Auth::user()->pegawai->nama_pegawai }} <br>
                                        {{ Auth::user()->pegawai->jabatan->jabatan }} <br> {{ Auth::user()->pegawai->timKerja->tim_kerja }}
                                        @endif
                                    </p>
                                </span>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-user mr-2"></i> Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('keluar') }}" class="dropdown-item dropdown-footer">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- /.navbar -->

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

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2022 <a href="https://adminlte.io">Biro Umum</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.1.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('dist_admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('dist_admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist_admin/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist_admin/dist/js/demo.js') }}"></script>
</body>

</html>
