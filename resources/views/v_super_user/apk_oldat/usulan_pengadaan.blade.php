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
                    <form action="{{ url('super-user/oldat/pengajuan/proses-pengajuan/pengadaan') }}" method="POST">
                        <input type="hidden" name="pegawai_id" value="{{ $pegawai->id_pegawai }}">
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
                                                <input type="text" class="form-control" name="total_pengajuan" id="jumlahBarang" value="1">
                                            </div>
                                            <div class="col-md-2">
                                                <a id="btnJumlah" class="btn btn-default ">Pilih</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    <tbody id="input-barang" class="bg-grey">
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
                            <div class="col-md-12 mt-4 form-group">
                                <div class="card-header bg-primary pt-4 pb-4" style="border-radius: 5px;">
                                    <h5 class="font-weight-bold card-title">Verifikasi Kode OTP</h5>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <input type="text" class="form-control" placeholder="Masukan Kode OTP">
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="reset" class="btn btn-default">BATAL</button>
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah data sudah benar ?')">SUBMIT</button>
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
        $('#btnJumlah').click(function() {
            $(".input-barang").empty();
            let no = 2;
            let i;
            let jumlah = ($('#jumlahBarang').val()) - 1;
            for (i = 1; i <= jumlah; i++) {
                $("#input-barang").append(
                    `<tr class='input-barang'>
                            <td class="text-center">` + no++ + `</td>
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

        });
    });
</script>
@endsection

@endsection
