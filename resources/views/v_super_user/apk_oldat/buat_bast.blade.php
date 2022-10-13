@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Berita Acara Serah Terima (BAST)</h1>
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
    <div class="container">
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold mt-2 text-capitalize">
                    berita acara serah terima {{ $cekSurat->jenis_form }} barang
                </h3>
            </div>
            @foreach($pengajuan as $dataPengajuan)
            <form class="form-pengajuan" action="{{ url('super-user/oldat/surat/proses-bast/'. $id ) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" name="id_form_usulan" value="{{ $id }}">
                    <input type="hidden" name="pegawai_id" value="{{ $dataPengajuan->id_pegawai }}">
                    <span id="kode_otp"></span>
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12"><label class="text-muted">Informasi Pengusul</label></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $dataPengajuan->nama_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jabatan Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->jabatan.' '.$dataPengajuan->tim_kerja }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit Kerja </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->unit_kerja }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Perolehan </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control text-capitalize" name="tanggal_pengguna" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pengguna </label>
                        <div class="col-sm-10">
                            <textarea type="date" name="rencana_pengguna" class="form-control text-capitalize">{{ $dataPengajuan->rencana_pengguna }}</textarea>
                        </div>
                    </div>

                    @if ($cekSurat->jenis_form == 'pengadaan')
                    @foreach($dataPengajuan->detailPengadaan as $i => $dataBarang)
                    <input type="hidden" name="id_barang[]" value="{{ $dataBarang->id_form_usulan_pengadaan }}">
                    <div class="form-group row mt-4">
                        <div class="col-md-12"><label class="text-muted">Informasi Barang</label></div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="kode_barang[]">
                        </div>
                        <label class="col-sm-2 col-form-label">NUP Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="nup_barang[]">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis Barang </label>
                        <div class="col-sm-4">
                            <input type="hidden" name="kategori_barang_id[]" value="{{ $dataBarang->id_kategori_barang }}">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataBarang->kategori_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Merk Barang </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-capitalize" name="merk_barang[]" value="{{ $dataBarang->merk_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jumlah_barang[]" value="{{ $dataBarang->jumlah_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Satuan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="satuan_barang[]" value="{{ $dataBarang->satuan_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nilai Perolehan (*) </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="nilai_perolehan[]" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Tahun Perolehan (*) </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="tahun_perolehan[]" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Spesifikasi Barang </label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="spesifikasi_barang[]">{{ $dataBarang->spesifikasi_barang }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Foto Barang </label>
                        <div class="col-sm-10">
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="file" class="form-control image" accept=".png, .jpg, .jpeg" name="foto_barang[]" data-idtarget={{ $i }} required>
                                <img id="preview-image-before-upload{{$i}}" src="{{ asset('gambar/barang_bmn/') }}" style="max-height: 80px;">
                            </div><br>
                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                        </div>
                    </div>
                    @endforeach
                    @else
                    @foreach($dataPengajuan->detailPerbaikan as $i => $dataBarang)
                    <input type="hidden" name="id_barang[]" value="{{ $dataBarang->id_barang }}">
                    <div class="form-group row mt-4">
                        <div class="col-md-12"><label class="text-muted">Informasi Barang</label></div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" value="{{ $dataBarang->kode_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">NUP Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" value="{{ $dataBarang->nup_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis Barang </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataBarang->kategori_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Merk Barang </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-capitalize" name="merk_barang[]" value="{{ $dataBarang->merk_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jumlah_barang[]" value="{{ $dataBarang->jumlah_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Satuan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="satuan_barang[]" value="{{ $dataBarang->satuan_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nilai Perolehan (*) </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="nilai_perolehan[]" value="Rp {{ number_format($dataBarang->nilai_perolehan, 0, ',', '.') }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Tahun Perolehan (*) </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="tahun_perolehan[]" value="{{ $dataBarang->tahun_perolehan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Spesifikasi Barang </label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="spesifikasi_barang[]" readonly>{{ $dataBarang->spesifikasi_barang }}</textarea>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <label>Apakah semua barang telah diterima dengan baik ?</label><br>
                            <input type="radio" name="konfirmasi" value="1"> Ya
                            <input type="radio" name="konfirmasi" value="1"> Tidak
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="text-muted col-md-12">Verifikasi</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Verifikasi BAST</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP" placeholder="Masukan Kode OTP" required>
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
                        </div>
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
                url: '/super-user/sendOTP?jenisForm=' + jenisForm,
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
