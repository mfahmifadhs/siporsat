@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Olah Data BMN & Meubelair (OLDAT)</h1>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/oldat/usulan/status/1') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 1)->count() }} <small>usulan</small></h3>
                                    <p>PERSETUJUAN KABAG RT</p>
                                </div>
                                <div class="icon p-2">
                                    <i>1</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/oldat/usulan/status/2') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 2)->count() }} <small>usulan</small></h3>
                                    <p>SEDANG PROSES PPK</p>
                                </div>
                                <div class="icon p-2">
                                    <i>2</i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/oldat/usulan/status/5') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_proses_id', 5)->count() }} <small>usulan</small></h3>
                                    <p>SELESAI BERITA ACARA</p>
                                </div>
                                <div class="icon p-2">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-12">
                        <a href="{{ url('admin-user/oldat/usulan/status/ditolak') }}">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $usulanTotal->where('status_pengajuan_id', 2)->count() }} <small>usulan</small></h3>
                                    <p>PENGAJUAN DITOLAK</p>
                                </div>
                                <div class="icon p-2">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12 text-center">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-left">Usulan Pengajuan</h6>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-left">Daftar Barang Milik Negara (BMN)</h6>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/oldat/usulan/daftar/seluruh-usulan') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-copy fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar Usulan</h6>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <!--  -->
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/oldat/barang/daftar/*') }}" class="btn btn-default border-secondary p-4 btn-block">
                            <i class="fas fa-boxes fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Daftar BMN</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/oldat/barang/tambah/*') }}" class="btn btn-default border-secondary p-4 btn-block disabled">
                            <i class="fas fa-plus-circle fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Tambah BMN</h6>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-left">Laporan</h6>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="#" class="btn btn-default border-secondary p-4 btn-block disabled">
                            <i class="fas fa-file-alt fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Anggaran</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6 form-group">
                        <a href="{{ url('admin-user/oldat/usulan/laporan/*') }}" class="btn btn-default border-secondary p-4 btn-block disabled">
                            <i class="fas fa-file-alt fa-3x"></i>
                            <h6 class="mt-3 font-weight-bolder">Laporan</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true

        })

        $("#table-unitkerja").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": false,
            "paging": false,
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })

        $("#table-oldat").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "info": false,
            "paging": true,
            "lengthMenu": [
                [11, 25, 50, -1],
                [11, 25, 50, "Semua"]
            ],
            "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "25%",
                    "targets": 2
                },
                {
                    "width": "25%",
                    "targets": 3
                },
            ]

        })
    })
</script>

@endsection

@endsection
