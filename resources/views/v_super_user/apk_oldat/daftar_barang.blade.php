@extends('v_super_user.layout.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Master Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
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
                                <th>Id Barang</th>
                                <th class="text-center">No</th>
                                <th>Kode Barang</th>
                                <th>NUP</th>
                                <th>Jumlah</th>
                                <th>Nilai Perolehan</th>
                                <th>Tahun Perolehan</th>
                                <th>Kondisi</th>
                                <th>Pengguna</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <?php $no = 1; ?>
                        <tbody>
                            {{-- @foreach ($barang as $row)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $row->kode_barang }}</td>
                            <td>{{ $row->nup_barang }}</td>
                            <td>{{ $row->kategori_barang }}</td>
                            <td>{{ $row->spesifikasi_barang }}</td>
                            <td>{{ $row->jumlah_barang.' '.$row->satuan_barang }}</td>
                            <td>Rp {{ number_format($row->nilai_perolehan, 0, ',', '.') }}</td>
                            <td>{{ $row->tahun_perolehan }}</td>
                            <td>{{ $row->kondisi_barang }}</td>
                            <td>{{ $row->nama_pegawai }}</td>
                            <td>
                                <a type="button" class="btn btn-primary" data-toggle="dropdown">
                                    <i class="fas fa-bars"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item btn" href="{{ url('super-user/oldat/barang/detail/'. $row->id_barang) }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>



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
            // $('.table-container').hide()
            setTimeout(showTable, 1000);
        });


        // function showTable() {
        //     let dataTable = $('#table-barang').DataTable()
        //     console.log('start')
        //     let dataBarang = JSON.parse(`<?php echo $barang; ?>`)
        //     // console.log($('#table-barang').find('tbody'))

        //     dataTable.clear()
        //     // dataTable.draw()
        //     // console.log('tes')
        //     // console.log(parseInt(dataBarang.length / 4))
        //     // let progress = $('.progress-bar')
        //     let no = 1
        //     dataBarang.forEach(element => {
        //         dataTable.row.add([
        //             element.id_barang,
        //             no,
        //             element.kode_barang,
        //             element.nup_barang,
        //             element.jumlah_barang + ' ' + element.satuan_barang,
        //             element.nilai_perolehan,
        //             element.tahun_perolehan,
        //             element.kondisi_barang,
        //             element.nama_pegawai
        //         ])
        //         // if (no == parseInt(dataBarang.length / 4 * 100)) {
        //         //     console.log(no)
        //         //     $('.progress-bar').css('width' , parseInt(dataBarang.length / 4 * 100))
        //         // } else if (no == parseInt(dataBarang.length / 2)) {
        //         //     console.log(no)
        //         //     $('.progress-bar').css('width' , parseInt(dataBarang.length / 2 * 100))
        //         // } else if (no == parseInt(dataBarang.length * 3 / 4)) {
        //         //     console.log(no)
        //         //     $('.progress-bar').css('width' , parseInt(dataBarang.length * 3 / 4 * 100))
        //         // }
        //         no++
        //         // console.log('data ke - ' + no)
        //     });
        //     dataTable.draw()
        //     // }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
        // }

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
                $('.loading').hide()
                console.log('finish')
            }

            $('#table-barang tbody').on('click', '.btn-detail', function() {
                let dataTable = $('#table-barang').DataTable()
                let row = dataTable.row($(this).parents('tr')).data()
                // console.log(row)
                window.location.href = "/super-user/oldat/barang/detail/" + row[0];
            })
    </script>
@endsection
@endsection
