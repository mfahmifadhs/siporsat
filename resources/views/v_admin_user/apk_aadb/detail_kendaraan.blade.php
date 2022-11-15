@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/aadb/barang/data/semua') }}">Master AADB</a></li>
                    <li class="breadcrumb-item active">Detail</li>
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
                            @if($kendaraan->foto_kendaraan == null)
                            <img src="https://cdn-icons-png.flaticon.com/512/3202/3202926.png" class="img-thumbnail mt-2" style="width: 100%;">
                            @else
                            <img src="{{ asset('gambar/kendaraan/'. $kendaraan->foto_kendaraan) }}" class="img-thumbnail mt-2" style="width: 100%;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $kendaraan->kategori_barang }}</h3>
                        <p class="text-muted text-center">{{ $kendaraan->spesifikasi_barang }}</p>
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
                                <form action="{{ url('admin-user/aadb/kendaraan/proses-ubah/'. $kendaraan->id_kendaraan) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="form_usulan_id" value="{{ $kendaraan->form_usulan_id }}">
                                    <input type="hidden" name="jenis_aadb" value="{{ $kendaraan->jenis_aadb }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Upload Foto Kendaraan</label>
                                            <p>
                                                @if($kendaraan->foto_kendaraan == null)
                                                <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/3202/3202926.png" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                                @else
                                                <img id="preview-image-before-upload" src="{{ asset('gambar/kendaraan/'. $kendaraan->foto_kendaraan) }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                                @endif
                                            </p>
                                            <p>
                                            <div class="btn btn-default btn-file">
                                                <i class="fas fa-paperclip"></i> Upload Foto
                                                <input type="hidden" class="form-control image" name="foto_lama" value="{{ $kendaraan->foto_kendaraan }}">
                                                <input type="file" class="form-control image" name="foto_kendaraan" accept="image/jpeg , image/jpg, image/png" value="{{ $kendaraan->foto_kendaraan }}">
                                            </div><br>
                                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                                            </p>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pengguna :</label>
                                            <input type="text" class="form-control" name="pengguna" value="{{ $kendaraan->pengguna }}" placeholder="Masukkan Nama Pengguna">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Unit Kerja :</label>
                                            <select name="unit_kerja_id" class="form-control">
                                                <option value="">-- Pilih Unit Kerja --</option>
                                                @foreach($unitKerja as $dataUnitKerja)
                                                <option value="{{ $dataUnitKerja->id_unit_kerja }}" <?php if ($kendaraan->unit_kerja_id == $dataUnitKerja->id_unit_kerja) echo "selected"; ?>>
                                                    {{ $dataUnitKerja->unit_kerja }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Kode Barang : </label>
                                            <input type="text" name="kode_barang" class="form-control" value="{{ $kendaraan->kode_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>NUP : </label>
                                            <input type="text" name="nup_barang" class="form-control" value="{{ $kendaraan->nup_barang }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="level">Nama Kendaraan :</label>
                                            <select name="id_jenis_kendaraan" class="form-control">
                                                <option value="">-- Pilih Jenis Kendaraan --</option>
                                                @foreach($jenis as $dataJenis)
                                                <option value="{{ $dataJenis->id_jenis_kendaraan }}" <?php if ($kendaraan->jenis_kendaraan_id == $dataJenis->id_jenis_kendaraan) echo "selected"; ?>>
                                                    {{ $dataJenis->jenis_kendaraan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Merk/Tipe : </label>
                                            <input type="text" name="merk_tipe_kendaraan" class="form-control" value="{{ $kendaraan->merk_tipe_kendaraan }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>No. Plat : </label>
                                            <input type="text" name="no_plat_kendaraan" class="form-control" value="{{ $kendaraan->no_plat_kendaraan }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Masa Berlaku STNK : </label>
                                            <input type="date" name="mb_stnk_plat_kendaraan" class="form-control" value="{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_kendaraan)->isoFormat('Y-MM-DD') }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>No. Plat RHS: </label>
                                            <input type="text" name="no_plat_rhs" class="form-control" value="{{ $kendaraan->no_plat_rhs }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Masa Berlaku STNK RHS: </label>
                                            <input type="date" name="mb_stnk_plat_kendaraan" class="form-control" value="{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_rhs)->isoFormat('Y-MM-DD') }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Nomor BPKB </label>
                                            <input type="text" name="no_bpkb" class="form-control" value="{{ $kendaraan->no_bpkb }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Nomor Rangka </label>
                                            <input type="text" name="no_rangka" class="form-control" value="{{ $kendaraan->no_rangka }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Nomor Mesin </label>
                                            <input type="text" name="no_mesin" class="form-control" value="{{ $kendaraan->no_mesin }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Tahun Kendaraan </label>
                                            <input type="text" name="tahun_kendaraan" class="form-control" value="{{ $kendaraan->tahun_kendaraan }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="level">Kondisi Barang :</label>
                                            <select name="id_kondisi_kendaraan" class="form-control">
                                                <option value="">-- Pilih Kondisi Barang --</option>
                                                @foreach($kondisi as $dataKondisi)
                                                <option value="{{ $dataKondisi->id_kondisi_kendaraan }}" <?php if ($kendaraan->kondisi_kendaraan_id == $dataKondisi->id_kondisi_kendaraan) echo "selected"; ?>>
                                                    {{ $dataKondisi->kondisi_kendaraan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Nilai Perolehan : </label>
                                            <input type="number" name="nilai_perolehan" class="form-control" value="{{ $kendaraan->nilai_perolehan }}">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>Keterangan : </label>
                                            <textarea type="text" name="keterangan_aadb" class="form-control" rows="5">{{ $kendaraan->keterangan_aadb}}</textarea>
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
                                @foreach($kendaraan->riwayat as $i => $riwayatPengguna)
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
                                                <a href="#">{{ $riwayatPengguna->unit_kerja }}</a> <br> {{ $riwayatPengguna->jabatan.' '.$riwayatPengguna->keterangan_pegawai }}
                                            </h3>

                                            <div class="timeline-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label>Pengguna : </label>
                                                        <p>{{ $riwayatPengguna->pengguna }}</p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-default">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-xs" href="{{ url('admin-user/aadb/barang/hapus-riwayat/'. $riwayatPengguna->id_riwayat_barang) }}" onclick="return confirm('Hapus Riwayat Ini ?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
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
                                                        <form action="{{ url('admin-user/aadb/kendaraan/ubah-riwayat/'. $riwayatPengguna->kendaraan_id) }}">
                                                            @csrf
                                                            <input type="hidden" name="id_riwayat_kendaraan" value="{{ $riwayatPengguna->id_riwayat_kendaraan }}">
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Pengguna</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="pengguna" value="{{ $riwayatPengguna->pengguna }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Tanggal Pemakaian</label>
                                                                <div class="col-sm-8">
                                                                    <input type="date" class="form-control" name="tanggal_pengguna" value="{{ $riwayatPengguna->tanggal_pengguna }}">
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
