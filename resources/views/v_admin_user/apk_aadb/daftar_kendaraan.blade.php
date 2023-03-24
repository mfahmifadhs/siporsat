@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Angkutan Darat Bermotor (AADB)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Kendaraan</li>
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
            <a href="{{ url('admin-user/aadb/dashboard') }}" class="print mr-2">
                <i class="fas fa-arrow-circle-left"></i> Kembali
            </a>
        </div>
        <div class="alert alert-secondary loading" role="alert">
            Sedang menyiapkan data ...
        </div>
        <div class="card card-primary table-container">
            <div class="card-header">
                <h4 class="card-title mt-1 font-weight-bold">
                    Daftar Aadb
                </h4>
            </div>
            <div class="card-body">
                <table id="table-aadb" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 0%;">No</th>
                            <th>Jenis Aadb</th>
                            <th>No. Plat</th>
                            <th>Kendaraan</th>
                            <th>Kualifikasi</th>
                            <th>Masa Berlaku STNK</th>
                            <th>Tahun Perolehan</th>
                            <th>Nomor BPKB</th>
                            <th>Nomor Rangka</th>
                            <th>Nomor Mesin</th>
                            <th>Nilai Perolehan</th>
                            <th>Kondisi</th>
                            <th>Pengguna</th>
                            <th>Status Kendaraan</th>
                            <th>Unit Kerja</th>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kategori kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('super-admin/aadb/kategori-kendaraan/proses-tambah/data') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="level">Kategori kendaraan :</label>
                        <input type="text" class="form-control" name="kategori_kendaraan" placeholder="Tambah Kategori kendaraan" required>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Tambah Kategori kendaraan ?')">Submit</button>
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
                <h4 class="modal-title">Upload Data Kendaraan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('admin-user/aadb/kendaraan/upload/data') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Upload Data kendaraan</label>
                        <input type="file" name="upload" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <p class="mt-2">
                            <small>Download format excel <a href="{{ asset('format/format_data_kendaraan.xlsx') }}" download>disini</a></small> <br>
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

<!-- Modal Kendaraan -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Detail Kendaraan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-capitalize">
                <div class="form-group row">
                    <label class="col-md-3">Unit Kerja</label>
                    <div class="col-md-9">:
                        <span id="unitKerja"></span>
                    </div>
                    <label class="col-md-3">Jenis Aadb</label>
                    <div class="col-md-9">:
                        <span id="jenisAadb"></span>
                    </div>
                    <label class="col-md-3">Nomor Plat</label>
                    <div class="col-md-9">:
                        <span id="nomorPlat"></span>
                    </div>
                    <label class="col-md-3">Nama Kendaraan</label>
                    <div class="col-md-9">:
                        <span id="namaAadb"></span>
                    </div>
                    <label class="col-md-3">Kualifikasi</label>
                    <div class="col-md-9">:
                        Kendaraan <span id="kualifikasi"></span>
                    </div>
                    <label class="col-md-3">Tahun Perolehan</label>
                    <div class="col-md-9">:
                        <span id="tanggalPerolehan"></span>
                    </div>
                    <label class="col-md-3">Nomor BPKB</label>
                    <div class="col-md-9">:
                        <span id="noBpkb"></span>
                    </div>
                    <label class="col-md-3">Nomor Mesin</label>
                    <div class="col-md-9">:
                        <span id="noMesin"></span>
                    </div>
                    <label class="col-md-3">Nomor Rangka</label>
                    <div class="col-md-9">:
                        <span id="noRangka"></span>
                    </div>
                    <label class="col-md-3">Nilai Perolehan</label>
                    <div class="col-md-9">:
                        <span id="nilaiPerolehan"></span>
                    </div>
                    <label class="col-md-3">Kondisi</label>
                    <div class="col-md-9">:
                        <span id="kondisiAadb"></span>
                    </div>
                    <label class="col-md-3">Status Aadb</label>
                    <div class="col-md-9">:
                        <span id="statusAadb"></span>
                    </div>
                    <label class="col-md-3">Pengguna</label>
                    <div class="col-md-9">:
                        <span id="pengguna"></span>
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

            $("#table-aadb").DataTable({
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
                        "aTargets": [1, 4, 5, 6, 7, 8, 9, 10, 12, 13]
                    },
                ],
                buttons: [{
                        extend: 'pdf',
                        text: ' PDF',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Data Master Kendaraan',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 14]
                        },
                        messageTop: datetime
                    },
                    {
                        extend: 'excel',
                        text: ' Excel',
                        className: 'fas fa-file btn btn-primary mr-2 rounded',
                        title: 'Data Master Kendaraan',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
                        },
                        messageTop: datetime
                    }
                ]
            }).buttons().container().appendTo('#table-aadb_wrapper .col-md-6:eq(0)');
        });
        // $('.table-container').hide()
        setTimeout(showTable, 1000);
    })

    function showTable() {
        let dataTable = $('#table-aadb').DataTable()
        let dataAadb = JSON.parse(`<?php echo $kendaraan; ?>`)

        dataTable.clear()
        let no = 1
        dataAadb.forEach(element => {
            dataTable.row.add([
                element.id_kendaraan,
                element.jenis_aadb,
                element.no_plat_kendaraan,
                element.merk_tipe_kendaraan + ' ' + element.tahun_kendaraan,
                element.kualifikasi,
                element.mb_stnk_plat_kendaraan,
                element.tanggal_perolehan,
                element.no_bpkb,
                element.no_rangka,
                element.no_mesin,
                `Rp ` + String(element.nilai_perolehan).replace(/(.)(?=(\d{3})+$)/g, '$1,'),
                element.kondisi_kendaraan,
                element.pengguna,
                element.status_kendaraan,
                element.unit_kerja
            ])
            no++
        });
        dataTable.draw()
        $('.loading').hide()
    }

    $('#table-aadb tbody').on('click', '.btn-detail', function() {
        let dataTable = $('#table-aadb').DataTable();
        let row = dataTable.row($(this).parents('tr')).data();

        // Set the value of the input fields in the modal here
        $('#detailModal #jenisAadb').text(row[1]);
        $('#detailModal #nomorPlat').text(row[2]);
        $('#detailModal #namaAadb').text(row[3]);
        $('#detailModal #kualifikasi').text(row[4]);
        $('#detailModal #masaBerlakuStnk').text(row[5]);
        $('#detailModal #tanggalPerolehan').text(row[6]);
        $('#detailModal #noBpkb').text(row[7]);
        $('#detailModal #noRangka').text(row[8]);
        $('#detailModal #noMesin').text(row[9]);
        $('#detailModal #nilaiPerolehan').text(row[10]);
        $('#detailModal #kondisiAadb').text(row[11]);
        $('#detailModal #pengguna').text(row[12]);
        $('#detailModal #statusKendaraan').text(row[13]);
        $('#detailModal #unitKerja').text(row[14]);

        // Open the modal
        $('#detailModal').modal('show');
    });

    $('#table-aadb tbody').on('click', '.btn-edit', function() {
        let dataTable = $('#table-aadb').DataTable()
        let row = dataTable.row($(this).parents('tr')).data()
        window.location.href = "/admin-user/aadb/kendaraan/detail/" + row[0];
    })
</script>
@endsection

@endsection
