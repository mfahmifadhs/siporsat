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
                    <option value="0">Rekap Berdasarkan Unit Kerja</option>
                    <option value="1">Rekap Berdasarkan Tahun Kendaraan</option>
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
                    <div class="card-body">
                        <table id="table-rekap" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Unit Kerja</th>
                                    @foreach($jenisKendaraan as $dataJenken)
                                    <th class="text-center">{{ $dataJenken->jenis_kendaraan }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($rekapUnker as $unitKerja => $jenisKendaraan)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $unitKerja }}</td>
                                    @foreach($jenisKendaraan as $jenKen => $total)
                                    <td class="text-center"> {{ $total }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@section('js')
<script>
    $(function() {
        $("#table-rekap").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false
        }).buttons().container().appendTo('#table-rekap_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
