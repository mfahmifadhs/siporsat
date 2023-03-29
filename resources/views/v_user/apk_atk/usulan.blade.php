@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Permintaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('unit-kerja/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usulan Permintaan ATK</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($aksi == 'distribusi')
<section class="content">
    <div class="container">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @elseif ($message = Session::get('failed'))
        <div class="alert alert-danger">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @endif
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title text-capitalize">usulan pengajuan {{ $aksi }} ATK </h3>
            </div>
            <form action="{{ url('unit-kerja/atk/usulan/proses-distribusi/'. $aksi) }}" method="POST">
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="jenis_form" value="distribusi">
                    <input type="hidden" name="no_surat_usulan" value="{{ 'usulan/atk/'.$aksi.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pemakaian (*)</label>
                        <div class="col-sm-10">
                            <textarea name="rencana_pengguna" class="form-control" rows="3" placeholder="Rencana Pemakaian Barang" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Stok Barang</label>
                        <div class="col-sm-10">
                            <table id="table-atk" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 1%;" class="text-center">No</th>
                                        <th style="width: 20%;">Nama Barang</th>
                                        <th style="width: 20%;">Spesifikasi</th>
                                        <th style="width: 10%;">Stok Barang</th>
                                        <th style="width: 10%;">Jumlah Permintaan</th>
                                    </tr>
                                </thead>
                                @php $no = 1; @endphp
                                <tbody>
                                    @foreach($stok as $dataStok)

                                    @if ($dataStok->jumlah_disetujui - $dataStok->jumlah_pemakaian != 0)
                                    <tr>
                                        <td class="text-center pt-3">
                                            <input type="hidden" name="id_pengadaan[]" value="{{ $dataStok->id_form_usulan_pengadaan }}">
                                            {{ $no++ }}
                                        </td>
                                        <td><input type="text" class="form-control" value="{{ $dataStok->nama_barang }}" style="font-size: 13px;" readonly></td>
                                        <td><input type="text" class="form-control" value="{{ $dataStok->spesifikasi }}" style="font-size: 13px;" readonly></td>
                                        <td><input type="text" class="form-control text-center" value="{{ $dataStok->jumlah_disetujui - $dataStok->jumlah_pemakaian.' '.$dataStok->satuan }}" style="font-size: 13px;" min="1" readonly></td>
                                        <td>
                                            <input type="number" class="form-control text-center" name="jumlah_permintaan[]" max="{{ $dataStok->jumlah_disetujui - $dataStok->jumlah_pemakaian }}" style="font-size: 13px;border: none;border-bottom: 0.5px solid;" value="0" oninput="this.value = Math.abs(this.value)">
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- <div id="main-gdn">
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-8 text-muted float-left mt-2">Merk/Tipe ATK</label>
                            <label class="col-sm-4 text-muted text-right">
                                <a id="btn-total" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah List Baru
                                </a>
                            </label>
                        </div>
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kategori" id="kategori">
                                    <option value="">-- KATEGORI BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Jenis</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize jenis" id="jenis">
                                    <option value="">-- JENIS BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize barang" id="barang">
                                    <option value="">-- NAMA BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                            <div class="col-sm-4">
                                <select name="atk_id[]" class="form-control text-capitalize merktipe" id="merktipe">
                                    <option value="">-- MERK/TIPE BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-4">
                                <span id="stok"><input class="form-control" placeholder="STOK BARANG" readonly></span>
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan1-atk"><input class="form-control" placeholder="SATUAN" readonly></span>
                            </div>
                        </div>
                        <span id="list-baru"></span>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah (*)</label>
                            <div class="col-sm-4">
                                @if ($aksi == 'pengadaan')
                                <input type="number" class="form-control" name="jumlah[]" min="1" placeholder="MASUKAN JUMLAH BARANG" required>
                                @else
                                <span id="jumlahDistribusi"><input class="form-control" placeholder="MASUKAN JUMLAH BARANG" readonly></span>
                                @endif
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan2-atk"><input class="form-control" placeholder="SATUAN" readonly></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan[]" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                            </div>
                        </div>
                        <div id="section-atk"></div>
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah anda ingin melakukan pengajuan perbaikan ?')">SUBMIT</button>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="card-footer">
                    @if ($stok->sum('jumlah_disetujui') - $stok->sum('jumlah_pemakaian') != 0)
                    <button type="submit" class="btn btn-primary btn-md font-weight-bold float-right" onclick="return confirm('Apakah data sudah benar ?')">
                        <i class="fas fa-paper-plane"></i> SUBMIT
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>
@else
<section class="content">
    <div class="container">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @elseif ($message = Session::get('failed'))
        <div class="alert alert-danger">
            <p style="color:white;margin: auto;">{{ $message }}</p>
        </div>
        @endif
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title text-capitalize">usulan pengajuan {{ $aksi }} ATK </h3>
            </div>
            <form action="{{ url('unit-kerja/atk/usulan/distribusi2/'. $actionForm) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-6">
                            <label class="col-form-label">Tanggal</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                        <div class="form-group col-md-6 col-6">
                            <label class="col-form-label">Perihal</label>
                            <input type="text" class="form-control" value="Permintaan ATK" readonly>
                        </div>
                        <div class="form-group col-md-12 col-12">
                            <label class="col-form-label">Rencana Pengguna</label>
                            <textarea type="text" class="form-control" name="rencana_pengguna" placeholder="Contoh : Permintaan untuk Bulan April" required>{{ $usulan ? $usulan['rencana_pengguna'] : '' }}</textarea>
                        </div>
                        <div class="form-group col-md-12 col-12">
                            <label class="col-form-label">Permintaan ATK</label><br>
                            <small>
                                Mohon untuk melengkapi informasi barang yang akan disimpan,
                                format file dapat diunduh <a href="" class="font-weight-bold"><u>Disini</u></a>
                            </small>
                            <div class="card-footer text-center border border-dark">
                                @if ($resArr == [])
                                <div class="btn btn-default btn-file">
                                    <i class="fas fa-upload"></i> Upload File
                                    <input type="file" class="form-control image" name="file_atk[]" onchange="displaySelectedFileCountItem(this)" required>
                                    <span id="selected-file-count-item"></span>
                                </div><br>
                                <button type="submit" class="btn btn-primary btn-md mt-2" onclick="return confirm('Upload File?')">
                                    <i class="fas fa-clipboard-check"></i> Pilih
                                </button><br>
                                @else
                                <a href="{{ url('unit-kerja/atk/usulan/distribusi2/*') }}" class="btn btn-danger btn-md font-weight-bold" onclick="return confirm('Upload ulang?')">
                                    <i class="fas fa-sync"></i> Upload Ulang
                                </a><br>
                                @endif
                                <span class="help-block" style="font-size: 12px;">Mohon upload file sesuai format yang telah di download (.xlsx)</span>
                            </div>
                        </div>
                        @if ($resArr)
                        <div class="form-group col-md-12 col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <td class="text-center">No</td>
                                        <td>Nama Barang</td>
                                        <td>Keterangan Barang</td>
                                        <td style="width: 15%;" class="text-center">Permintaan</td>
                                        <td style="width: 10%;" class="text-center">Satuan</td>
                                        <td style="width: 20%;">Keterangan Permintaan</td>
                                    </tr>
                                </thead>
                                @php $no = 1; @endphp
                                <tbody>
                                    @foreach ($resArr as $row)
                                    @if ($row['kode_form'] == 101)
                                    @foreach($row['data_barang'] as $rowItem)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $rowItem['nama_barang'] }}</td>
                                        <td>{{ $rowItem['keterangan'] }}</td>
                                        <td class="text-center">
                                            <input type="hidden" name="idAtk[]" value="{{ $rowItem['kode_barang'] }}">
                                            <input type="number" name="permintaanAtk[]" class="form-control input-border-bottom" value="{{ $rowItem['permintaan'] }}">
                                        </td>
                                        <td class="text-center">
                                            {{ $rowItem['satuan'] }}
                                        </td>
                                        <td>
                                            <input type="text" name="keteranganAtk[]" class="form-control input-border-bottom" value="{{ $rowItem['keterangan_permintaan'] }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @foreach($row['data_barang'] as $rowItem)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>{{ $rowItem['nama_barang'] }}</td>
                                        <td>{{ $rowItem['keterangan'] }}</td>
                                        <td class="text-center">
                                            <input type="hidden" name="idAlkom[]" value="{{ $rowItem['kode_barang'] }}">
                                            <input type="number" name="permintaanAlkom[]" class="form-control input-border-bottom" value="{{ $rowItem['permintaan'] }}">
                                        </td>
                                        <td class="text-center">
                                            {{ $rowItem['satuan'] }}
                                        </td>
                                        <td>
                                            <input type="text" name="keteranganAlkom[]" class="form-control input-border-bottom" value="{{ $rowItem['keterangan_permintaan'] }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    <!-- <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h6><i class="icon fas fa-info"></i> Mohon untuk mengupload file sesuai format!</h6>
                                <small> Format Kebutuhan Alkom <a href="{{ asset('format/format_kebutuhan_alkom.xls') }}" download> download</a> </small><br>
                                <small> Format Kebutuhan ATK <a href="{{ asset('format/format_kebutuhan_atk.xls') }}" download> download</a> </small> </small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table id="table-kebutuhan" class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th style="width: 25%;">Jenis Barang</th>
                                        <th style="width: 30%;">Nama Barang</th>
                                        <th style="width: 35%;">Spesifikasi</th>
                                        <th style="width: 20%;">Jumlah</th>
                                        <th style="width: 10%;">Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @php $no = 1; @endphp
                                <tbody id="section-input">
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td>
                                            <select name="jenis_barang[]" class="form-control text-uppercase" style="font-size: 13px;" required>
                                                <option value="">-- Pilih Jenis Barang --</option>
                                                <option value="atk">Alat Tulis Kantor (ATK)</option>
                                                <option value="alkom">Alat Komputer (Alkom)</option>
                                                <option value="lainya">Lain-lain</option>
                                            </select>
                                        </td>
                                        <td>

                                            <input type="text" name="barang[] small" class="form-control text-uppercase" style="font-size: 13px;" placeholder="Contoh: Buku/Pensil/Printer/Tinta/Toner, Dll">
                                        </td>
                                        <td><input type="text" name="spesifikasi[]" class="form-control text-uppercase" style="font-size: 13px;" placeholder="Contoh: Toner Canon Seri 6A, Buku Tulis Dudu, Dll"></td>
                                        <td><input type="number" name="jumlah[]" class="form-control text-center" style="font-size: 13px;" min="1" value="0"></td>
                                        <td><input type="text" name="satuan[]" class="form-control text-center text-uppercase" style="font-size: 13px;"></td>
                                        <td>
                                            <a id="add-row-pengadaan" class="btn btn-dark text-uppercase font-weight-bold">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2">&nbsp;</label>
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah anda ingin melakukan pengajuan perbaikan ?')">
                                <i class="fas fa-paper-plane btn-md"></i> SUBMIT
                            </button>
                        </div>
                    </div> -->
                </div>
                @if ($resArr != [])
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Upload File?')">
                        <i class="fas fa-paper-plane"></i> Submit
                    </button>
                </div>
                @endif
            </form>

        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Kebutuhan ATK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('unit-kerja/atk/usulan/preview-pengadaan/'. $aksi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                <input type="hidden" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/atk/'.$aksi.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                <input type="hidden" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" name="proses" value="upload">
                        <label class="col-sm-5 col-form-label">Rencana Pemakaian*</label>
                        <div class="col-sm-12">
                            <input type="text" name="rencana_pengguna" class="form-control" placeholder="Rencana Pengguna" required>
                        </div>
                        <label class="col-sm-5 col-form-label">Kebutuhan ATK (*)</label>
                        <div class="col-sm-12">
                            <input type="file" name="file_atk" class="form-control">
                            <small>Format file (.xls)</small>
                        </div>
                        <label class="col-sm-5 col-form-label">Kebutuhan Alkom (*)</label>
                        <div class="col-sm-12">
                            <input type="file" name="file_alkom" class="form-control">
                            <small>Format file (.xls)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah file sudah benar ?')">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')

    function displaySelectedFileCountItem(input) {
        var selectedFileCount = input.files.length;
        var selectedFileCountElement = document.getElementById('selected-file-count-item');
        selectedFileCountElement.textContent = selectedFileCount + ' (file dipilih)';
    }
    // Jumlah Kendaraan
    $(function() {
        let total = 1
        let i = 0
        let button = document.getElementById("btnSubmit");

        $("#table-kebutuhan").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": true,
            "searching": true,
            "lengthMenu": [
                [5, 10, 25, -1],
                [5, 10, 25, "Semua"]
            ],
            buttons: [{
                text: '⬆️ Upload Kebutuhan',
                className: 'btn bg-primary mr-2 rounded font-weight-bold form-group',
                action: function(e, dt, node, config) {
                    $('#upload').modal('show');
                }
            }]

        }).buttons().container().appendTo('#table-kebutuhan_wrapper .col-md-6:eq(0)');

        // Daftar Kategori
        $(".kategori").select2({
            ajax: {
                url: `{{ url('unit-kerja/atk/select2/1/kategori') }}`,
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        })

        // Daftar Jenis
        $(document).on('change', '.kategori', function() {
            let kategori = $(this).val()
            if (kategori) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('unit-kerja/atk/select2/2/` + kategori + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#jenis").empty();
                            $("#jenis").select2();
                            $("#jenis").append('<option value="">-- JENIS BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#jenis").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#jenis").empty();
                        }
                    }
                })
            } else {
                $("#jenis").empty();
            }
        })

        // Daftar Barang
        $(document).on('change', '.jenis', function() {
            let jenis = $(this).val()
            if (jenis) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('unit-kerja/atk/select2/3/` + jenis + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#barang").empty();
                            $("#barang").select2();
                            $("#barang").append('<option value="">-- NAMA BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#barang").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#barang").empty();
                        }
                    }
                })
            } else {
                $("#barang").empty();
            }
        })
        // Daftar Merk
        $(document).on('change', '.barang', function() {
            let barang = $(this).val()
            if (barang) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('unit-kerja/atk/select2/4/` + barang + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#merktipe").empty();
                            $("#merktipe").select2();
                            $("#merktipe").append('<option value="">-- MERK/TIPE BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#merktipe").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            })
                            $("#merktipe").append('<option value="lain-lain">LAIN-LAIN</option>')
                        } else {
                            $("#merktipe").empty();
                        }
                    }
                })
            } else {
                $("#merktipe").empty();
            }
        })

        // Daftar Stok
        $(document).on('change', '.merktipe', function() {
            let kategori = $('.barang').val()
            let merktipe = $(this).val()
            let usulan = '{{ $aksi }}'
            if (merktipe) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('unit-kerja/atk/select2/5/` + merktipe + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (merktipe != 'lain-lain') {
                            if (res) {
                                $("#list-baru").empty()
                                $("#stok").empty();
                                $("#satuan1-atk").empty();
                                $("#satuan2-atk").empty();
                                $("#jumlahDistribusi").empty();
                                $.each(res, function(index, row) {
                                    $("#stok").append(
                                        '<input type="number" class="form-control" value="' + row.stok + '" readonly>' +
                                        '<input type="hidden" class="form-control text-uppercase" name="barang_lain[]">' +
                                        '<input type="hidden" class="form-control text-uppercase" name="kategori_atk_id[]">'
                                    )
                                    $("#satuan1-atk").append(
                                        '<input type="text" class="form-control text-uppercase" value="' + row.satuan + '" readonly>'
                                    )
                                    $("#satuan2-atk").append(
                                        '<input type="text" class="form-control text-uppercase" name="satuan[]" value="' + row.satuan + '" readonly>'
                                    )
                                    $("#jumlahDistribusi").append(
                                        row.stok == 0 ? '<input type="text" class="form-control" name="jumlah[]" value="STOK TIDAK TERSEDIA" required readonly>' : '<input type="number" class="form-control" name="jumlah[]" min="1" max="' + row.stok + '" required>'
                                    )
                                    if (usulan == 'pengadaan') {
                                        button.disabled = false
                                    } else {
                                        row.stok == 0 ? button.disabled = true : button.disabled = false
                                    }
                                })
                            } else {
                                $("#stok").empty();
                            }
                        } else {

                            $("#list-baru").empty()
                            $("#stok").empty()
                            $("#satuan1-atk").empty()
                            $("#satuan2-atk").empty()

                            $("#list-baru").append(
                                `<div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Lain-lain</label>
                                    <div class="col-md-10">
                                        <input type="hidden" class="form-control text-uppercase" name="kategori_atk_id[]" value="` + kategori + `">
                                        <input type="text" class="form-control text-uppercase" name="barang_lain[]" placeholder="MERK/TIPE LAINYA" required>
                                    </div>
                                </div>`
                            )

                            $("#stok").append(
                                '<input type="text" class="form-control" value="-" readonly>'
                            )

                            $("#satuan1-atk").append(
                                '<input type="text" class="form-control" value="-" readonly>'
                            )

                            $("#satuan2-atk").append(
                                '<input type="text" class="form-control text-uppercase" name="satuan[]" placeholder="SATUAN" required>'
                            )

                            button.disabled = false

                        }
                    }
                })
            } else {
                $("#stok").empty();
            }
        })

        // More Item
        $('#btn-total').click(function() {
            ++i
            $("#section-atk").append(
                `<div class="atk">
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-8 text-muted float-left mt-2">Merk/Tipe ATK</label>
                        <label class="col-sm-4 text-muted text-right">
                            <a class="btn btn-danger remove-list">
                                <i class="fas fa-trash"></i> Hapus List
                            </a>
                        </label>
                    </div>
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kategori</label>
                        <div class="col-sm-4">
                            <select class="form-control text-capitalize kategori` + i + `" data-idtarget=` + i + `>
                                <option value="">-- KATEGORI BARANG --</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label">Jenis</label>
                        <div class="col-sm-4">
                            <select id="jenis` + i + `" class="form-control text-capitalize select2-` + i + `-2 jenis` + i + `" data-idtarget=` + i + `>
                                <option value="">-- JENIS BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-4">
                            <select id="barang` + i + `" class="form-control text-capitalize select2-` + i + `-3 barang` + i + `" data-idtarget=` + i + `>
                                <option value="">-- NAMA BARANG --</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                        <div class="col-sm-4">
                            <select name="atk_id[]" id="merktipe` + i + `" class="form-control text-capitalize select2-` + i + `-4 merktipe` + i + `" data-idtarget=` + i + `>
                                <option value="">-- MERK/TIPE BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Stok</label>
                        <div class="col-sm-4">
                            <span id="stok` + i + `"><input class="form-control" placeholder="STOK BARANG" readonly></span>
                        </div>
                        <label class="col-sm-2 col-form-label">Satuan</label>
                        <div class="col-sm-4">
                            <span id="satuan1` + i + `"><input class="form-control" placeholder="SATUAN" readonly></span>
                        </div>
                    </div>
                    <span id="list-baru` + i + `"></span>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah (*)</label>
                        <div class="col-sm-4">
                            @if ($aksi == 'pengadaan')
                            <input type="number" class="form-control" name="jumlah[]" min="1" placeholder="MASUKAN JUMLAH BARANG" required>
                            @else
                            <span id="jumlahDistribusi` + i + `"><input class="form-control" placeholder="MASUKAN JUMLAH BARANG" readonly></span>
                            @endif
                        </div>
                        <label class="col-sm-2 col-form-label">Satuan</label>
                        <div class="col-sm-4">
                            <span id="satuan2` + i + `"><input class="form-control" placeholder="SATUAN" readonly></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="keterangan[]" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                        </div>
                    </div>
                    <div id="section-gdn"></div>
                </div>`
            )

            $(".kategori" + i).select2({
                ajax: {
                    url: `{{ url('unit-kerja/atk/select2/1/kategori') }}`,
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            })

            // Daftar Jenis
            $(document).on('change', '.kategori' + i, function() {
                let target = $(this).data('idtarget')
                let kategori = $(this).val()
                if (kategori) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('unit-kerja/atk/select2/2/` + kategori + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#jenis" + target).empty();
                                $("#jenis" + target).select2();
                                $("#jenis" + target).append('<option value="">-- JENIS BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#jenis" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                });
                            } else {
                                $("#jenis" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#jenis" + target).empty();
                }
            })

            // Daftar Barang
            $(document).on('change', '.jenis' + i, function() {
                let target = $(this).data('idtarget')
                let jenis = $(this).val()
                if (jenis) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('unit-kerja/atk/select2/3/` + jenis + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#barang" + target).empty();
                                $("#barang" + target).select2();
                                $("#barang" + target).append('<option value="">-- NAMA BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#barang" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                });
                            } else {
                                $("#barang" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#barang" + target).empty();
                }
            })

            // Daftar Merk
            $(document).on('change', '.barang' + i, function() {
                let target = $(this).data('idtarget')
                let barang = $(this).val()
                if (barang) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('unit-kerja/atk/select2/4/` + barang + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#merktipe" + target).empty();
                                $("#merktipe" + target).select2();
                                $("#merktipe" + target).append('<option value="">-- MERK/TIPE BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#merktipe" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                })
                                $("#merktipe" + target).append('<option value="lain-lain">LAIN-LAIN</option>')
                            } else {
                                $("#merktipe" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#merktipe" + target).empty();
                }
            })

            // Daftar Stok
            $(document).on('change', '.merktipe' + i, function() {
                let target = $(this).data('idtarget')
                let merktipe = $(this).val()
                let kategori = $('.barang' + target).val()
                let usulan = '{{ $aksi }}'
                if (merktipe) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('unit-kerja/atk/select2/5/` + merktipe + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (merktipe != 'lain-lain') {
                                if (res) {
                                    $("#list-baru" + target).empty()
                                    $("#stok" + target).empty()
                                    $("#satuan1" + target).empty()
                                    $("#satuan2" + target).empty()
                                    $("#jumlahDistribusi" + target).empty()
                                    $.each(res, function(index, row) {
                                        $("#stok" + target).append(
                                            '<input type="number" class="form-control" value="' + row.stok + '" readonly>' +
                                            '<input type="hidden" class="form-control text-uppercase" name="barang_lain[]">' +
                                            '<input type="hidden" class="form-control text-uppercase" name="kategori_atk_id[]">'
                                        )
                                        $("#satuan1" + target).append(
                                            '<input type="text" class="form-control text-uppercase" value="' + row.satuan + '" readonly>'
                                        )
                                        $("#satuan2" + target).append(
                                            '<input type="text" class="form-control text-uppercase" name="satuan[]" value="' + row.satuan + '" readonly>'
                                        )
                                        $("#jumlahDistribusi" + target).append(
                                            row.stok == 0 ? '<input type="text" class="form-control" name="jumlah[]" value="STOK TIDAK TERSEDIA" required readonly>' : '<input type="number" class="form-control" name="jumlah[]" min="1" max="' + row.stok + '" required>'
                                        )

                                        if (usulan == 'pengadaan') {
                                            button.disabled = false
                                        } else {
                                            row.stok == 0 ? button.disabled = true : button.disabled = false
                                        }

                                    });
                                } else {
                                    $("#stok" + target).empty();
                                }
                            } else {

                                $("#list-baru" + target).empty()
                                $("#stok" + target).empty();
                                $("#satuan1" + target).empty();
                                $("#satuan2" + target).empty();

                                $("#list-baru" + target).append(
                                    `<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Lain-lain</label>
                                        <div class="col-md-10">
                                            <input type="hidden" class="form-control text-uppercase" name="kategori_atk_id[]" value="` + kategori + `">
                                            <input type="text" class="form-control text-uppercase" name="barang_lain[]" placeholder="MERK/TIPE LAINYA" required>
                                        </div>
                                    </div>`
                                )

                                $("#stok" + target).append(
                                    '<input type="text" class="form-control" value="-" readonly>'
                                )

                                $("#satuan1" + target).append(
                                    '<input type="text" class="form-control" value="-" readonly>'
                                )

                                $("#satuan2" + target).append(
                                    '<input type="text" class="form-control text-uppercase" name="satuan[]" placeholder="SATUAN" required>'
                                )

                                button.disabled = false

                            }
                        }
                    })
                } else {
                    $("#stok" + target).empty();
                }
            })

        })

        $(document).on('click', '.remove-list', function() {
            $(this).parents('.atk').remove();
        })

    })
    // Pengadaan section
    $(function() {
        let i = 0
        let no = 1
        // More Item
        $('#add-row-pengadaan').click(function() {
            ++i
            ++no
            $("#section-input").append(
                `<tr class="row-pengadaan">
                    <td class="text-center">` + no + `</td>
                    <td>
                        <select name="jenis_barang[]" class="form-control text-uppercase" style="font-size: 13px;" required>
                            <option value="">-- Pilih Jenis Barang --</option>
                            <option value="atk">Alat Tulis Kantor (ATK)</option>
                            <option value="alkom">Alat Komputer (Alkom)</option>
                            <option value="lainya">Lain-lain</option>
                        </select>
                    </td>
                    <td>

                        <input type="text" name="barang[] small" class="form-control text-uppercase" style="font-size: 13px;" placeholder="Contoh: Buku/Pensil/Printer/Tinta/Toner, Dll">
                    </td>
                    <td><input type="text" name="spesifikasi[]" class="form-control text-uppercase" style="font-size: 13px;" placeholder="Contoh: Toner Canon Seri 6A, Buku Tulis Dudu, Dll"></td>
                    <td><input type="number" name="jumlah[]"  class="form-control text-center" style="font-size: 13px;" min="1" value="0"></td>
                    <td><input type="text" name="satuan[]"  class="form-control text-center text-uppercase" style="font-size: 13px;"></td>
                    <td>
                        <a id="remove-row-pengadaan" class="btn btn-dark text-uppercase font-weight-bold">
                            <i class="fas fa-minus-circle"></i>
                        </a>
                    </td>
                </tr>`
            )

            $(document).on('click', '#remove-row-pengadaan', function() {
                $(this).parents('.row-pengadaan').remove();
            });
        })
    })
</script>
@endsection

@endsection
