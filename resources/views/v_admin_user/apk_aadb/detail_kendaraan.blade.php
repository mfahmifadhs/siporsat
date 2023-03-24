@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Alat Angkutan Darat Bermotor (AADB)</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/aadb/kendaraan/daftar/*') }}">Daftar Aadb</a></li>
                    <li class="breadcrumb-item active">Detail Aadb</li>
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
                <a href="{{ url('admin-user/aadb/kendaraan/daftar/*') }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile text-capitalize">
                        <div class="row">
                            <div class="col-md-12">
                                @if($kendaraan->foto_kendaraan == null)
                                <img src="https://cdn-icons-png.flaticon.com/512/3202/3202926.png" class="img-thumbnail mt-2" style="width: 100%;">
                                @else
                                <img src="{{ asset('gambar/kendaraan/'. $kendaraan->foto_kendaraan) }}" class="img-thumbnail mt-2" style="width: 100%;">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <h3 class="profile-username text-center">{{ $kendaraan->jenis_kendaraan }}</h3>
                            </div>
                            <div class="col-md-12">
                                <h6 class="text-muted text-center">{{ $kendaraan->merk_tipe_kendaraan }}</h6>
                            </div>
                            <div class="col-md-12 text-center">
                                <a class="btn btn-primary btn-sm " data-toggle="modal" data-target="#modal-foto">
                                    <i class="fas fa-edit"></i> Upload Foto
                                </a>
                            </div>
                            <div class="modal fade" id="modal-foto">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Foto Kendaraan</h4>
                                        </div>
                                        <form action="{{ url('admin-user/aadb/kendaraan/proses-ubah/'. $kendaraan->id_kendaraan) }}" method="POST" enctype="multipart/form-data">

                                            @csrf
                                            <div class="modal-body text-center">
                                                <input type="hidden" name="update" value="foto">
                                                <p>
                                                    @if($kendaraan->foto_kendaraan == null)
                                                    <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/3202/3202926.png" class="img-thumbnail mt-2" style="width: 50%;">
                                                    @else
                                                    <img id="preview-image-before-upload" src="{{ asset('gambar/kendaraan/'. $kendaraan->foto_kendaraan) }}" class="img-thumbnail mt-2" style="width: 50%;">
                                                    @endif
                                                </p>
                                                <div class="btn btn-default btn-file btn-sm">
                                                    <i class="fas fa-paperclip"></i> Upload Foto
                                                    <input type="hidden" class="form-control image" name="foto_lama" value="{{ $kendaraan->foto_kendaraan }}">
                                                    <input type="file" class="form-control image" name="foto_kendaraan" accept="image/jpeg , image/jpg, image/png" value="{{ $kendaraan->foto_kendaraan }}">
                                                </div><br>
                                                <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Upload foto kendaraan ?')">
                                                    <i class="fas fa-save"></i> Simpan
                                                </button>
                                                <button name="hapus" value="1" class="btn btn-danger btn-sm" onclick="return confirm('Ingin menghapus foto ?')">
                                                    <i class="fas fa-trash"></i> Hapus Foto
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card card-primary card-outline">
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
                                    <input type="hidden" name="foto_kendaraan" value="{{ $kendaraan->foto_kendaraan }}">
                                    <div class="row">
                                        <div class="col-md-12 border-bottom mb-3">
                                            <label class="text-muted">Informasi Pengguna & Pengemudi</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pengguna :</label>
                                            <input type="text" class="form-control" name="pengguna" value="{{ $kendaraan->pengguna }}" placeholder="Masukkan Nama Pengguna">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Jabatan :</label>
                                            <input type="text" class="form-control" name="jabatan" value="{{ $kendaraan->jabatan }}" placeholder="Masukkan Nama Pengguna">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pengemudi :</label>
                                            <input type="text" class="form-control" name="pengemudi" value="{{ $kendaraan->pengemudi }}" placeholder="Masukkan Nama Pengguna">
                                        </div>
                                        <div class="col-md-12 border-bottom mb-3">
                                            <label class="text-muted">Informasi Kendaraan</label>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Unit Kerja :</label>
                                            <select name="unit_kerja_id" class="form-control">
                                                <option value="{{ Auth::user()->pegawai->unit_kerja_id }}">{{ Auth::user()->pegawai->unitKerja->unit_kerja }}</option>
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
                                            @if ($kendaraan->mb_stnk_plat_kendaraan != null)
                                            <input type="date" name="mb_stnk_plat_kendaraan" class="form-control" value="{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_kendaraan)->isoFormat('Y-MM-DD') }}">
                                            @else
                                            <input type="date" name="mb_stnk_plat_kendaraan" class="form-control">
                                            @endif
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>No. Plat RHS: </label>
                                            <input type="text" name="no_plat_rhs" class="form-control" value="{{ $kendaraan->no_plat_rhs }}">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Masa Berlaku STNK RHS: </label>
                                            @if ($kendaraan->mb_stnk_plat_kendaraan != null)
                                            <input type="date" name="mb_stnk_plat_rhs" class="form-control" value="{{ \Carbon\Carbon::parse($kendaraan->mb_stnk_plat_rhs)->isoFormat('Y-MM-DD') }}">
                                            @else
                                            <input type="date" name="mb_stnk_plat_rhs" class="form-control">
                                            @endif
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
                                        <div class="col-md-6 form-group">
                                            <label>Kualifikasi Kendaraan : </label>
                                            <select name="kualifikasi" class="form-control" required>
                                                @if ($kendaraan->kualifikasi != null)
                                                @if ($kendaraan->kualifikasi == 'jabatan')
                                                <option value="jabatan">Kendaraan Jabatan</option>
                                                <option value="operasional">Kendaraan Operasional</option>
                                                <option value="bermotor">Kendaraan Bermotor</option>
                                                @elseif ($kendaraan->kualifikasi == 'operasional')
                                                <option value="operasional">Kendaraan Operasional</option>
                                                <option value="jabatan">Kendaraan Jabatan</option>
                                                <option value="bermotor">Kendaraan Bermotor</option>
                                                @else
                                                <option value="bermotor">Kendaraan Bermotor</option>
                                                <option value="jabatan">Kendaraan Jabatan</option>
                                                <option value="operasional">Kendaraan Operasional</option>
                                                @endif
                                                @else
                                                <option value="">-- Pilih Kualifikasi --</option>
                                                <option value="jabatan">Kendaraan Jabatan</option>
                                                <option value="operasional">Kendaraan Operasional</option>
                                                <option value="bermotor">Kendaraan Bermotor</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="mb-3">Status Kendaraan : </label> <br>
                                            <input type="radio" name="status_kendaraan_id" value="1" {{ $kendaraan->status_kendaraan_id == 1 ? 'checked' : '' }}>
                                            <span class="mr-3">Aktif </span>
                                            <input type="radio" name="status_kendaraan_id" value="2" {{ $kendaraan->status_kendaraan_id == 2 ? 'checked' : '' }}>
                                            <span class="mr-3">Perbaikan </span>
                                            <input type="radio" name="status_kendaraan_id" value="3" {{ $kendaraan->status_kendaraan_id == 3 ? 'checked' : '' }}>
                                            <span class="mr-3">Proses Penghapusan </span>
                                            <input type="radio" name="status_kendaraan_id" value="4" {{ $kendaraan->status_kendaraan_id == 4 ? 'checked' : '' }}>
                                            <span class="mr-3">Sudah Dihapuskan </span>
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
                                            <input type="radio" name="proses" value="pengemudi-baru" required>
                                            <span>Pengemudi Baru </span>
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
                                            <span class="time mt-2">
                                                @if ($riwayatPengguna->status_pengguna == 1)
                                                <span class="badge badge-lg badge-pill badge-success" style="font-size: 1.5vh;">Aktif</span>
                                                @else
                                                <span class="badge badge-lg badge-pill badge-danger" style="font-size: 1.5vh;">Tidak Aktif</span>
                                                @endif
                                            </span>

                                            <h3 class="timeline-header text-capitalize">
                                                <a href="#">{{ $riwayatPengguna->pengguna }}</a> <br> {{ $riwayatPengguna->jabatan.' '.$riwayatPengguna->keterangan_pegawai }}
                                            </h3>

                                            <div class="timeline-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label>Pengguna : </label>
                                                        <p>{{ $riwayatPengguna->pengguna }}</p>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label>Jabatan : </label>
                                                        <p>{{ $riwayatPengguna->jabatan }}</p>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label>Pengemudi : </label>
                                                        <p>{{ $riwayatPengguna->pengemudi }}</p>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-{{ $riwayatPengguna->id_riwayat_kendaraan }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <a class="btn btn-danger btn-xs" href="{{ url('admin-user/aadb/kendaraan/hapus-riwayat/'. $riwayatPengguna->id_riwayat_kendaraan) }}" onclick="return confirm('Hapus Riwayat Ini ?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modal-{{ $riwayatPengguna->id_riwayat_kendaraan }}">
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
                                                            <input type="hidden" name="kendaraan_id" value="{{ $riwayatPengguna->kendaraan_id }}">
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Tanggal Pemakaian</label>
                                                                <div class="col-sm-8">
                                                                    <input type="date" class="form-control" name="tanggal_pengguna" value="{{ $riwayatPengguna->tanggal_pengguna }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Pengguna</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="pengguna" value="{{ $riwayatPengguna->pengguna }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Jabatan</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="jabatan" value="{{ $riwayatPengguna->jabatan }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Pengemudi</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" name="pengemudi" value="{{ $riwayatPengguna->pengemudi }}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">&nbsp;</label>
                                                                <div class="col-sm-8">
                                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Ubah informasi riwayat pemakaian ?')">
                                                                        <i class="fas fa-save"></i> Ubah
                                                                    </button>
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
