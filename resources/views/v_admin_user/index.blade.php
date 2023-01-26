@extends('v_admin_user.layout.app')

@section('content')

<section class="content">
    <div class="container-fluid">
        <!-- Anggaran -->
        <div class="row">
            <div class="col-sm-12 py-3">
                <h4 class="m-0">Anggaran</h4>
            </div>
            <div class="col-lg-4 col-12">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <p>Total Anggaran Awal</p>
                        <h3><sup style="font-size: 20px">Rp</sup> 0</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <p>Total Anggaran Digunakan</p>
                        <h3><sup style="font-size: 20px">Rp</sup> 0</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="small-box bg-success">
                    <div class="inner">
                        <p>Total Anggaran Tersedia</p>
                        <h3><sup style="font-size: 20px">Rp</sup> 0</h3>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- Usulan -->
        <div class="row">
            <div class="col-sm-12 py-3">
                <h4 class="m-0">Usulan</h4>
            </div>
            <div class="col-sm-12 col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0 table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th class="text-left">Modul</th>
                                        <th>Menunggu <br> Persetujuan</th>
                                        <th>Sedang Diproses <br> PPK</th>
                                        <th>Menunggu Konfirmasi <br> BAST Pengusul</th>
                                        <th>Menunggu Konfirmasi <br> BAST Kabag RT</th>
                                        <th>Selesai <br> BAST</th>
                                        <th>Usulan <br> Ditolak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- (OLDAT) Olah Data BMN & Meubelair -->
                                    <tr>
                                        <td class="text-left">Olah Data BMN & Meubelair</td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- (AADB) Alat Angkutan Darat Bermotor -->
                                    <tr>
                                        <td class="text-left">Alat Angkutan Darat Bermotor (AADB)</td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- (ATK) Alat Tulis Kantor -->
                                    <tr>
                                        <td class="text-left">Alat Tulis Kantor (ATK)</td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $oldat->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- (GDN) Gedung dan Bangunan -->
                                    <tr>
                                        <td class="text-left">Gedung dan Bangunan</td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $gdn->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <!-- (UKT) Urusan Kerumahtanggaan -->
                                    <tr>
                                        <td class="text-left">Urusan Kerumahtanggaan</td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">
                                                <a href="" class="text-white" style="font-size: 14px;">
                                                    {{ $ukt->where('status_proses_id', 1)->count() }} usulan
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
