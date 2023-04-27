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
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/dashboard/data') }}"> Gudang ATK </a></li>
                    <li class="breadcrumb-item active">Refensi ATK</li>
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
                <a href="{{ URL::previous() }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Berita Acara Serah Terima ATK</h4>
                    </div>
                    <div class="card-body">

                        <table id="table-kategori" class="table table-bordered fa-1x">
                            <thead>
                                <tr>
                                    <th style="width: 0%;" class="text-center">No</th>
                                    <th style="width: 10;">Tanggal Bast</th>
                                    <th>Nomor Bast</th>
                                    <th style="width: 10;">Tanggal Usulan</th>
                                    <th>Nomor Usulan</th>
				    <th>Usulan</th>
                                    <th style="width: 15%;" class="text-center">Diserahkan</th>
                                    <th style="width: 0%;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @if ($modul == 'atk')
                                @foreach ($bast as $i => $detailAtk)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\carbon::parse($detailAtk->tanggal_bast)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $detailAtk->nomor_bast }}</td>
                                    <td>{{ \Carbon\carbon::parse($detailAtk->tanggal_usulan)->isoFormat('DD MMMM Y') }}</td>
                                    <td>{{ $detailAtk->no_surat_usulan }}</td>
				    <td>
					@if ($detailAtk->jenis_form == 'distribusi')
                                            @foreach ($detailAtk->detailBast as $item)
						{{ $item->nama_barang.' '.$item->spesifikasi.' ['. $item->jumlah_bast_detail.' '. $item->satuan .']' }},
                                            @endforeach
					@endif
                                    </td>
                                    <td class="text-center">{{ count($detailAtk->detailAtk) }} barang</td>
                                    <td class="text-center">
                                        <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                            <i class="fas fa-bars"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a href="{{ url('admin-user/surat/detail-bast-atk/'. $detailAtk->id_bast) }}" class="dropdown-item btn" type="button">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah Refensi -->

<div class="modal fade" id="modal-add-atk">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title font-weight-bold">Tambah Referensi ATK</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <form action="{{ url('admin-user/atk/gudang/insert/atk') }}" method="POST">
                @csrf
                <div class="modal-body text-capitalize">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Refensi</label>
                        <input class="col-md-10 form-control" name="kode_ref" placeholder="Kode Referensi" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Kategori</label>
                        <input class="col-md-10 form-control" name="kategori_id" placeholder="Kategori ID" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <input class="col-md-10 form-control" name="deskripsi_barang" placeholder="Deskripsi Barang" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Satuan</label>
                        <input class="col-md-10 form-control" name="satuan_barang" placeholder="Satuan Barang" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <textarea class="col-md-10 form-control" name="keterangan_barang" placeholder="Keterangan Tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Tambah Baru?')">
                        <i class="fas fa-plus-circle"></i> TAMBAH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kategori -->

<div class="modal fade" id="modal-add-kategori">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title font-weight-bold">Tambah Kategori ATK</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <form action="{{ url('admin-user/atk/gudang/insert/kategori') }}" method="POST">
                @csrf
                <div class="modal-body text-capitalize">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kode Kategori</label>
                        <input class="col-md-10 form-control" name="id_kategori_atk" placeholder="Kode Kategori" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Deskripsi</label>
                        <input class="col-md-10 form-control" name="deskripsi_kategori" placeholder="Deskripsi Kategori" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Satuan</label>
                        <input class="col-md-10 form-control" name="satuan_kategori" placeholder="Satuan" required>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keterangan</label>
                        <textarea class="col-md-10 form-control" name="keterangan_kategori" placeholder="Keterangan Tambahan"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Tambah Baru?')">
                        <i class="fas fa-plus-circle"></i> TAMBAH
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        $("#table-kategori").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
	    columnDefs: [{
                "bVisible": false,
                "aTargets": [5]
            }],
            "buttons": [{
                extend: 'excel',
                title: 'Show',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }, {
                extend: 'pdf',
                title: 'Show',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }, {
                extend: 'print',
                title: 'Show',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }]
        }).buttons().container().appendTo('#table-kategori_wrapper .col-md-6:eq(0)');
    })

    $(function() {
        $("#table-atk").DataTable({
            "responsive": false,
            "lengthChange": true,
            "autoWidth": true,
            "info": true,
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
	    columnDefs: [{
                "bVisible": false,
                "aTargets": [5]
            }],
            "buttons": [{
                extend: 'excel',
                title: 'Daftar Referensi ATK',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }, {
                extend: 'pdf',
                title: 'Daftar Referensi ATK',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }, {
                extend: 'print',
                title: 'Daftar Referensi ATK',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }]

        }).buttons().container().appendTo('#table-atk_wrapper .col-md-6:eq(0)');
    })
</script>

@endsection
@endsection
