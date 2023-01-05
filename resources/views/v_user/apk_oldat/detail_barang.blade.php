@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('unit-kerja/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
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
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile text-capitalize">
                        <div class="text-center">
                            @if($barang->foto_barang == null)
                            <img src="{{ asset('dist_admin/img/1224838.png') }}" class="img-thumbnail mt-2" style="width: 100%;">
                            @else
                            <img src="{{ asset('gambar/barang_bmn/'. $barang->foto_barang) }}" class="img-thumbnail mt-2" style="width: 100%;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $barang->kategori_barang }}</h3>
                        <p class="text-muted text-center">{{ $barang->spesifikasi_barang }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#informasi-barang" data-toggle="tab">Informasi Barang</a></li>
                            <li class="nav-item"><a class="nav-link" href="#riwayat-barang" data-toggle="tab">Riwayat</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="informasi-barang">
                                <form action="{{ url('unit-kerja/oldat/barang/proses-ubah/'. $barang->id_barang) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Upload Foto Barang</label>
                                            <p>
                                                @if($barang->foto_barang == null)
                                                <img id="preview-image-before-upload" src="{{ asset('dist_admin/img/1224838.png') }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                                @else
                                                <img id="preview-image-before-upload" src="{{ asset('gambar/barang_bmn/'. $barang->foto_barang) }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                                @endif
                                            </p>
                                            <p>
                                            <div class="btn btn-default btn-file">
                                                <i class="fas fa-paperclip"></i> Upload Foto
                                                <input type="hidden" class="form-control image" name="foto_lama" value="{{ $barang->foto_barang }}">
                                                <input type="file" class="form-control image" name="foto_barang" accept="image/jpeg , image/jpg, image/png" value="{{ $barang->foto_barang }}">
                                            </div><br>
                                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pengguna Barang :</label>
                                            <input type="text" class="form-control" name="pengguna_barang" value="{{ $barang->pengguna_barang }}" placeholder="Masukkan Pengguna Barang">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Kategori Barang :</label>
                                            <select name="id_kategori_barang" class="form-control">
                                                <option value="">-- Pilih Level --</option>
                                                @foreach($kategoriBarang as $dataKategoriBarang)
                                                <option value="{{ $dataKategoriBarang->id_kategori_barang }}" <?php if ($barang->kategori_barang_id == $dataKategoriBarang->id_kategori_barang) echo "selected"; ?>>
                                                    {{ $dataKategoriBarang->kategori_barang }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Kode Barang : </label>
                                            <input type="text" name="kode_barang" class="form-control" value="{{ $barang->kode_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>NUP : </label>
                                            <input type="text" name="nup_barang" class="form-control" value="{{ $barang->nup_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Jumlah : </label>
                                            <input type="text" name="jumlah_barang" class="form-control" value="{{ $barang->jumlah_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Satuan : </label>
                                            <input type="text" name="satuan_barang" class="form-control" value="{{ $barang->satuan_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Tahun Perolehan : </label>
                                            <input type="text" name="tahun_perolehan" class="form-control" value="{{ $barang->tahun_perolehan }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="level">Kondisi Barang :</label>
                                            <select name="id_kondisi_barang" class="form-control">
                                                <option value="">-- Pilih Kondisi Barang --</option>
                                                @foreach($kondisiBarang as $dataKondisiBarang)
                                                <option value="{{ $dataKondisiBarang->id_kondisi_barang }}" <?php if ($barang->kondisi_barang_id == $dataKondisiBarang->id_kondisi_barang) echo "selected"; ?>>
                                                    {{ $dataKondisiBarang->kondisi_barang }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Nilai Perolehan : </label>
                                            <input type="number" name="nilai_perolehan" class="form-control" value="{{ $barang->nilai_perolehan }}">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>Spesifikasi : </label>
                                            <textarea type="text" name="spesifikasi_barang" class="form-control" rows="5">{{ $barang->spesifikasi_barang}}</textarea>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>Proses : </label> <br>
                                            <input type="radio" name="proses" value="update" required>
                                            <span>Update Data </span>
                                            <input type="radio" name="proses" value="pengguna-baru" required>
                                            <span>Pengguna Baru </span>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <button type="button" class="btn btn-default">Close</button>
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah Informasi Barang ?')">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="riwayat-barang">
                                @foreach($barang->riwayat as $i => $riwayatPengguna)
                                <div class="timeline timeline-inverse">
                                    <div class="time-label">
                                        <span class="bg-danger">
                                            {{ \Carbon\Carbon::parse($riwayatPengguna->tanggal_pengguna)->isoFormat('DD MMMM Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user bg-primary"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-date"></i> {{ \Carbon\Carbon::parse($riwayatPengguna->tanggal_pengguna)->isoFormat('DD MMMM Y') }}</span>

                                            <h3 class="timeline-header text-capitalize">
                                                <a href="#">{{ $riwayatPengguna->pengguna_barang }}</a> <br> {{ $riwayatPengguna->jabatan.' '.$riwayatPengguna->keterangan_pegawai }}
                                            </h3>

                                            <div class="timeline-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label>Lokasi Pemakaian : </label>
                                                        <p>{{ $riwayatPengguna->keperluan_penggunaan }}</p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Kondisi Barang : </label>
                                                        <p>{{ $riwayatPengguna->kondisi_barang }}</p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-default">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <!-- <a class="btn btn-danger btn-xs" href="{{ url('unit-kerja/oldat/barang/hapus-riwayat/'. $riwayatPengguna->id_riwayat_barang) }}" onclick="return confirm('Hapus Riwayat Ini ?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-default">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title text-capitalize">
                                                            {{ $riwayatPengguna->kategori_barang.' '.$riwayatPengguna->spesifikasi_barang }}
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ url('unit-kerja/oldat/barang/ubah-riwayat/'. $riwayatPengguna->barang_id) }}">
                                                            @csrf
                                                            <input type="hidden" name="id_riwayat_barang" value="{{ $riwayatPengguna->id_riwayat_barang }}">
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Pengguna</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" name="pengguna_barang" class="form-control" value="{{ $riwayatPengguna->pengguna_barang }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Tanggal Pemakaian</label>
                                                                <div class="col-sm-8">
                                                                    <input type="date" class="form-control" name="tanggal_pengguna" value="{{ $riwayatPengguna->tanggal_pengguna }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Lokasi Pemakaian</label>
                                                                <div class="col-sm-8">
                                                                    <textarea type="text" class="form-control" name="keperluan_penggunaan">{{ $riwayatPengguna->keperluan_penggunaan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">&nbsp;</label>
                                                                <div class="col-sm-8">
                                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah informasi riwayat pemakaian ?')">Ubah</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@section('js')
<script>
    $('.image').change(function() {
        let reader = new FileReader();

        reader.onload = (e) => {
            $('#preview-image-before-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
</script>
@endsection

@endsection
