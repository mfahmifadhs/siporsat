@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Oldah Data BMN & Meubelair (OLDAT)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/oldat/dashboard') }}">Dashboard</a></li>
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
            <a href="{{ url('admin-user/oldat/dashboard') }}" class="print mr-2">
                <i class="fas fa-arrow-circle-left"></i> Kembali
            </a>
        </div>
        <div class="alert alert-secondary loading" role="alert">
            Sedang menyiapkan data ...
        </div>
        <div class="card card-primary table-container">
            <div class="card-header">
                <h4 class="card-title mt-1 font-weight-bold">
                    Daftar BMN
                </h4>
            </div>
            <div class="card-body">
                <table id="table-barang" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>id Barang</th>
                            <th style="width: 0%;">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merk/Tipe</th>
                            <th>Jumlah</th>
                            <th>Nilai Perolehan</th>
                            <th>Tahun Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th style="width: 0%;">Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-md-3">Kode Barang</label>
                    <div class="col-md-9">:
                        <span id="kodeBarang"></span>
                    </div>
                    <label class="col-md-3">Nama Barang</label>
                    <div class="col-md-9">:
                        <span id="namaBarang"></span>
                    </div>
                    <label class="col-md-3">Deskripsi</label>
                    <div class="col-md-9">:
                        <span id="deskripsiBarang"></span>
                    </div>
                    <label class="col-md-3">Nilai Perolehan</label>
                    <div class="col-md-9">:
                        <span id="nilaiPerolehan"></span>
                    </div>
                    <label class="col-md-3">Tahun Perolehan</label>
                    <div class="col-md-9">:
                        <span id="tahunPerolehan"></span>
                    </div>
                    <label class="col-md-3">Kondisi</label>
                    <div class="col-md-9">:
                        <span id="kondisiBarang"></span>
                    </div>
                    <label class="col-md-3">Unit Kerja</label>
                    <div class="col-md-9">:
                        <span id="unitKerja"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        $(function() {
            var currentdate = new Date();
            var datetime = "Tanggal: " + currentdate.getDate() + "/" +
                (currentdate.getMonth() + 1) + "/" +
                currentdate.getFullYear() + " \n Pukul: " +
                currentdate.getHours() + ":" +
                currentdate.getMinutes() + ":" +
                currentdate.getSeconds()

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
                            <a class="dropdown-item btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>`,
                    },
                    {
                        "bVisible": false,
                        "aTargets": [0, 5, 6, 7, 8]
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
                            columns: [2, 3, 4, 8, 9]
                            // columns: [0, 3, 4, 5, 6, 7, 8, 9]
                        },
                        messageTop: datetime
                    },
                    {
                        extend: 'excel',
                        text: ' Excel',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Data Master Barang',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                        },
                        messageTop: datetime
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
        let dataBarang = JSON.parse(`<?php echo $barang; ?>`)

        dataTable.clear()
        let no = 1
        dataBarang.forEach(element => {
            dataTable.row.add([
                element.id_barang,
                no,
                element.kode_barang + `.` + element.nup_barang,
                element.kategori_barang,
                element.barang,
                element.jumlah_barang + ' ' + element.satuan_barang,
                `Rp ` + String(element.nilai_perolehan).replace(/(.)(?=(\d{3})+$)/g, '$1,'),
                element.tahun_perolehan,
                element.kondisi_barang,
                element.unit_kerja
            ])
            no++
        });
        dataTable.draw()
        $('.loading').hide()
    }

    $('#table-barang tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-barang').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();

        // Set the value of the input fields in the modal here
        $('#editModal #kodeBarang').text(row[2]);
        $('#editModal #namaBarang').text(row[3]);
        $('#editModal #deskripsiBarang').text(row[4]);
        $('#editModal #nilaiPerolehan').text(row[6]);
        $('#editModal #tahunPerolehan').text(row[7]);
        $('#editModal #kondisiBarang').text(row[8]);
        $('#editModal #unitKerja').text(row[9]);

        // Open the modal
        $('#editModal').modal('show');
    });

    $('#table-barang tbody').on('click', '.btn-edit', function() {
        let dataTable = $('#table-barang').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        window.location.href = "/admin-user/oldat/barang/detail/" + row[0];
    })
</script>
@endsection

@endsection
