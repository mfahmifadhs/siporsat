@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Pengajuan</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row form-group" id="rekap-default">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Pengajuan Pengadaan Kendaraan Baru/Sewa</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-pengajuan" class="table table-bordered table-striped">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>ID Form</th>
                                <th>Kode Form</th>
                                <th>Pengusul</th>
                                <th>Jenis Pengajuan</th>
                                <th>Rencana Pengguna</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </thead>
                            @php $no = 1; @endphp
                            <tbody>
                                @foreach($pengajuan as $row)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $row->id_form_usulan }}</td>
                                    <td>{{ $row->kode_form }}</td>
                                    <td>{{ $row->nama_pegawai }}</td>
                                    <td>{{ $row->jenis_form_usulan }}</td>
                                    <td>{{ $row->rencana_pengguna }}</td>
                                    <td>{{ $row->status_pengajuan.' '.$row->status_proses }}</td>
                                    <td></td>
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
        $("#table-pengajuan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["pdf", "excel"]
        }).buttons().container().appendTo('#table-rekap_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@endsection
