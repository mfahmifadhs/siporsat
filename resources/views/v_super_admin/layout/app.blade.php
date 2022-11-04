<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPORSAT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Icon Title -->
    <link rel="icon" type="image/png" href="{{ asset('dist_admin_admin/img/logo-kemenkes-icon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/select2/css/select2.min.css') }}">
</head>

<!-- <body class="hold-transition sidebar-mini sidebar-collapse"> -->

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{ asset('dist_admin/img/logo-kemenkes-brown.png') }}" alt="Sistem Informasi Pergudangan" height="200">
  </div> -->

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url('super-admin/dashboard') }}" class="nav-link">Dashboard</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                        <b>Super Admin</b>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">
                            Super Admin
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
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="{{ asset('dist_admin/img/logo-kemenkes-icon.png') }}" alt="Sistem Informasi Pergudangan" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SIPORSAT</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-4">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Pengelolaan OLAT -->
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold {{ Request::is('super-admin/oldat/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-laptop"></i>
                                <p>Pengelolaan OLDAT </p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ url('super-admin/oldat/dashboard') }}" class="nav-link {{ Request::is('super-admin/oldat/dashboard') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-header ">Barang</li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/oldat/kategori-barang/data/semua') }}" class="nav-link {{ Request::is('super-admin/oldat/kategori-barang/data/semua') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-list-alt"></i>
                                        <p>
                                            Kategori
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/oldat/barang/data/semua') }}" class="nav-link {{ Request::is('super-admin/oldat/barang/data/semua') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-boxes"></i>
                                        <p>
                                            Master Barang
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-header">Status</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list-alt"></i>
                                        <p>
                                            Status
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="../../index.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Status Barang</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../../index2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Status Pengadaan</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-header">Pengadaan</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list-alt"></i>
                                        <p>
                                            Form Pengadaan
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/oldat/pengajuan/data/seluruh-pengajuan') }}" class="nav-link">
                                        <i class="nav-icon fas fa-file"></i>
                                        <p>
                                            Master Pengajuan
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-header">Rekapitulasi</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Master Rekapitulasi
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="../../index.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Pengguna</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../../index2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Tim Kerja</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../../index2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Unit Kerja</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="../../index2.html" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Unit Utama</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- Pengelolaan AADB -->
                        <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold {{ Request::is('super-admin/aadb/dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-car-side"></i>
                                <p>Pengelolaan AADB</p>
                                <i class="right fas fa-angle-left"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/aadb/dashboard') }}" class="nav-link {{ Request::is('super-admin/aadb/dashboard') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-home"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/aadb/kendaraan/daftar/kendaraan') }}" class="nav-link {{ Request::is('super-admin/aadb/kendaraan/daftar/kendaraan') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-car"></i>
                                        <p>Master Kendaraan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/aadb/kendaraan/daftar/pengemudi') }}" class="nav-link {{ Request::is('super-admin/aadb/kendaraan/daftar/pengemudi') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-tie"></i>
                                        <p>Master Pengemudi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                                <!-- <li class="nav-header font-weight-bold">Pengelolaan ATK</li> -->
                                <!-- Pengelolaan ATK -->
                                <!-- <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-list"></i>
                                <p>PENGELOLAAN ATK</p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-header ">Menu</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list-alt"></i>
                                        <p>
                                            Sub Menu
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                                <!-- <li class="nav-header font-weight-bold">Pengelolaan Pemeliharaan</li> -->
                                <!-- Pengelolaan Pemeliharaan -->
                                <!-- <li class="nav-item">
                            <a href="#" class="nav-link font-weight-bold">
                                <i class="nav-icon fas fa-list"></i>
                                <p>PENGELOLAAN MTC</p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-header ">Menu</li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-list-alt"></i>
                                        <p>
                                            Sub Menu
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li> -->
                                <li class="nav-header font-weight-bold mt-4">Pengguna & Pegawai</li>
                                <li class="nav-item">
                                    <a href="{{ url('super-admin/level/data/semua') }}" class="nav-link {{ Request::is('super-admin/level/data/semua') ? 'active' : '' }}">
                                        <i class="fas fa-users-cog nav-icon"></i>
                                        <p>Level</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link {{ Request::is('super-admin/kewenangan/data/semua') ? 'active' : '' }}">
                                        <i class="fas fa-users-cog nav-icon"></i>
                                        <p>Kewenangan</p>
                                    </a>
                                </li>
                                <li class="nav-item mb-4">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>
                                            Master Pengguna
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url('super-admin/pengguna/data/semua') }}" class="nav-link {{ Request::is('super-admin/pengguna/data/semua') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Pengguna</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('super-admin/pegawai/data/semua') }}" class="nav-link {{ Request::is('super-admin/pegawai/data/semua') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Pegawai</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('super-admin/tim-kerja/data/semua') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Tim Kerja</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ url('super-admin/unit-kerja/data/semua') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Unit Kerja</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Unit Utama</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
            <br>
        </div>


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

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('dist_admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('dist_admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('dist_admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist_admin/js/adminlte.js') }}"></script>

    <!-- PAGE PLUGINS -->
    <script src="{{ asset('dist_admin/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('dist_admin/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('dist_admin/plugins/chart.js/Chart.min.js') }}"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist_admin/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dist_admin/js/pages/dashboard2.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('dist_admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
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
    <!-- Select2 -->
    <script src="{{ asset('dist_admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- ChartJS -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    @yield('js')
    <script>
        $(function() {
            var url = window.location;
            // for single sidebar menu
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');

            // for sidebar menu and treeview
            $('ul.nav-treeview a').filter(function() {
                    return this.href == url;
                }).parentsUntil(".nav-sidebar > .nav-treeview")
                .css({
                    'display': 'block'
                })
                .addClass('menu-open').prev('a')
                .addClass('active');
        });
    </script>
</body>

</html>
