@extends($layout.'.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-lg">Urusan Kerumahtanggaan</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url($url.'/ukt/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Daftar</li>
                </ol>
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
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title mt-1 font-weight-bold">
                            Daftar Usulan Urusan Kerumahtanggaan
                        </h4>
                    </div>
                    <div class="card-body border border-dark">
                        <form method="GET" action="{{ route('ukt.show', ['aksi' => 'filter', 'id' => '*']) }}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label class="text-xs">Pilih Tanggal</label>
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
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
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label class="text-xs">Unit Kerja</label>
                                    <select name="uker_id" class="form-control form-control-sm text-capitalize border-dark">
                                        <option value="">Seluruh Unit Kerja</option>
                                        @foreach ($listUker as $row)
                                        <option value="{{ $row->id_unit_kerja }}" <?php echo $row->id_unit_kerja == $uker ? 'selected' : ''; ?>>
                                            {{ ucwords(strtolower($row->unit_kerja)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label class="text-xs">Status Proses</label>
                                    <select name="proses_id" class="form-control form-control-sm text-capitalize border-dark">
                                        <option value="">Seluruh Status Proses</option>
                                        <option value="1" <?php echo $proses == '1' ? 'selected' : ''; ?>>Menunggu Persetujuan</option>
                                        <option value="2" <?php echo $proses == '2' ? 'selected' : ''; ?>>Sedang Diproses</option>
                                        <option value="5" <?php echo $proses == '5' ? 'selected' : ''; ?>>Selesai Berita Acara</option>
                                    </select>
                                </div>
                                <div class="col-sm-2 text-right">
                                    <label class="text-xs" style="margin-top: 19%;">&nbsp;</label>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ route('ukt.show', ['aksi' => 'pengajuan', 'id' => '*']) }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-undo"></i> Muat
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <div class="card-body border border-dark">
                            <table id="table-data" class="table table-bordered text-uppercase text-xs text-center">
                                <thead class="text-uppercase">
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 6%;">Aksi</th>
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
                                    @endif
                                    <tr>
                                        <td colspan="9">Sedang Mengambil data ...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                url: `{{ route('ukt.select') }}`,
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
                                    <td>${item.no}</td>
                                    <td>${item.aksi}</td>
                                    <td>${item.tanggal}</td>
                                    <td>${item.uker}</td>
                                    <td>${item.nosurat}</td>
                                    <td class="text-left">${item.pekerjaan}</td>
                                    <td class="text-left">${item.deskripsi}</td>
                                    <td>${item.status}</td>
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
