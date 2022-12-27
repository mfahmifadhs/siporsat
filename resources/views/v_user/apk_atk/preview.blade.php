@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Pengadaan ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('unit-kerja/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usulan Pengadaan ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 form-group">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
            <div class="col-md-2">
                <label>Tanggal Usulan : </label>
                <h6>{{ \Carbon\carbon::parse($tanggal)->isoFormat('DD MMMM Y') }}</h6>
            </div>
            <div class="col-md-5">
                <label>Nomor Surat : </label>
                <h6 class="text-uppercase">{{ $noSurat }}</h6>
            </div>
            <div class="col-md-5">
                <label>Rencana Pengguna : </label>
                <h6>{{ $rencana }}</h6>
            </div>
            <div class="col-md-12 form-group mt-3">
                <div class="card card-primary">
                    <form action="{{ url('unit-kerja/atk/usulan/proses-pengadaan/baru') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                        <input type="hidden" name="no_surat_usulan" value="{{ $noSurat }}">
                        <input type="hidden" name="tanggal_usulan" value="{{ $tanggal }}">
                        <input type="hidden" name="rencana_pengguna" value="{{ $rencana }}">
                        @if($resultAtk != null)
                        <div class="card-header">
                            <b>DAFTAR KEBUTUHAN BARANG-BARANG PERSEDIAAN (ATK) SATKER SETJEN TAHUN 2023</b> <br>
                            <b><small class="text-danger font-weight-bold">Jumlah barang dapat diubah selama Usulan belum disetujui oleh Kepala Bagian Rumah Tangga</small></b>
                        </div>
                        <div class="card-body">
                            <table id="table-atk" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 35%;">Nama Barang</th>
                                        <th style="width: 40%;">Spesifikasi</th>
                                        <th style="width: 15%;">Jumlah</th>
                                        <th style="width: 10%;">Satuan</th>
                                    </tr>
                                </thead>
                                @php $no = 1; @endphp
                                <tbody>
                                    @foreach($resultAtk as $dataAtk)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            <input type="hidden" name="atk_id[]" class="form-control" value="{{ $dataAtk['id_form_usulan_pengadaan'] }}">
                                            <input type="text" name="atk_barang[]" class="form-control" value="{{ $dataAtk['nama_barang'] }}">
                                        </td>
                                        <td><input type="text" name="atk_spesifikasi[]" class="form-control" value="{{ $dataAtk['spesifikasi'] }}"></td>
                                        <td><input type="text" name="atk_jumlah[]" class="form-control text-center" value="{{ $dataAtk['jumlah'] }}"></td>
                                        <td><input type="text" name="atk_satuan[]" class="form-control text-center text-uppercase" value="{{ $dataAtk['satuan'] }}"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if ($resultAlkom != null)
                        <div class="card-header">
                            <b>DAFTAR KEBUTUHAN BARANG-BARANG PERSEDIAAN (ALKOM) SATKER SETJEN TAHUN 2023</b>
                        </div>
                        <div class="card-body">
                            <table id="table-alkom" class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th style="width: 35%;">Nama Barang</th>
                                        <th style="width: 40%;">Spesifikasi</th>
                                        <th style="width: 15%;">Jumlah</th>
                                        <th style="width: 10%;">Satuan</th>
                                    </tr>
                                </thead>
                                @php $no = 1; @endphp
                                <tbody>
                                    @foreach($resultAlkom as $dataAlkom)
                                    @if($dataAlkom['jumlah'] != 0)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>

                                            <input type="hidden" name="alkom_id[]" class="form-control" value="{{ $dataAlkom['id_form_usulan_pengadaan'] }}">
                                            <input type="text" name="alkom_barang[]" class="form-control" value="{{ $dataAlkom['nama_barang'] }}">
                                        </td>
                                        <td><input type="text" name="alkom_spesifikasi[]" class="form-control" value="{{ $dataAlkom['spesifikasi'] }}"></td>
                                        <td><input type="text" name="alkom_jumlah[]" class="form-control text-center" value="{{ $dataAlkom['jumlah'] }}"></td>
                                        <td><input type="text" name="alkom_satuan[]" class="form-control text-center" value="BUAH" readonly></td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah data sudah benar ?')">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
        $("#table-alkom").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })
    })
</script>

@endsection
@endsection
