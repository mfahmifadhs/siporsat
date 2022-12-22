@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Daftar ATK</h1>
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
                        @if($fileAtk != null)
                        <div class="card-header">
                            <b>DAFTAR KEBUTUHAN BARANG-BARANG PERSEDIAAN (ATK) SATKER SETJEN TAHUN 2023</b>
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
                                    @foreach($fileAtk as $key => $value)
                                    @foreach ($value as $dataAtk)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td><input type="text" name="atk_barang[]" class="form-control" value="{{ $dataAtk[1] }}"></td>
                                        <td><input type="text" name="atk_spesifikasi[]" class="form-control" value="{{ $dataAtk[2] }}"></td>
                                        <td><input type="text" name="atk_jumlah[]" class="form-control text-center" value="{{ $dataAtk[3] }}"></td>
                                        <td><input type="text" name="atk_satuan[]" class="form-control text-center text-uppercase" value="{{ $dataAtk[4] }}"></td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        @if ($fileAlkom != null)
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
                                    @foreach($fileAlkom as $key => $value)
                                    @foreach ($value as $dataAtk)
                                    @if($dataAtk[3] != 0)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td><input type="text" name="alkom_barang[]" class="form-control" value="{{ $dataAtk[1] }}"></td>
                                        <td><input type="text" name="alkom_spesifikasi[]" class="form-control" value="{{ $dataAtk[2] }}"></td>
                                        <td><input type="text" name="alkom_jumlah[]" class="form-control text-center" value="{{ $dataAtk[3] }}"></td>
                                        <td><input type="text" name="alkom_satuan[]" class="form-control text-center" value="BUAH" readonly></td>
                                    </tr>
                                    @endif
                                    @endforeach
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
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "search": false,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        })
        $("#table-alkom").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "search": false,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ]
        })
    })
</script>

@endsection
@endsection
