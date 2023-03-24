@extends('v_admin_user.layout.app')

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="font-weight-bold mb-4 text-capitalize">
                            Selamat Datang, {{ ucfirst(strtolower(Auth::user()->pegawai->nama_pegawai)) }}
                        </h4>
                    </div>
                    <!-- Oldat -->
                    @if(Auth::user()->akses->first()->is_oldat == 1)
                    <div class="col-md-4 col-12 mt-2">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-primary">
                                <div class="row">
                                    <div class="col-md-3 mt-2 text-center">
                                        <i class="fas fa-laptop fa-3x"></i>
                                    </div>
                                    <div class="col-md-9 mt-2 mb-0">
                                        <h5 class="font-weight-bold">Olah Data BMN & Meubelair</h5>
                                        <h6>(OLDAT)</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/oldat/usulan/status/1') }}" class="nav-link">
                                            Proses Persetujuan Kabag RT
                                            <span class="float-right">
                                                {{ $usulanOldat->where('status_proses_id', 1)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/oldat/usulan/status/2') }}" class="nav-link">
                                            Proses PPK
                                            <span class="float-right">
                                                {{ $usulanOldat->where('status_proses_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/oldat/usulan/status/3') }}" class="nav-link">
                                            Proses Berita Acara
                                            <span class="float-right">
                                                {{ $usulanOldat->where('status_proses_id', 3)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/oldat/usulan/status/5') }}" class="nav-link">
                                            Selasai Berita Acara
                                            <span class="float-right">
                                                {{ $usulanOldat->where('status_proses_id', 5)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/oldat/usulan/status/ditolak') }}" class="nav-link">
                                            Ditolak
                                            <span class="float-right">
                                                {{ $usulanOldat->where('status_pengajuan_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- AADB -->
                    @if(Auth::user()->akses->first()->is_aadb == 1)
                    <div class="col-md-4 col-12 mt-2">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-primary">
                                <div class="row">
                                    <div class="col-md-3 mt-2 text-center">
                                        <i class="fas fa-car fa-3x"></i>
                                    </div>
                                    <div class="col-md-9 mt-2 mb-0">
                                        <h5 class="font-weight-bold">Alat Angkutan Darat Bermotor</h5>
                                        <h6>(AADB)</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/aadb/usulan/status/1') }}" class="nav-link">
                                            Proses Persetujuan Kabag RT
                                            <span class="float-right">
                                                {{ $usulanAadb->where('status_proses_id', 1)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/aadb/usulan/status/2') }}" class="nav-link">
                                            Proses PPK
                                            <span class="float-right">
                                                {{ $usulanAadb->where('status_proses_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/aadb/usulan/status/3') }}" class="nav-link">
                                            Proses Berita Acara
                                            <span class="float-right">
                                                {{ $usulanAadb->where('status_proses_id', 3)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/aadb/usulan/status/5') }}" class="nav-link">
                                            Selasai Berita Acara
                                            <span class="float-right">
                                                {{ $usulanAadb->where('status_proses_id', 5)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/aadb/usulan/status/ditolak') }}" class="nav-link">
                                            Ditolak
                                            <span class="float-right">
                                                {{ $usulanAadb->where('status_pengajuan_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- ATK -->
                    @if(Auth::user()->akses->first()->is_atk == 1)
                    <div class="col-md-4 col-12 mt-2">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-primary">
                                <div class="row">
                                    <div class="col-md-3 mt-2 text-center">
                                        <i class="fas fa-pencil-ruler fa-3x"></i>
                                    </div>
                                    <div class="col-md-9 mt-2 mb-0">
                                        <h5 class="font-weight-bold">Alat Tulis Kantor</h5>
                                        <h6>(ATK)</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/atk/usulan/status/1') }}" class="nav-link">
                                            Proses Persetujuan Kabag RT
                                            <span class="float-right">
                                                {{ $usulanAtk->where('status_proses_id', 1)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/atk/usulan/status/2') }}" class="nav-link">
                                            Mempersiapkan Barang
                                            <span class="float-right">
                                                {{ $usulanAtk->where('status_proses_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/atk/usulan/status/3') }}" class="nav-link">
                                            Dapat Diserahkan
                                            <span class="float-right">
                                                {{ $usulanAtk->where('status_proses_id', 3)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/atk/usulan/status/5') }}" class="nav-link">
                                            Selasai Berita Acara
                                            <span class="float-right">
                                                {{ $usulanAtk->where('status_proses_id', 5)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/atk/usulan/status/ditolak') }}" class="nav-link">
                                            Ditolak
                                            <span class="float-right">
                                                {{ $usulanAtk->where('status_pengajuan_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- GDN -->
                    @if(Auth::user()->akses->first()->is_mtc == 1)
                    <div class="col-md-4 col-12 mt-2">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-primary">
                                <div class="row">
                                    <div class="col-md-3 mt-2 text-center">
                                        <i class="fas fa-hotel fa-3x"></i>
                                    </div>
                                    <div class="col-md-9 mt-2 mb-0">
                                        <h5 class="font-weight-bold">Gedung dan Bangunan</h5>
                                        <h6>(GDN)</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/gdn/usulan/status/1') }}" class="nav-link">
                                            Proses Persetujuan Kabag RT
                                            <span class="float-right">
                                                {{ $usulanGdn->where('status_proses_id', 1)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/gdn/usulan/status/2') }}" class="nav-link">
                                            Proses PPK
                                            <span class="float-right">
                                                {{ $usulanGdn->where('status_proses_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/gdn/usulan/status/3') }}" class="nav-link">
                                            Proses Berita Acara
                                            <span class="float-right">
                                                {{ $usulanGdn->where('status_proses_id', 3)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/gdn/usulan/status/5') }}" class="nav-link">
                                            Selasai Berita Acara
                                            <span class="float-right">
                                                {{ $usulanGdn->where('status_proses_id', 5)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/gdn/usulan/status/ditolak') }}" class="nav-link">
                                            Ditolak
                                            <span class="float-right">
                                                {{ $usulanGdn->where('status_pengajuan_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- UKT -->
                    @if(Auth::user()->akses->first()->is_ukt == 1)
                    <div class="col-md-4 col-12 mt-2">
                        <div class="card card-widget widget-user-2">
                            <div class="widget-user-header bg-primary">
                                <div class="row">
                                    <div class="col-md-3 mt-2 text-center">
                                        <i class="fas fa-laptop-house fa-3x"></i>
                                    </div>
                                    <div class="col-md-9 mt-2 mb-0">
                                        <h5 class="font-weight-bold">Urusan Kerumahtanggaan</h5>
                                        <h6>(UKT)</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column font-weight-bold">
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/ukt/usulan/status/1') }}" class="nav-link">
                                            Proses Persetujuan Kabag RT
                                            <span class="float-right">
                                                {{ $usulanUkt->where('status_proses_id', 1)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/ukt/usulan/status/2') }}" class="nav-link">
                                            Proses PPK
                                            <span class="float-right">
                                                {{ $usulanUkt->where('status_proses_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/ukt/usulan/status/3') }}" class="nav-link">
                                            Proses Berita Acara
                                            <span class="float-right">
                                                {{ $usulanUkt->where('status_proses_id', 3)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/ukt/usulan/status/5') }}" class="nav-link">
                                            Selasai Berita Acara
                                            <span class="float-right">
                                                {{ $usulanUkt->where('status_proses_id', 5)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('admin-user/ukt/usulan/status/ditolak') }}" class="nav-link">
                                            Ditolak
                                            <span class="float-right">
                                                {{ $usulanUkt->where('status_pengajuan_id', 2)->count() }} usulan
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $("#table").DataTable({
            "responsive": true,
            "lengthChange": false,
            "searching": false,
            "info": false,
            "paging": false,
            "autoWidth": false,
        }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
