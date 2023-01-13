@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Buat Usulan Oldah Data & Meubelair</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-usulan') }}"> Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Buat Usulan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
            </div>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header font-weight-bold">
                @if($id == 'pengadaan')
                <h3 class="card-title font-weight-bold">Usulan Pengajuan Pengadaan Oldat </h3>
                @else
                <h3 class="card-title font-weight-bold">Usulan Pengajuan Perbaikan Oldat </h3>
                @endif
            </div>
            <div class="card-body">
                <form class="form-pengajuan" action="{{ url('super-user/oldat/pengajuan/proses-usulan/'. $id ) }}" method="POST">
                    <input type="hidden" name="pegawai_id" value="{{ $pegawai->id_pegawai }}">
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <span id="kode_otp"></span>
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12"><label class="text-muted">Informasi Pengusul</label></div>
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/oldat/'.$id.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control text-capitalize" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $pegawai->nama_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jabatan Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-capitalize" value="{{ $pegawai->keterangan_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit Kerja </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-capitalize" value="{{ $pegawai->unit_kerja }}" readonly>
                        </div>
                    </div>
                    @if($id == 'pengadaan')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pengguna (*)</label>
                        <div class="col-sm-10">
                            <textarea type="date" name="rencana_pengguna" class="form-control text-capitalize" required></textarea>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">Jumlah Jenis Barang</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="total_pengajuan" id="jumlahBarang" minlength="1" value="1">
                            <small>Jumlah barang disesuaikan dengan kebutuhan jenis barang</small>
                        </div>
                        <div class="col-sm-2">
                            <a id="btnJumlah" class="btn btn-primary">PILIH</a>
                        </div>
                    </div>
                    @if($id == 'pengadaan')
                    <div id="input-barang-pengadaan">
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-8 text-muted float-left mt-2">Informasi Barang 1</label>
                        </div>
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis Barang (*)</label>
                            <div class="col-sm-10">
                                <select name="kategori_barang_id[]" class="form-control kategori">
                                    <option value="">-- Pilih Jenis Barang --</option>
                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Merk Barang (*)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="merk_barang[]" placeholder="Merk">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="jumlah_barang[]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="satuan_barang[]" value="unit" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Estimasi Harga (*)</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control price" name="estimasi_biaya[]" placeholder="Estimasi Harga / Barang" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Spesifikasi (*)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="spesifikasi_barang[]" placeholder="Contoh: Lenovo, RAM 8 GB" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    @else
                    <div id="input-barang-perbaikan">
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-8 text-muted float-left mt-2">Informasi Barang 1</label>
                        </div>
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pilih Barang (*)</label>
                            <div class="col-sm-10">
                                <select class="form-control kategori" data-idtarget="1">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Merk</label>
                            <div class="col-sm-10">
                                <select name="kode_barang[]" class="form-control spekBarang select2-barang" id="barang1" data-idtarget="1">
                                    <option value="">-- Pilih Barang --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kode Barang</label>
                            <div class="col-sm-4">
                                <span id="kode_barang1"><input type="text" class="form-control" readonly></span>
                            </div>
                            <label class="col-sm-1 col-form-label">NUP</label>
                            <div class="col-sm-2">
                                <span id="nup_barang1"><input type="text" class="form-control" readonly></span>
                            </div>
                            <label class="col-sm-1 col-form-label">Tahun</label>
                            <div class="col-sm-2">
                                <span id="tahun_perolehan1"><input class="form-control" readonly></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Keterangan Kerusakan</label>
                            <div class="col-sm-10">
                                <textarea name="keterangan_kerusakan[]" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (Auth::user()->pegawai->jabatan_id != 7)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10 text-right">
                            <button class="btn btn-primary font-weight-bold" id="btnSubmit" onclick="return confirm('Apakah data sudah terisi dengan benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT
                            </button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    // let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $(function() {
        let j = 1;
        let id = "{{ $id }}"
        $('.kategori').select2()

        // Jumlah Barang yang akan diperbaiki
        $('#btnJumlah').click(function() {
            if (id == 'pengadaan') {
                $(".input-barang-pengadaan").empty();
                let no = 2
                let i
                let jumlah = ($('#jumlahBarang').val()) - 1
                for (i = 1; i <= jumlah; i++) {
                    ++j;
                    $("#input-barang-pengadaan").append(
                        `<div class="input-barang-pengadaan">
                            <hr style="border: 0.5px solid grey;">
                            <div class="form-group row">
                                <label class="col-sm-8 text-muted float-left mt-2">Informasi Barang `+j+`</label>
                            </div>
                            <hr style="border: 0.5px solid grey;">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Jenis Barang</label>
                                <div class="col-sm-10">
                                    <select name="kategori_barang_id[]" class="form-control kategori">
                                        <option value="">-- Pilih Jenis Barang --</option>
                                        @foreach($kategoriBarang as $dataKategoriBarang)
                                        <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Merk Barang </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="merk_barang[]" placeholder="Merk">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Jumlah</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="jumlah_barang[]">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Satuan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="satuan_barang[]" value="unit" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Estimasi Harga (*)</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="estimasi_biaya[]" placeholder="Estimasi Harga" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Spesifikasi</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="spesifikasi_barang[]" placeholder="Contoh: Lenovo, RAM 8 GB" rows="3"></textarea>
                                </div>
                            </div>
                        </div>`
                    )
                }
            } else if (id == 'perbaikan') {
                $(".input-barang-perbaikan").empty();
                let no = 2
                let i;
                let jumlah = ($('#jumlahBarang').val()) - 1
                for (i = 1; i <= jumlah; i++) {
                    ++j;
                    $("#input-barang-perbaikan").append(
                        `<div class="input-barang-perbaikan">
                            <hr style="border: 0.5px solid grey;">
                            <div class="form-group row">
                                <label class="col-sm-8 text-muted float-left mt-2">Informasi Barang ` + j + `</label>
                            </div>
                            <hr style="border: 0.5px solid grey;">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Pilih Barang (*)</label>
                                <div class="col-sm-10">
                                    <select class="form-control barang kategori" data-idtarget="` + j + `">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($kategoriBarang as $dataKategoriBarang)
                                        <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Merk</label>
                                <div class="col-sm-10">
                                    <select name="kode_barang[]" class="form-control spekBarang" id="barang` + j + `" data-idtarget="` + j + `">
                                        <option value="">-- Pilih Barang --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kode Barang</label>
                                <div class="col-sm-4">
                                    <span id="kode_barang` + j + `"><input type="text" class="form-control" readonly></span>
                                </div>
                                <label class="col-sm-1 col-form-label">NUP</label>
                                <div class="col-sm-2">
                                    <span id="nup_barang` + j + `"><input type="text" class="form-control" readonly></span>
                                </div>
                                <label class="col-sm-1 col-form-label">Tahun Perolehan</label>
                                <div class="col-sm-2">
                                    <span id="tahun_perolehan` + j + `"><input class="form-control" readonly></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Keterangan Kerusakan</label>
                                <div class="col-sm-10">
                                    <textarea name="keterangan_kerusakan[` + i + `]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>`
                    )
                }
            }

            $('.kategori').select2()
        })


        // Menampilkan informasi barang yang dipilih
        $(document).on('change', '.kategori', function() {
            let kategori = $(this).val();
            let target = $(this).data('idtarget');
            if (kategori) {
                $.ajax({
                    type: "GET",
                    url: "/super-user/oldat/select2/daftar?kategori=" + kategori,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#barang" + target).empty();
                            $("#barang" + target).select2();
                            $("#barang" + target).append('<option value="">-- Pilih Barang --</option>');
                            $.each(res, function(merk_tipe_barang, id_barang) {
                                $("#barang" + target).append(
                                    '<option value="' + id_barang + '">' + merk_tipe_barang + '</option>'
                                )
                            });
                        } else {
                            $("#barang" + target).empty();
                        }
                    }
                });
            } else {
                $("#barang" + target).empty();
            }
        })

        // Menampilkan detail informasi barang
        $(document).on('change', '.spekBarang', function() {
            let idBarang = $(this).val();
            let target = $(this).data('idtarget');
            if (idBarang) {
                $.ajax({
                    type: "GET",
                    url: "/super-user/oldat/select2/detail?idBarang=" + idBarang,
                    dataType: 'JSON',
                    success: function(res) {
                        $("#kode_barang" + target).empty();
                        $("#nup_barang" + target).empty();
                        $("#jumlah_barang" + target).empty();
                        $("#satuan_barang" + target).empty();
                        $("#tahun_perolehan" + target).empty();
                        $.each(res, function(index, row) {
                            $("#kode_barang" + target).append(
                                '<input type="number" class="form-control" value="' + row.kode_barang + '" readonly>'
                            );
                            $("#nup_barang" + target).append(
                                '<input type="number" class="form-control" value="' + row.nup_barang + '" readonly>'
                            );
                            $("#jumlah_barang" + target).append(
                                '<input type="number" class="form-control" value="' + row.jumlah_barang + '" readonly>'
                            );
                            $("#satuan_barang" + target).append(
                                '<input type="text" class="form-control" value="' + row.satuan_barang + '" readonly>'
                            );
                            $("#tahun_perolehan" + target).append(
                                '<input type="text" class="form-control" value="' + row.tahun_perolehan + '" readonly>'
                            );
                        });
                    }
                });
            } else {
                $("#barang" + target).empty();
            }
        })
    })
</script>
@endsection

@endsection
