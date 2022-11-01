@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0">Daftar Rumah Dinas Negara</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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
            <div class="col-md-12 form-group">
                <div class="card">
                    <div class="card-body">
                        <table id="table-rumah" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Alamat Lengkap</th>
                                    <th>LB / LT</th>
                                    <th>Kondisi</th>
                                    <th>Penghuni</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($rumah as $dataRumah)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('gambar/rumah_dinas/'. $dataRumah->foto_rumah) }}" alt="{{ $dataRumah->foto_rumah }}" width="150">
                                    </td>
                                    <td>
                                        <b>
                                            Gol. {{ $dataRumah->golongan_rumah }} /
                                            NUP. {{ $dataRumah->nup_rumah }} /
                                            {{ $dataRumah->lokasi_kota }}
                                        </b> <br>
                                        {{ $dataRumah->alamat_rumah }}
                                    </td>
                                    <td>
                                        {{ $dataRumah->luas_bangunan }} m<sup>2</sup> /
                                        {{ $dataRumah->luas_tanah }} m<sup>2</sup>
                                    </td>
                                    <td>{{ $dataRumah->kondisi_rumah }}</td>
                                    <td>
                                        @foreach ($dataRumah->penghuniRumah as $dataPenghuni)
                                        <label>{{ $dataPenghuni->nama_pegawai }}</label> <br>
                                        {{ $dataPenghuni->keterangan_pegawai }} <br>
                                        No. SIP : {{ $dataPenghuni->nomor_sip }} <br>
                                        @if($dataPenghuni->masa_berakhir_sip != null)
                                        Masa Berakhir SIP : {{ \Carbon\Carbon::parse($dataPenghuni->masa_berakhir_sip)->isoFormat('DD MMMM Y') }} <br>
                                        @endif
                                        Jenis Sertifikat : {{ $dataPenghuni->jenis_sertifikat }}
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ url('admin-user/rdn/rumah-dinas/detail/'. $dataRumah->id_rumah_dinas) }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        </div>
                                    </td>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-rumah").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true
        })
    })
</script>

@endsection
@endsection
