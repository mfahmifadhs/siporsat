@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Alat Tulis Kantor (ATK)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Stok ATK</li>
                </ol>
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
                <a href="{{ url('admin-user/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Stok ATK</h4>
                    </div>
                    <div class="card-body">
                        <table id="table-stok" class="table table-bordered fa-1x">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Barang</th>
                                    <th class="text-center">Total Pembelian</th>
                                    <th class="text-center">Total Permintaan</th>
                                    <th class="text-center">Sisa Stok</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <!-- @foreach ($barang as $i => $dataAtk)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $dataAtk['kode_ref'] }}</td>
                                    <td>{{ $dataAtk['kategori_id'] }}</td>
                                    <td>{{ $dataAtk['deskripsi'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['barang_masuk'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['barang_keluar'] }}</td>
                                    <td class="text-center">{{ $dataAtk['satuan'] }}</td>
                                    <td class="text-center">{{ (int) $dataAtk['jumlah'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach -->
                                @php $no = 1; @endphp
                                @foreach ($list as $i => $row)
                                @if ($row->riwayat->sum('jumlah') != 0)
                                <tr>
                                    <td class="text-center pt-4">{{ $no++ }}</td>
                                    <td>
                                        <b class="text-primary">{{ $row->kode_ref }}</b> <br>
                                        {{ $row->deskripsi_barang }}
                                    </td>
                                    <td class="text-center pt-4">
                                        {{ $row->riwayat->where('status_riwayat', 'masuk')->sum('jumlah') }}
                                    </td>
                                    <td class="text-center pt-4">
                                        {{ $row->riwayat->where('status_riwayat', 'keluar')->sum('jumlah') }}
                                    </td>
                                    <td class="text-center pt-4">
                                        {{ $row->riwayat->where('status_riwayat', 'masuk')->sum('jumlah') - $row->riwayat->where('status_riwayat', 'keluar')->sum('jumlah') }}
                                    </td>
                                    <td class="text-center pt-4">{{ $row->satuan_barang }}</td>
                                    <td class="text-center pt-3">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#atk-{{ $row->id_atk }}">
                                            <i class="fas fa-bars"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="atk-{{ $row->id_atk }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">
                                                    {{ $row->deskripsi_barang }} <br>
                                                    Stok : {{ $row->riwayat->where('status_riwayat', 'masuk')->sum('jumlah') - $row->riwayat->where('status_riwayat', 'keluar')->sum('jumlah').' '.$row->satuan_barang }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="timeline">
                                                            <div class="time-label">
                                                                <span class="bg-blue">Riwayat Pembelian</span>
                                                            </div>
                                                            @foreach ($row->riwayat->where('status_riwayat', 'masuk')->sortByDesc('tanggal_riwayat') as $riwayatAtk)
                                                            <div>
                                                                <i class="fas fa-circle-dot bg-blue"></i>
                                                                <div class="timeline-item">
                                                                    <h3 class="timeline-header" style="font-size: 14px;">
                                                                        <a href="#" class="text-danger text-capitalize">
                                                                            Biro Umum
                                                                        </a>
                                                                        <br>
                                                                        <span class="small">
                                                                        <i class="fas fa-clock"></i>
                                                                        {{ $riwayatAtk->tanggal_riwayat }}
                                                                        </span>
                                                                    </h3>
                                                                    <div class="timeline-body">
                                                                        <div class="row medium">
                                                                            <div class="col-md-4">
                                                                                <i class="fas fa-cubes"></i> Jumlah
                                                                            </div>
                                                                            <div class="col-md-4">: {{ $riwayatAtk->jumlah.' '.$row->satuan_barang }}</div>
                                                                            <div class="col-md-4 text-right">
                                                                                <a href="{{ url('admin-user/atk/gudang/detail-transaksi/'. $riwayatAtk->usulan_id) }}" class="btn btn-default border-secondary btn-xs">
                                                                                    Read more
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="timeline">
                                                            <div class="time-label">
                                                                <span class="bg-red">Riwayat Permintaan</span>
                                                            </div>
                                                            @foreach ($row->riwayat->where('status_riwayat', 'keluar')->sortByDesc('tanggal_riwayat') as $riwayatAtk)
                                                            <div>
                                                                <i class="fas fa-circle-dot bg-red"></i>
                                                                <div class="timeline-item">
                                                                    <h3 class="timeline-header" style="font-size: 14px;">
                                                                        <a href="#" class="text-danger text-capitalize">
                                                                            @if ($riwayatAtk->bast)
                                                                                {{ ucfirst(strtolower($riwayatAtk->bast->usulan->pegawai->unitKerja->unit_kerja)) }}
                                                                            @else
										@if($riwayatAtk->transaksi)
										    {{ ucfirst(strtolower($riwayatAtk->transaksi->unitKerja->unit_kerja)) }}
										@else
										    {{ ucfirst(strtolower($riwayatAtk->unitKerja->unit_kerja)) }}
										@endif
                                                                            @endif
                                                                        </a>
                                                                        <br>
                                                                        <span class="small">
                                                                        <i class="fas fa-clock"></i>
                                                                        {{ $riwayatAtk->tanggal_riwayat }}
                                                                        </span>
                                                                    </h3>
                                                                    <div class="timeline-body">
                                                                        <div class="row medium">
                                                                            <div class="col-md-4">
                                                                                <i class="fas fa-cubes"></i> Jumlah
                                                                            </div>
                                                                            <div class="col-md-4">: {{ $riwayatAtk->jumlah.' '.$row->satuan_barang }}</div>
                                                                            <div class="col-md-4 text-right">
                                                                                <a href="{{ url('admin-user/surat/detail-bast-atk/'. $riwayatAtk->usulan_id) }}" class="btn btn-default border-secondary btn-xs">
                                                                                    Read more
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
        $("#table-stok").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "buttons": ["excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-kategori_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection
@endsection
