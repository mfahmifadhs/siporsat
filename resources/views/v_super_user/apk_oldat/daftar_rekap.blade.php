@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Rekapitulasi</h1>
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
            <div class="col-md-4 form-group">
                <select id="dataRekap" class="form-control">
                    <option value="0">Rekap Berdasarkan Tahun Perolehan</option>
                    <option value="1">Rekap Berdasarkan Kategori Barang</option>
                    <option value="2">Rekap Berdasarkan Tim Kerja</option>
                    <option value="3">Rekap Berdasarkan Unit Kerja </option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <a id="btnPilihRekap" class="btn btn-primary">Pilih</a>
            </div>
        </div>
        <div class="row form-group" id="rekap-default">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Rekapitulasi Barang</h4>
                    </div>
                    <div class="card-body" id="table-rekap">
                        <table id="table-1" class="table table-bordered table-responsive">
                            <thead>
                                <tr class="bg-light">
                                    <th style="width:10%;">Tahun</th>
                                    <th style="width:40%;">Tim Kerja</th>
                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                    <th>{{ $dataKategoriBarang->kategori_barang }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapTahunPerolehan as $tahun => $dataUnitKerja)

                                <tr>
                                    <td rowspan="{{$timKerja->count()+1}}" class="text-centerl po">{{ $tahun }}</td>
                                </tr>
                                @foreach($rekapTahunPerolehan[$tahun]['biro umum'] as $tiker => $dataKategoriBarang)
                                <tr>
                                    <td>{{ $tiker }}</td>
                                    @foreach($dataKategoriBarang as $kategori => $totalBarang)
                                    <td>{{ $totalBarang }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        {{ $tahunPerolehan->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $("#table-1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["pdf", "excel"]
        }).buttons().container().appendTo('#table-1_wrapper .col-md-6:eq(0)');
    });

    $('#btnPilihRekap').click(function() {
        $("#table-rekap").empty();
        let no = 2;
        let i;
        let data = $('#dataRekap').val();
        console.log(data);
        if (data == 1) {
            $("#table-rekap").append(
                `<table id="table-1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Nama Barang</th>
                            <th>Jumlah Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapTotalBarang as $dataTotalBarang)
                        <tr>
                            <td>{{ $dataTotalBarang->kategori_barang }}</td>
                            <td>{{ $dataTotalBarang->totalbarang }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>`

            );
        } else if (data == 2) {
            $("#table-rekap").append(
                `<table class="table table-bordered table-responsive">
                            <tr>
                                <th style="width:40%;">Tim Kerja</th>
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
                `<table class="table table-bordered table-responsive">
                            <tr>
                                <th style="width:80%;">Unit Kerja</th>
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
