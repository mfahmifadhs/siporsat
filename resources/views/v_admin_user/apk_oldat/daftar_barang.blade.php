@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-admin/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="col-md-12 form-group">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @elseif ($message = Session::get('failed'))
            <div class="alert alert-danger">
                <p style="color:white;margin: auto;">{{ $message }}</p>
            </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload" title="Upload Data Barang">
                        <i class="fas fa-file-upload"></i>
                    </a>
                    <!-- <a href="{{ url('admin-user/oldat/barang/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data pengadaan ?')">
                        <i class="fas fa-file-download"></i>
                    </a> -->
                    <!-- <a type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add" title="Tambah Kategori Barang">
                        <i class="fas fa-plus-circle"></i>
                    </a> -->
                </div>
            </div>
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id Barang</th>
                            <th class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>NUP</th>
                            <th>Merk / Tipe</th>
                            <th>Jumlah</th>
                            <th>Nilai Perolehan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin-user    /oldat/kategori-barang/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Kategori Barang :</label>
                        <input type="text" class="form-control" name="kategori_barang" placeholder="Tambah Kategori Barang" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Kategori Barang ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/oldat/barang/upload/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Upload Data Barang</label>
                        <input type="file" name="upload" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="mt-2">
                            <small>Download format excel <a href="{{ asset('format/format_data_barang.xlsx') }}" download>disini</a></small> <br>
                            <small>Format file harus (.xlsx)</small>
                        </p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Upload Data ?')">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        console.log("ready!");
        $(function() {
            $("#table-barang").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                columnDefs: [{
                        targets: -1,
                        data: null,
                        defaultContent: `<a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn btn-detail">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>`,
                    },
                    {
                        "bVisible": false,
                        "aTargets": [0]
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                buttons: [{
                        extend: 'pdf',
                        text: ' PDF',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Data Master Barang',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                            // columns: [0, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'excel',
                        text: ' Excel',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Data Master Barang',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    }
                ],
                "bDestroy": true
            }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
        });
        setTimeout(showTable, 1000);
    });


    function showTable() {
        let dataTable = $('#table-barang').DataTable()
        console.log('start')
        let dataBarang = JSON.parse(`<?php echo $barang; ?>`)
        // console.log($('#table-barang').find('tbody'))

        dataTable.clear()
        // dataTable.draw()
        let no = 1
        dataBarang.forEach(element => {
            dataTable.row.add([
                element.id_barang,
                no,
                element.kode_barang,
                element.nup_barang,
                element.barang,
                element.jumlah_barang + ' ' + element.satuan_barang,
                element.nilai_perolehan,
                element.tahun_perolehan,
                element.kondisi_barang,
                element.nama_pegawai
            ])
            no++
            // console.log('data ke - ' + no)
        });
        dataTable.draw()
        console.log('finish')
    }
    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        // console.log(row)
        window.location.href = "/admin-user/oldat/barang/detail/" + row[0];
    })
</script>
@endsection

@endsection
