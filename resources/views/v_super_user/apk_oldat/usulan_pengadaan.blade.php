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
                            <div class="col-md-12">
                                <h5 class="font-weight-bold">Informasi Pengusul</h5>
                                <hr>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nama Pengusul :</label>
                                <input type="text" class="form-control" value="{{ $pegawai->nama_pegawai }}">
                            </div>
                            <div class="col-md-6 form-group">
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
                            <div class="col-md-12 mt-4">
                                <span style="float: left;">
                                    <h5 class="font-weight-bold mt-2">Informasi Kebutuhan Barang</h5>
                                </span>
                                <span style="float: right;">
                                    <h5 class="font-weight-bold">
                                        <div class="form-group row">
                                            <h6 class="col-md-5 mt-2">Jumlah Barang : </h6>
                                            <input type="text" class="form-control col-md-7" id="jumlahBarang">
                                        </div>
                                    </h5>
                                </span>
                            </div>
                            <div class="col-md-12 form-group">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;">Jenis Barang</th>
                                            <th style="width: 25%;">Merk</th>
                                            <th style="width: 12%;">Jumlah</th>
                                            <th style="width: 12%;">Satuan</th>
                                            <th>Spesifikasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="input-barang">
                                        <tr>
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
        $('#jumlahBarang').change(function() {
            let i;
            let jumlah = ($(this).val()) - 1;

            if (jumlah > 0) {
                for (i = 1; i <= jumlah; i++) {
                    $("#input-barang").append(
                        `<tr class='input-barang'>
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
            } else {
                $(".input-barang").empty();
            }
        });
    });
</script>
@endsection

@endsection
