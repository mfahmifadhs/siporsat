@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Berita Acara</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/oldat/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/oldat/pengajuan/daftar/seluruh-pengajuan') }}">Daftar Pengajuan</a></li>
                    <li class="breadcrumb-item active">Buat Berita Acara</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title font-weight-bold mt-2">Berita Acara Serah Terima Barang</h3>
                <div class="card-tools">
                    <a href="{{ url('super-user/tim-kerja/download/data') }}" class="btn btn-primary" title="Download File" onclick="return confirm('Download data Kategori Barang ?')">
                        <i class="fas fa-file-download"></i>
                    </a>
                </div>
            </div>
            @foreach($pengajuan as $dataPengajuan)
            <form class="form-pengajuan" action="{{ url('super-user/oldat/surat/proses-bast/'. $id ) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" name="id_form_usulan" value="{{ $id }}">
                    <input type="hidden" name="pegawai_id" value="{{ $dataPengajuan->id_pegawai }}">
                    <span id="kode_otp"></span>
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nama Pengusul :</label>
                            <input type="text" class="form-control" value="{{ $dataPengajuan->nama_pegawai }}" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Jabatan Pengusul :</label>
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->jabatan.' '.$dataPengajuan->tim_kerja }}" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Unit Kerja :</label>
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->unit_kerja }}" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Tanggal Perolehan :</label>
                            <input type="date" class="form-control text-capitalize" name="tanggal_pengguna" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Rencana Pengguna :</label>
                            <textarea type="date" name="rencana_pengguna" class="form-control text-capitalize">{{ $dataPengajuan->rencana_pengguna }}</textarea>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Informasi Barang Pengguna :</label>
                            @if ($cekSurat->jenis_form == 'pengadaan')
                            <table id="table-barang" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Barang</th>
                                        <th>NUP</th>
                                        <th>Jenis Barang</th>
                                        <th>Merk</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Nilai Perolehan</th>
                                        <th>Tahun Perolehan</th>
                                        <th>Foto Barang (*)</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody id="input-barang-pengadaan" class="bg-grey">
                                    @foreach($dataPengajuan->detailPengadaan as $i => $dataBarang);
                                    <tr>
                                        <td class="text-center">
                                            <input type="hidden" name="id_barang[]" value="{{ $dataBarang->id_form_usulan_pengadaan }}">
                                            {{ $no++ }}
                                        </td>
                                        <td><input type="number" class="form-control" name="kode_barang[]" required></td>
                                        <td><input type="number" class="form-control" name="nup_barang[]" required></td>
                                        <td><input type="hidden" class="form-control" name="kategori_barang_id[]" value="{{ $dataBarang->id_kategori_barang }}">
                                            {{ $dataBarang->kategori_barang }}
                                        </td>
                                        <td><input type="text" class="form-control" name="merk_barang[]" value="{{ $dataBarang->merk_barang }}" readonly></td>
                                        <td><textarea type="text" class="form-control" name="spesifikasi_barang[]" readonly>{{ $dataBarang->spesifikasi_barang }}</textarea></td>
                                        <td><input type="text" class="form-control" name="jumlah_barang[]" value="{{ $dataBarang->jumlah_barang }}" readonly></td>
                                        <td><input type="text" class="form-control" name="satuan_barang[]" value="{{ $dataBarang->satuan_barang }}" readonly></td>
                                        <td><input type="number" class="form-control" name="nilai_perolehan[]" required></td>
                                        <td><input type="number" class="form-control" name="tahun_perolehan[]" required></td>
                                        <td class="text-center">
                                            <div class="btn btn-default btn-file">
                                                <i class="fas fa-paperclip"></i> Upload Foto
                                                <input type="file" class="form-control image" accept=".png, .jpg, .jpeg" name="foto_barang[]" data-idtarget={{ $i }} required>
                                                <img id="preview-image-before-upload{{$i}}" src="{{ asset('gambar/barang_bmn/') }}" style="max-height: 80px;">
                                            </div><br>
                                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <table id="table-barang" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Kode Barang</th>
                                        <th>NUP</th>
                                        <th>Jenis Barang</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Satuan</th>
                                        <th>Nilai Perolehan</th>
                                        <th>Tahun Perolehan</th>
                                        <th>Foto Barang</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody id="input-barang-pengadaan" class="bg-grey">
                                    @foreach($dataPengajuan->detailPerbaikan as $i => $dataBarang)
                                    <tr>
                                        <td class="text-center">
                                            <input type="hidden" name="id_barang[]" value="{{ $dataBarang->id_barang }}">
                                            {{ $no++ }}
                                        </td>
                                        <td><input type="number" class="form-control" value="{{ $dataBarang->kode_barang }}" readonly></td>
                                        <td><input type="text" class="form-control" value="{{ $dataBarang->nup_barang }}" readonly></td>
                                        <td><input type="text" class="form-control" value="{{ $dataBarang->kategori_barang }}" readonly></td>
                                        <td><textarea type="text" class="form-control" readonly>{{ $dataBarang->spesifikasi_barang }}</textarea></td>
                                        <td><input type="text" class="form-control" value="{{ $dataBarang->jumlah_barang }}" readonly></td>
                                        <td><input type="text" class="form-control" value="{{ $dataBarang->satuan_barang }}" readonly></td>
                                        <td><input type="number" class="form-control" value="{{ $dataBarang->nilai_perolehan }}" readonly></td>
                                        <td><input type="number" class="form-control" value="{{ $dataBarang->tahun_perolehan }}" readonly></td>
                                        <td class="text-center">
                                            <img src="{{ asset('gambar/barang_bmn/'. $dataBarang->foto_barang) }}" style="max-height: 80px;">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Apakah semua barang telah diterima dengan baik ?</label>
                        <p>
                            <input type="radio" name="konfirmasi" value="1"> Ya
                            <input type="radio" name="konfirmasi" value="1"> Tidak
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label>Verifikasi Dokumen :</label>
                        <input type="text" class="form-control col-md-3" id="inputOTP" placeholder="Masukan Kode OTP" required>
                        <a class="btn btn-default btn-sm mt-2" id="btnKirimOTP">Kirim Kode OTP</a>
                        <a class="btn btn-primary btn-sm mt-2" id="btnCheckOTP">Check Kode OTP</a>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="reset" class="btn btn-default btn-md">BATAL</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary" onclick="return confirm('Apakah data sudah benar ?')" disabled>SUBMIT</button>
                </div>
            </form>
            @endforeach
        </div>
    </div>
</section>

@section('js')
<script>
    $(function() {
        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let jenisForm = "{{ $id }}"
            let tujuan = "{{ $tujuan }}"
            jQuery.ajax({
                url: '/super-user/oldat/sendOTP?jenisForm=' + jenisForm,
                data: {
                    "tujuan": tujuan
                },
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasi mengirim kode OTP')
                    resOTP = res
                }
            });
        });
        $(document).on('click', '#btnCheckOTP', function() {
            let inputOTP = $('#inputOTP').val()
            console.log(inputOTP)
            if (inputOTP == '') {
                alert('Mohon isi kode OTP yang diterima')
            } else if (inputOTP == resOTP) {
                $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
                alert('Kode OTP Benar')
                $('#btnSubmit').prop('disabled', false)
            } else {
                alert('Kode OTP Salah')
                $('#btnSubmit').prop('disabled', true)
            }
        })

        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "paging": false,
            "info": false,
            "sort": false,
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
    });
    $('.image').change(function() {
        let idtarget = $(this).data('idtarget');
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-before-upload' + idtarget).attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);

    });
</script>
@endsection

@endsection
