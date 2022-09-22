@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pengelolaan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        <a href="{{ url('super-user/aadb/usulan/pengadaan/kendaraan') }}" class="btn btn-primary">Usulan <br> Pengadaan</a>
                        <a href="{{ url('super-user/aadb/usulan/servis/kendaraan') }}" class="btn btn-primary">Usulan <br> Servis</a>
                        <a href="{{ url('super-user/aadb/usulan/perpanjangan-stnk/kendaraan') }}" class="btn btn-primary">Usulan <br> Perpanjangan STNK</a>
                        <a href="{{ url('super-user/aadb/usulan/voucher-bbm/kendaraan') }}" class="btn btn-primary">Usulan <br> Voucher BBM</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 form-group">
                <div class="card card-outline card-primary text-center" style="height: 100%;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">-- Pilih Jenis AADB --</option>
                                    <option value="bmn">BMN</option>
                                    <option value="sewa">SEWA</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="unit_kerja">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach ($unitKerja as $item)
                                    <option value="{{$item->id_unit_kerja}}">{{$item->unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    @foreach ($jenisKendaraan as $item)
                                    <option value="{{$item->id_jenis_kendaraan}}">{{$item->jenis_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">-- Pilih Merk Kendaraan --</option>
                                    @foreach ($merk as $item)
                                    <option value="{{$item->merk_kendaraan}}">{{$item->merk_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">-- Pilih Tahun Kendaraan --</option>
                                    @foreach ($tahun as $item)
                                    <option value="{{$item->tahun_kendaraan}}">{{$item->tahun_kendaraan}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="" class="form-control" name="tim_kerja">
                                    <option value="">-- Pilih Pengguna --</option>
                                    @foreach ($pengguna as $item)
                                    <option value="{{$item->pengguna}}">{{$item->pengguna}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 form-group">
                                <button id="searchChartData" class="btn btn-primary form-control">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="konten-statistik">
                        <div id="konten-chart">
                            <canvas id="pie-chart" width="800" height="450"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
