@extends('v_super_admin.layout.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Master Barang</h1>
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
        {{-- <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">
                    75%</div>
            </div> --}}
        <div class="alert alert-secondary loading" role="alert">
            Sedang menyiapkan data ...
        </div>
        <br>
        <div class="card table-container">
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>id Barang</th>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>NUP</th>
                            <th>Merk/Tipe</th>
                            <th>Nilai</th>
                            <th>Tahun</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Unit Kerja</th>
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

<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('super-admin/oldat/barang/file-barang/upload') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <label class="col-form-label">Upload Data Barang</label>
                    <input type="file" class="form-control" name="file" required>
                    <small>Format file (.xlsx)</small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

            </form>
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
                            columns: [1, 2, 3, 4, 8, 9]
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
                    },
                    {
                        text: ' Upload',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        action: function(e, dt, node, config) {
                            var rowData = dt.row({
                                selected: true
                            }).data();
                            $('#upload').modal('show');
                        }
                    }
                ],
                "bDestroy": true
            }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
        });
        // $('.table-container').hide()
        setTimeout(showTable, 1000);
    })

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
                element.kode_barang + ` <br> ` + element.kategori_barang,
                element.nup_barang,
                element.barang,
                `Rp ` + element.nilai_perolehan,
                element.tahun_perolehan,
                element.kondisi_barang,
                element.pengguna_barang,
                element.unit_kerja
            ])
            no++
            // console.log('data ke - ' + no)
        });
        dataTable.draw()
        $('.loading').hide()
        console.log('finish')
    }

    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        // console.log(row)
        window.location.href = "/super-admin/oldat/barang/detail/" + row[0];
    })
</script>
@endsection

@endsection
