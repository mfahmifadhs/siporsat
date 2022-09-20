@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Buat Usulan Pengadaan Baru</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/pengajuan/daftar/semua-pengajuan') }}"> Daftar Pengajuan</a></li>
                    <li class="breadcrumb-item active">Buat Usulan Pengadaan Baru</li>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form class="form-pengajuan" action="{{ url('super-user/oldat/pengajuan/proses-pengajuan/'. $id ) }}" method="POST">
                        <input type="hidden" name="pegawai_id" value="{{ $pegawai->id_pegawai }}">
                        <span id="kode_otp"></span>
                        @csrf
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="card-header bg-primary pt-4 pb-4" style="border-radius: 5px;">
                                    <h5 class="font-weight-bold card-title">Informasi Pengusul</h5>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 form-group">
                                <label>Nama Pengusul :</label>
                                <input type="text" class="form-control" value="{{ $pegawai->nama_pegawai }}">
                            </div>
                            <div class="col-md-6 mt-3 form-group">
                                <label>Jabatan Pengusul :</label>
                                <input type="text" class="form-control text-capitalize" value="{{ $pegawai->jabatan }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Unit Kerja :</label>
                                <input type="text" class="form-control text-capitalize" value="{{ $pegawai->unit_kerja }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tanggal Usulan :</label>
                                <input type="date" class="form-control text-capitalize" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Rencana Pengguna :</label>
                                <textarea type="date" name="rencana_pengguna" class="form-control text-capitalize"></textarea>
                            </div>
                            <div class="col-md-12 mt-4 form-group">
                                <div class="card-header bg-primary" style="border-radius: 5px;">
                                    <h5 class="font-weight-bold card-title mt-4">Informasi Kebutuhan Barang</h5>
                                    <div class="card-tools">
                                        <label>Jumlah Barang :</label>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="total_pengajuan" id="jumlahBarang" minlength="1" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <a id="btnJumlah" class="btn btn-default ">Pilih</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($id == 'pengadaan')
                            <div class="col-md-12 mt-4 form-group">
                                <table class="table table-responsive table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th style="width: 20%;">Jenis Barang</th>
                                            <th style="width: 25%;">Merk</th>
                                            <th style="width: 12%;">Jumlah</th>
                                            <th style="width: 12%;">Satuan</th>
                                            <th>Spesifikasi</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody id="input-barang-pengadaan" class="bg-grey">
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>
                                                <select name="kategori_barang_id[]" class="form-control">
                                                    <option value="">-- Pilih Jenis Barang --</option>
                                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" name="merk_barang[]" placeholder="Merk"></td>
                                            <td><input type="number" class="form-control" name="jumlah_barang[]" placeholder="Jumlah" minlength="1"></td>
                                            <td><input type="text" class="form-control" name="satuan_barang[]" placeholder="Satuan"></td>
                                            <td><textarea class="form-control" name="spesifikasi_barang[]" placeholder="Contoh: Lenovo, RAM 8 GB" rows="3"></textarea></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="col-md-12 mt-4 form-group">
                                <table class="table table-responsive table-bordered table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th style="width: 20%;">Pilih Barang</th>
                                            <th style="width: 30%;">Merk</th>
                                            <th style="width: 15%;">Kode Barang</th>
                                            <th style="width: 10%;">NUP</th>
                                            <th style="width: 8%;">Jumlah</th>
                                            <th style="width: 10%;">Satuan</th>
                                            <th style="width: 20%;">Tahun Perolehan</th>
                                        </tr>
                                    </thead>
                                    <?php $no = 1; ?>
                                    <tbody id="input-barang-perbaikan" class="bg-grey">
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td>
                                                <select class="form-control kategori" data-idtarget="1">
                                                    <option value="">-- Pilih Barang --</option>
                                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="kode_barang[]" class="form-control spekBarang" id="barang1" data-idtarget="1">
                                                    <option value="">-- Pilih Barang --</option>
                                                </select>
                                            </td>
                                            <td><span id="kode_barang1"></span></td>
                                            <td><span id="nup_barang1"></span></td>
                                            <td><span id="jumlah_barang1"></span></td>
                                            <td><span id="satuan_barang1"></span></td>
                                            <td><span id="tahun_perolehan1"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <div class="col-md-12 mt-4 form-group">
                                <div class="card-header bg-primary pt-4 pb-4" style="border-radius: 5px;">
                                    <h5 class="font-weight-bold card-title">Verifikasi Kode OTP</h5>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control col-md-3" id="inputOTP" placeholder="Masukan Kode OTP" required>
                                <a class="btn btn-primary mt-2" id="btnCheckOTP">Check Kode OTP</a>
                                <a class="btn btn-default mt-2" id="btnKirimOTP">Kirim Kode OTP</a>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="reset" class="btn btn-default">BATAL</button>
                                <button type="submit" id="btnSubmit" class="btn btn-primary" onclick="return confirm('Apakah data sudah benar ?')" disabled>SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        let j = 1;
        let id = "{{ $id }}"
        // Jumlah Barang yang akan diperbaiki
        $('#btnJumlah').click(function() {
            if (id == 'pengadaan') {
                $(".input-barang-pengadaan").empty();
                let no = 2
                let i;
                let jumlah = ($('#jumlahBarang').val()) - 1
                for (i = 1; i <= jumlah; i++) {
                    ++j;
                    $("#input-barang-pengadaan").append(
                        `<tr class="input-barang-pengadaan">
                            <td class="text-center">{{ $no++ }}</td>
                            <td>
                                <select name="kategori_barang_id[]" class="form-control">
                                    <option value="">-- Pilih Jenis Barang --</option>
                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control" name="merk_barang[]" placeholder="Merk"></td>
                            <td><input type="number" class="form-control" name="jumlah_barang[]" placeholder="Jumlah" minlength="1"></td>
                            <td><input type="text" class="form-control" name="satuan_barang[]" placeholder="Satuan"></td>
                            <td><textarea class="form-control" name="spesifikasi_barang[]" placeholder="Contoh: Lenovo, RAM 8 GB" rows="3"></textarea></td>
                        </tr>`
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
                        `<tr class="input-barang-perbaikan">
                            <td class="text-center">` + no++ + `</td>
                            <td>
                                <select class="form-control kategori" data-idtarget="` + j + `">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($kategoriBarang as $dataKategoriBarang)
                                    <option value="{{ $dataKategoriBarang->id_kategori_barang }}">{{ $dataKategoriBarang->kategori_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="kode_barang[]" class="form-control spekBarang" id="barang` + j + `" data-idtarget="` + j + `">
                                    <option value="">-- Pilih Barang --</option>
                                </select>
                            </td>
                            <td><span id="kode_barang`+ j +`"></span></td>
                            <td><span id="nup_barang`+ j +`"></span></td>
                            <td><span id="jumlah_barang`+ j +`"></span></td>
                            <td><span id="satuan_barang`+ j +`"></span></td>
                            <td><span id="tahun_perolehan`+ j +`"></span></td>
                        </tr>`
                    )
                }
            }
        });

        // Menampilkan informasi barang yang dipilih
        $(document).on('change', '.kategori', function() {
            let kategori = $(this).val();
            let target = $(this).data('idtarget');
            if (kategori) {
                $.ajax({
                    type: "GET",
                    url: "/super-user/oldat/get-barang/daftar?kategori=" + kategori,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#barang" + target).empty();
                            $("#barang" + target).select2();
                            $("#barang" + target).append('<option value="">-- Pilih Barang --</option>');
                            $.each(res, function(spesifikasi_barang, id_barang) {
                                $("#barang" + target).append(
                                    '<option value="' + id_barang + '">' + spesifikasi_barang + '</option>'
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
        });

        // Menampilkan detail informasi barang
        $(document).on('change', '.spekBarang', function() {
            let idBarang = $(this).val();
            let target = $(this).data('idtarget');
            if (idBarang) {
                $.ajax({
                    type: "GET",
                    url: "/super-user/oldat/get-barang/detail?idBarang=" + idBarang,
                    dataType: 'JSON',
                    success: function(res) {
                        $("#kode_barang" + target).empty();
                        $("#nup_barang" + target).empty();
                        $("#jumlah_barang" + target).empty();
                        $("#satuan_barang" + target).empty();
                        $("#tahun_perolehan" + target).empty();
                        $.each(res, function(index, row) {
                            console.log(res);
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
                                '<input type="number" class="form-control" value="' + row.tahun_perolehan + '" readonly>'
                            );
                        });
                    }
                });
            } else {
                $("#barang" + target).empty();
            }
        });

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "{{ $id }}"
            jQuery.ajax({
                url: '/super-user/oldat/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasi mengirim kode OTP')
                    resOTP = res
                }
            });
        });
        $(document).on('click','#btnCheckOTP',function(){
            let inputOTP = $('#inputOTP').val()
            console.log(inputOTP)
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            }else if(inputOTP == resOTP){
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit').prop('disabled', false)
            }else{
                alert('Kode OTP Salah')
                $('#btnSubmit').prop('disabled', true)
            }
        })
    });
</script>
@endsection

@endsection
