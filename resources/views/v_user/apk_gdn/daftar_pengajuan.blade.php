@extends($layout.'.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Gedung Bangunan (GDN)</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="{{ route('gdn.dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar Usulan</li>
                </ol>

            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ url('unit-kerja/gdn/usulan/pekerjaan/baru') }}" class="btn btn-primary btn-md font-weight-bold mt-3">
                    <i class="fas fa-plus-square"></i> PENGAJUAN
                </a>
            </div>
        </div>
    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
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
                <div class="card card-primary">
                    <div class="card-header border border-dark">
                        <label class="card-title">
                            Daftar Usulan
                        </label>
                        <div class="card-tools">
                            <a href="" class="btn btn-default btn-sm text-dark" data-toggle="modal" data-target="#modalFilter">
                                <i class="fas fa-filter"></i> Filter
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-uppercase text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th style="width: 12%;">Tanggal</th>
                                        <th style="width: 13%;">Unit Kerja</th>
                                        <th style="width: 15%;">No. Surat</th>
                                        <th>Pekerjaan</th>
                                        <th>Detail</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($usulan == 0)
                                    <tr class="text-center">
                                        <td colspan="9">Tidak ada data</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="9">Sedang Mengambil data ...</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-filter"></i> Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="GET" action="{{ route('gdn.show', ['aksi' => 'filter', 'id' => '*']) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-xs">Pilih Tanggal</label>
                        <select name="tanggal" class="form-control form-control-sm border-dark rounded text-center">
                            <option value="">Semua Tanggal</option>
                            @foreach(range(1, 31) as $dateNumber)
                            @php $rowTgl = Carbon\Carbon::create()->day($dateNumber)->isoFormat('DD'); @endphp
                            <option value="{{ $rowTgl }}" <?php echo $tanggal == $rowTgl ? 'selected' : '' ?>>
                                {{ $rowTgl }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Pilih Bulan</label>
                        <select name="bulan" class="form-control form-control-sm border-dark rounded text-center">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $monthNumber)
                            @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                            <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                {{ $rowBulan->isoFormat('MMMM') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Pilih Tahun</label>
                        <select name="tahun" class="form-control form-control-sm border-dark rounded text-center">
                            <option value="">Semua Tahun</option>
                            @foreach(range(2023, 2024) as $yearNumber)
                            @php $rowTahun = Carbon\Carbon::create()->year($yearNumber); @endphp
                            <option value="{{ $rowTahun->isoFormat('Y') }}" <?php echo $tahun == $rowTahun->isoFormat('Y') ? 'selected' : '' ?>>
                                {{ $rowTahun->isoFormat('Y') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Pilih Status</label>
                        <select name="proses_id" class="form-control form-control-sm text-capitalize border-dark">
                            <option value="">Seluruh Status Proses</option>
                            <option value="1" <?php echo $proses == '1' ? 'selected' : ''; ?>>Menunggu Persetujuan</option>
                            <option value="2" <?php echo $proses == '2' ? 'selected' : ''; ?>>Sedang Diproses</option>
                            <option value="5" <?php echo $proses == '5' ? 'selected' : ''; ?>>Selesai Berita Acara</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('gdn.show', ['aksi' => 'pengajuan', 'id' => '*']) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Muat
                    </a>
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        var currentdate = new Date();
        var datetime = "Tanggal: " + currentdate.getDate() + "/" +
            (currentdate.getMonth() + 1) + "/" +
            currentdate.getFullYear() + " \n Pukul: " +
            currentdate.getHours() + ":" +
            currentdate.getMinutes() + ":" +
            currentdate.getSeconds()
        $("#table-usulan").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                "bVisible": false,
                "aTargets": [5, 6, 7, 8, 9, 10]
            }, ],
            buttons: [{
                    extend: 'pdf',
                    text: ' PDF',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Gedung dan Bangunan',
                    exportOptions: {
                        columns: [0, 5, 6, 7, 8, 9, 10],
                    },
                    messageTop: datetime,
                },
                {
                    extend: 'excel',
                    text: ' Excel',
                    className: 'fas fa-file btn btn-primary mr-2 rounded',
                    title: 'Daftar Usulan Gedung dan Bangunan',
                    exportOptions: {
                        columns: [0, 5, 6, 7, 8, 9, 10]
                    },
                    messageTop: datetime
                }
            ],
            "bDestroy": true
        }).buttons().container().appendTo('#table-usulan_wrapper .col-md-6:eq(0)');
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elements = document.querySelectorAll('.hide-text');

        elements.forEach(function(element) {
            var maxHeight = parseFloat(window.getComputedStyle(element).getPropertyValue('max-height'));

            if (element.scrollHeight > maxHeight) {
                var showMoreLink = document.createElement('a');
                showMoreLink.className = 'show-more';
                showMoreLink.textContent = 'Show More';

                var hideText = function() {
                    element.style.maxHeight = maxHeight + 'px';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih banyak';
                };

                var showMore = function() {
                    element.style.maxHeight = 'none';
                    showMoreLink.style.display = 'block';
                    showMoreLink.textContent = 'Tampilkan lebih sedikit';
                };

                showMoreLink.addEventListener('click', function() {
                    if (element.style.maxHeight === 'none') {
                        hideText();
                    } else {
                        showMore();
                    }
                });

                element.insertAdjacentElement('afterend', showMoreLink);
                hideText();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        loadTable();

        function loadTable() {
            $.ajax({
                url: `{{ route('gdn.select') }}`,
                method: 'GET',
                data: {
                    aksi: '{{ $aksi }}',
                    id: '{{ $id }}',
                    uker: '{{ $uker }}',
                    proses: '{{ $proses }}',
                    tanggal: '{{ $tanggal }}',
                    bulan: '{{ $bulan }}',
                    tahun: '{{ $tahun }}',
                },
                dataType: 'json',
                success: function(response) {
                    let tbody = $('.table tbody');
                    tbody.empty(); // Mengosongkan tbody sebelum menambahkan data

                    if (response.message) {
                        // Jika ada pesan dalam respons (misalnya "No data available")
                        tbody.append(`
                        <tr>
                            <td colspan="9">${response.message}</td>
                        </tr>
                    `);
                    } else {
                        // Jika ada data
                        $.each(response, function(index, item) {
                            tbody.append(`
                                <tr>
                                    <td class="align-middle">${item.no}</td>
                                    <td class="align-middle">${item.aksi}</td>
                                    <td class="align-middle">${item.tanggal}</td>
                                    <td class="align-middle">${item.uker}</td>
                                    <td class="align-middle">${item.nosurat}</td>
                                    <td class="align-middle text-left">${item.pekerjaan}</td>
                                    <td class="align-middle text-left">${item.deskripsi}</td>
                                    <td class="align-middle">${item.status}</td>
                                </tr>
                            `);
                        });

                        $("#table-data").DataTable({
                            "responsive": false,
                            "lengthChange": true,
                            "autoWidth": false,
                            "info": true,
                            "paging": true,
                            "searching": true,
                            buttons: [{
                                extend: 'pdf',
                                text: ' PDF',
                                pageSize: 'A4',
                                className: 'bg-danger',
                                title: 'show',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6, 7]
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'show',
                                exportOptions: {
                                    columns: ':not(:nth-child(2))'
                                },
                            }],
                            "bDestroy": true
                        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    });
</script>

@endsection
@endsection
