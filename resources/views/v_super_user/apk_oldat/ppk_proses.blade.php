@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0">proses {{ $form }} barang </h1>
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
            @foreach($pengajuan as $dataPengajuan)
            <form class="form-pengajuan" action="{{ url('super-user/ppk/oldat/pengajuan/proses-'. $form .'/'. $dataPengajuan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" name="id_form_usulan" value="{{ $id }}">
                    <input type="hidden" name="pegawai_id" value="{{ $dataPengajuan->id_pegawai }}">
                    <span id="kode_otp"></span>
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12"><label class="text-muted">Informasi Pengusul</label></div>
                        <label class="col-sm-2 col-form-label">Nomor Surat </label>
                        <div class="col-sm-10">
                            @php
                            $totalUsulan = $total->total_form + 1;
                            $tahun = \Carbon\Carbon::now()->isoFormat('Y');
                            @endphp
                            @if ($total->jenis_form == 'pengadaan')
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.01/2/'.$totalUsulan.'/'.$tahun }}" readonly>
                            @else
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.04/2/'.$totalUsulan.'/'.$tahun }}" readonly>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Penyerahan </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control text-capitalize" name="tanggal_bast" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
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
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->jabatan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit Kerja </label>
                        <div class="col-sm-10">
                            <input type="hidden" name="unit_kerja_id" value="{{ $dataPengajuan->id_unit_kerja }}">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataPengajuan->unit_kerja }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Perolehan </label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control text-capitalize" name="tanggal_pengguna" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    @if($dataPengajuan->jenis_form == 'pengadaan')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pengguna </label>
                        <div class="col-sm-10">
                            <textarea type="date" name="rencana_pengguna" class="form-control text-capitalize" readonly>{{ $dataPengajuan->rencana_pengguna }}</textarea>
                        </div>
                    </div>
                    @endif

                    @if ($form == 'pengadaan')
                    @foreach($dataPengajuan->detailPengadaan as $i => $dataBarang)
                    <input type="hidden" name="id_barang[]" value="1{{ \Carbon\Carbon::now()->isoFormat('DDMMYY').rand(100,999) }}">
                    <input type="hidden" name="detail_usulan_id[]" value="{{ $dataBarang->id_form_usulan_pengadaan }}">
                    <div class="form-group row mt-4">
                        <div class="col-md-12"><label class="text-muted">Informasi Barang</label></div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="kode_barang[]" value="{{ $dataBarang->kategori_barang_id }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">NUP Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="nup_barang[]" placeholder="Nomor Urut Pemesanan (NUP)">
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
                            <input type="text" class="form-control text-capitalize" name="merk_tipe_barang[]" value="{{ $dataBarang->merk_barang }}" readonly>
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
                        <label class="col-sm-2 col-form-label">Tanggal Perolehan (*) </label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="tahun_perolehan[]" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Kontrak (*) </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="nomor_kontrak[]" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Nomor Kwitansi (*) </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="nomor_kwitansi[]" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Spesifikasi Barang </label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control" name="spesifikasi_barang[]"></textarea>
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
                            <input type="number" class="form-control" name="kode_barang[]" value="{{ $dataBarang->kode_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">NUP Barang </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="nup_barang[]" value="{{ $dataBarang->nup_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis Barang </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-capitalize" value="{{ $dataBarang->kategori_barang }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Merk Barang </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-capitalize" name="merk_tipe_barang[]" value="{{ $dataBarang->merk_tipe_barang }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pengguna</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="nama_pegawai[]" value="{{ $dataBarang->nama_pegawai }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Unit Kerja</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="unit_kerja[]" value="{{ $dataBarang->unit_kerja }}" readonly>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group row mt-4">
                        <label class="col-sm-12 text-muted">Bukti Pembayaran</label>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Total Biaya (*)</label>
                        <div class="col-sm-10">
                            <input type="number" name="total_biaya" class="form-control" placeholder="Nominal Biaya Perbaikan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Foto Kwitansi</label>
                        <div class="col-sm-10">
                            <p>
                                @if($dataPengajuan->lampiran == null)
                                <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/1611/1611318.png" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @else
                                <img id="preview-image-before-upload" src="{{ asset('gambar/kwitansi/oldat_perbaikan/'. $dataPengajuan->lampiran) }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @endif
                            </p>
                            <p>
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="hidden" class="form-control image" name="foto_lama" value="{{ $dataPengajuan->lampiran }}">
                                <input type="file" class="form-control image" name="foto_kwitansi" accept="image/jpeg , image/jpg, image/png" value="{{ $dataPengajuan->lampiran }}">
                            </div><br>
                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-footer">
                    <button type="reset" class="btn btn-default btn-md">BATAL</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary" onclick="return confirm('Apakah data sudah benar ?')">SUBMIT</button>
                </div>
            </form>
            @endforeach
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

    $(function() {
        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let jenisForm = "{{ $id }}"
            let tujuan = "verifikasi {{ $form }} barang"
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
            $('#kode_otp').append('<input type="hidden" class="form-control" name="kode_otp" value="' + resOTP + '">')
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
