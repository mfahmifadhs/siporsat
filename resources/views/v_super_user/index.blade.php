@extends('v_super_user.layout.app')

@section('content')

<!-- Main content -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="font-weight-bold mb-4 text-capitalize">
                            Selamat Datang, {{ ucfirst(strtolower(Auth::user()->pegawai->nama_pegawai)) }}
                        </h5>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card card-primary font-weight-bold">
                            <div class="card-header text-center">
                                <i class="fas fa-laptop"></i> Oldah Data & Meubelair
                            </div>
                            <a href="{{ url('super-user/oldat/usulan/status/1') }}">
                                <div class="card-header">
                                    <label>Menunggu Persetujuan</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_proses_id', 1)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/usulan/status/2') }}">
                                <div class="card-header">
                                    <label>Sedang Diproses</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_proses_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/usulan/status/3') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Pengusul</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_proses_id', 3)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/usulan/status/4') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Kabag RT</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_proses_id', 4)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/usulan/status/5') }}">
                                <div class="card-header">
                                    <label>Selesai</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_proses_id', 5)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/usulan/status/ditolak') }}">
                                <div class="card-header">
                                    <label>Ditolak</label>
                                    <div class="card-tools">
                                        {{ $usulanOldat->where('status_pengajuan_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/oldat/dashboard') }}" class="bg-primary text-center">
                                <div class="card-footer">
                                    Dashboard <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card card-primary font-weight-bold">
                            <div class="card-header">
                                <i class="fas fa-car"></i> Alat Angkutan Darat Bermotor
                            </div>
                            <a href="{{ url('super-user/aadb/usulan/status/1') }}">
                                <div class="card-header">
                                    <label>Menunggu Persetujuan</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_proses_id', 1)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/status/2') }}">
                                <div class="card-header">
                                    <label>Sedang Diproses</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_proses_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/status/3') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Pengusul</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_proses_id', 3)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/status/4') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Kabag RT</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_proses_id', 4)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/status/5') }}">
                                <div class="card-header">
                                    <label>Selesai</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_proses_id', 5)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/usulan/status/ditolak') }}">
                                <div class="card-header">
                                    <label>Ditolak</label>
                                    <div class="card-tools">
                                        {{ $usulanAadb->where('status_pengajuan_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/aadb/dashboard') }}" class="bg-primary text-center">
                                <div class="card-footer">
                                    Dashboard <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card card-primary font-weight-bold">
                            <div class="card-header">
                                <i class="fas fa-pencil-ruler"></i> Alat Tulis Kantor (ATK)
                            </div>
                            <a href="{{ url('super-user/atk/usulan/status/1') }}">
                                <div class="card-header">
                                    <label>Menunggu Persetujuan</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_proses_id', 1)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/usulan/status/2') }}">
                                <div class="card-header">
                                    <label>Sedang Diproses</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_proses_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/usulan/status/3') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Pengusul</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_proses_id', 3)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/usulan/status/4') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Kabag RT</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_proses_id', 4)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/usulan/status/5') }}">
                                <div class="card-header">
                                    <label>Selesai</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_proses_id', 5)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/usulan/status/ditolak') }}">
                                <div class="card-header">
                                    <label>Ditolak</label>
                                    <div class="card-tools">
                                        {{ $usulanAtk->where('status_pengajuan_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/atk/dashboard') }}" class="bg-primary text-center">
                                <div class="card-footer">
                                    Dashboard <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card card-primary font-weight-bold">
                            <div class="card-header">
                                <i class="fas fa-building"></i> Gedung dan Bangunan
                            </div>
                            <a href="{{ url('super-user/gdn/usulan/status/1') }}">
                                <div class="card-header">
                                    <label>Menunggu Persetujuan</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_proses_id', 1)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/usulan/status/2') }}">
                                <div class="card-header">
                                    <label>Sedang Diproses</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_proses_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/usulan/status/3') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Pengusul</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_proses_id', 3)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/usulan/status/4') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Kabag RT</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_proses_id', 4)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/usulan/status/5') }}">
                                <div class="card-header">
                                    <label>Selesai</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_proses_id', 5)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/usulan/status/ditolak') }}">
                                <div class="card-header">
                                    <label>Ditolak</label>
                                    <div class="card-tools">
                                        {{ $usulanGdn->where('status_pengajuan_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/gdn/dashboard') }}" class="bg-primary text-center">
                                <div class="card-footer">
                                    Dashboard <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card card-primary font-weight-bold">
                            <div class="card-header">
                                <i class="fas fa-suitcase"></i> Urusan Kerumah Tanggaan
                            </div>
                            <a href="{{ url('super-user/ukt/usulan/status/1') }}">
                                <div class="card-header">
                                    <label>Menunggu Persetujuan</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_proses_id', 1)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/usulan/status/2') }}">
                                <div class="card-header">
                                    <label>Sedang Diproses</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_proses_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/usulan/status/3') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Pengusul</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_proses_id', 3)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/usulan/status/4') }}">
                                <div class="card-header">
                                    <label>Konfirmasi BAST Kabag RT</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_proses_id', 4)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/usulan/status/5') }}">
                                <div class="card-header">
                                    <label>Selesai</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_proses_id', 5)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/usulan/status/ditolak') }}">
                                <div class="card-header">
                                    <label>Ditolak</label>
                                    <div class="card-tools">
                                        {{ $usulanUkt->where('status_pengajuan_id', 2)->count() }}
                                        usulan
                                    </div>
                                </div>
                            </a>
                            <a href="{{ url('super-user/ukt/dashboard') }}" class="bg-primary text-center">
                                <div class="card-footer">
                                    Dashboard <i class="fas fa-arrow-circle-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
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
