@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rekapitulasi Pengelolaan Olah Data (OLDAT)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"> Dashboard OLDAT</a></li>
                    <li class="breadcrumb-item active">Rekapitulasi Pengelolaan Olah Data (BMN)</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row form-group">
            <div class="col-md-4">
                <select id="dataRekap" class="form-control">
                    <option value="0">-- Pilih Rekap --</option>
                    <option value="1">Rekap Berdasarkan Tahun Perolehan</option>
                    <option value="2">Rekap Berdasarkan Tim Kerja</option>
                    <option value="3">Rekap Berdasarkan Unit Kerja </option>
                </select>
            </div>
            <div class="col-md-2">
                <a id="btnPilihRekap" class="btn btn-primary">Pilih</a>
            </div>
        </div>
        <div class="row form-group" id="rekap-default">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Rekapitulasi Barang Bersarkan Tahun Perolehan dan Tim Kerja</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="table-rekap">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Jumlah Barang</th>
                            </tr>
                            @foreach($rekapTotalBarang as $dataTotalBarang)
                            <tr>
                                <td>{{ $dataTotalBarang->kategori_barang }}</td>
                                <td>{{ $dataTotalBarang->totalbarang }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $('#btnPilihRekap').click(function() {
        $("#table-rekap").empty();
        let no = 2;
        let i;
        let data = $('#dataRekap').val();
        console.log(data);
        if (data == 1) {
            $("#table-rekap").append(
                `<table class="table table-bordered">
                    @foreach($tahunPerolehan as $dataTahun)
                        <thead>
                            <tr class="bg-light">
                                <th>Tahun</th>
                                <th>Tim Kerja</th>
                                @foreach($kategoriBarang as $dataKategoriBarang)
                                <th>{{ $dataKategoriBarang->kategori_barang }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="{{ $timKerja->count() + 1 }}" class="text-centerl po">
                                    {{ $dataTahun->tahun_perolehan }}
                                </td>
                            </tr>
                            @foreach($rekapTahunPerolehan as $tiker => $dataTimKerja)
                                <tr>
                                    <td>{{ $tiker }}</td>
                                    @foreach($dataTimKerja as $total => $totalBarang)
                                        <td>{{ $totalBarang }}</td>
                                    @endforeach

                                </tr>
                            @endforeach
                        </tbody>
                    @endforeach
                </table>`
            );
        } else if (data == 2) {
            $("#table-rekap").append(
                `<table class="table table-bordered">
                            <tr>
                                <th>Tim Kerja</th>
                                @foreach($kategoriBarang as $dataKategoriBarang)
                                <th>{{ $dataKategoriBarang->kategori_barang }}</th>
                                @endforeach
                            </tr>
                                @foreach($rekapTimKerja as $tiker => $timKerja)
                                <tr>
                                    <td>{{ $tiker }}</td>
                                    @foreach($timKerja as $kategori => $totalBarang)
                                    <td>{{ $totalBarang }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                        </table>`
            );
        } else if (data == 3) {
            $("#table-rekap").append(
                `<table class="table table-bordered">
                            <tr>
                                <th>Unit Kerja</th>
                                @foreach($kategoriBarang as $dataKategoriBarang)
                                <th>{{ $dataKategoriBarang->kategori_barang }}</th>
                                @endforeach
                            </tr>
                                @foreach($rekapUnitKerja as $unker => $unitKerja)
                                <tr>
                                    <td>{{ $unker }}</td>
                                    @foreach($unitKerja as $kategori => $totalBarang)
                                    <td>{{ $totalBarang }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                        </table>`
            );
        } else if (data == 0) {
            location.reload();
        }
    });
</script>
@endsection

@endsection
