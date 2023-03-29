@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Alat Tulis Kantor (ATK)</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('unit-kerja/atk/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Referensi ATK</li>
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
            <div class="col-md-12 form-group">
                <a href="{{ url('unit-kerja/atk/dashboard') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-12 form-group">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title font-weight-bold mt-1">
                            Daftar Referensi ATK
                        </h4>
                        <div class="card-tools">
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#historyItem" title="Riwayat Pengajuan Item Baru">
                                <i class="fas fa-list"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addItem" title="Tambah Item Baru">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table-atk" class="table table-bordered table-striped fa-1x">
                            <thead class="bg-primary font-weight-bold">
                                <tr>
                                    <th style="width: 0%;" class="text-center">No</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;">Satuan</th>
                                    <th style="width: 30%;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referensi as $i => $row)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $row->deskripsi_barang }}</td>
                                    <td>{{ $row->satuan_barang }}</td>
                                    <td>{{ $row->keterangan_barang }}</td>
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

<!-- Modal Tambah Atk -->
<div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('unit-kerja/atk/usulan/tambah-atk/*') }}" method="POST" class="mt-2">
                @csrf
                <div class="modal-body">
                    <h6 class="text-center">
                        Mohon sebelum menambah item, pastikan barang yang ingin ditambahkan belum terdaftar pada referensi.
                    </h6>
                    <hr>
                    <div id="input-container">
                        <div class="row input-row mb-2">
                            <label class="col-md-12 col-form-label">Deskripsi Barang</label>
                            <div class="col-md-10 col-10">
                                <input type="text" class="form-control" name="deskripsi_barang[]" placeholder="Contoh: Tinta 78A/Post It Sign Here">
                            </div>
                            <div class="col-md-2 col-2 text-right">
                                <a class="btn btn-danger remove-input">
                                    <i class="fas fa-times-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary add-input mt-3"><i class="fas fa-plus"></i></a>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Riwayat Atk Baru -->
<div class="modal fade" id="historyItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Riwayat Pengajuan Item Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="table-riwayat" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Deskripsi Barang</th>
                            <th class="text-center">Status Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatAtk as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $row->deskripsi_barang }}</td>
                            <td class="text-center">
                                {{ $row->is_approve == 'true' ?
                                        '✅ Sudah Ditambahkan'
                                    : $row->is_approve == 'false' ?
                                        '❌ Tidak Disetujui'
                                    : '⏳ Sedang Diproses'
                                    }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                "responsive": true,
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

            $("#table-atk").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "info": true,
                "paging": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ]
            }).buttons().container().appendTo('#table-atk_wrapper .col-md-6:eq(0)');

            $("#table-riwayat").DataTable({
                "responsive": false,
                "lengthChange": true,
                "autoWidth": true,
                "info": true,
                "paging": true,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ]
            }).buttons().container().appendTo('#table-atk_wrapper .col-md-6:eq(0)');
        })

        $(function() {
            // Attach listener to "Add More" button
            $('.add-input').click(function() {
                // Create new input row
                var newRow = $(
                    `<div class="row input-row mb-2">
                    <label class="col-md-12 col-10 col-form-label">Deskripsi Barang</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" name="deskripsi_barang[]" placeholder="Contoh: Tinta 78A/Post It Sign Here">
                    </div>
                    <div class="col-md-2 col-2 text-right">
                        <a class="btn btn-danger remove-input">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    </div>
                </div>`
                );
                // Append new row to input container
                $('#input-container').append(newRow);
            });

            // Attach listener to "Remove Index" button
            $(document).on('click', '.remove-input', function() {
                // Remove parent row
                $(this).parents('.input-row').remove();
            });
        });
    </script>

    @endsection
    @endsection
