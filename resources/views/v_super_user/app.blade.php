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
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
        @yield('content')
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
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dist_admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    @yield('js')
</body>

</html>
