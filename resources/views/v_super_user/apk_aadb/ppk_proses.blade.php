@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Pengajuan AADB</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/aadb/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Usulan Pengajuan AADB</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if ($aksi == 1)

@foreach($pengajuan->usulanKendaraan as $dataPengajuan)
<section class="content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usulan Pengajuan Pengadaan Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/ppk/aadb/proses-usulan/pengadaan/'. $pengajuan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_form_usulan" value="{{ $pengajuan->id_form_usulan }}">
                    <input type="hidden" name="unit_kerja_id" value="{{ $pengajuan->id_unit_kerja }}">
                    <input type="hidden" name="id_kendaraan" value="1{{ \Carbon\Carbon::now()->isoFormat('DDMMYY').rand(100,999) }}">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor BAST</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'bast/aadb/pengadaan/'.$idBast.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Selesai</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_bast" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="jenis_aadb" value="{{ $dataPengajuan->jenis_aadb }}" readonly>
                        </div>
                    </div>
                    @if($dataPengajuan->jenis_aadb == 'sewa')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Mulai Sewa (*)</label>
                        <div class="col-sm-10">
                            <input type="month" class="form-control" name="mulai_sewa" placeholder="Tahun Mulai Sewa" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Penyedia (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="penyedia" placeholder="Perusahaan Penyedia Sewa Kendaraan" required>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-12 form-group text-muted">Informasi Kendaraan</label>
                        <label class="col-sm-2 col-form-label">Kode Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="kode_barang" value="{{ $dataPengajuan->id_jenis_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_kendaraan" readonly>
                                <option value="{{ $dataPengajuan->id_jenis_kendaraan }}">{{ $dataPengajuan->jenis_kendaraan }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_tipe_kendaraan" value="{{ $dataPengajuan->merk_tipe_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" value="{{ $dataPengajuan->tahun_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat Kendaraan (*)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-uppercase" name="no_plat_kendaraan" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="mb_stnk_plat_kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Plat RHS</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control text-uppercase" name="no_plat_rhs">
                        </div>
                        <label class="col-sm-2 col-form-label">Masa Berlaku STNK RHS</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" name="mb_stnk_plat_rhs">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Penggunaan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" readonly>{{ $pengajuan->rencana_pengguna }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-12 text-muted">Bukti Pembayaran</label>
                        <label class="col-sm-2 col-form-label">Biaya Pengadaan (*)</label>
                        <div class="col-sm-10">
                            <input type="number" name="total_biaya" class="form-control" placeholder="Nominal Biaya Pengadaan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                        <div class="col-sm-10">
                            <p>
                                @if($pengajuan->lampiran == null)
                                <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/1611/1611318.png" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @else
                                <img id="preview-image-before-upload" src="{{ asset('gambar/kwitansi/pengadaan/'. $pengajuan->lampiran) }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @endif
                            </p>
                            <p>
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="hidden" class="form-control image" name="foto_lama" value="{{ $pengajuan->lampiran }}">
                                <input type="file" class="form-control image" name="foto_kwitansi" accept="image/jpeg , image/jpg, image/png" value="{{ $pengajuan->lampiran }}">
                            </div><br>
                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan pengadaan kendaraan ?')">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endforeach

@elseif ($aksi == 2)

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
                <h3 class="card-title">Usulan Pengajuan Servis Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/ppk/aadb/proses-usulan/servis/'.$pengajuan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No. Surat BAST</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'bast/aadb/servis/'.$idBast.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Selesai Proses</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_bast" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    @foreach($pengajuan->usulanServis as $dataServis)
                    <div class="form-group row">
                        <label class="col-sm-12 text-muted">Informasi Kendaraan</label>
                        <label class="col-sm-3 col-form-label">Nama Kendaraan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->merk_tipe_kendaraan }}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">Nomor Plat</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->no_plat_kendaraan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->kilometer_terakhir }}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">Pengguna</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->pengguna }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" value="{{ \Carbon\Carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->jatuh_tempo_servis }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" value="{{ \Carbon\Carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" value="{{ $dataServis->jatuh_tempo_ganti_oli }}" readonly>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group row">
                        <label class="col-sm-12 text-muted">Bukti Pembayaran</label>
                        <label class="col-sm-3 col-form-label">Biaya Perbaikan (*)</label>
                        <div class="col-sm-9">
                            <input type="number" name="total_biaya" class="form-control" placeholder="Nominal Biaya Perbaikan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Bukti Pembayaran</label>
                        <div class="col-sm-9">
                            <p>
                                @if($pengajuan->lampiran == null)
                                <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/1611/1611318.png" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @else
                                <img id="preview-image-before-upload" src="{{ asset('gambar/kwitansi/servis/'. $pengajuan->lampiran) }}" class="img-responsive img-thumbnail mt-2" style="width: 10%;">
                                @endif
                            </p>
                            <p>
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Upload Foto
                                <input type="hidden" class="form-control image" name="foto_lama" value="{{ $pengajuan->lampiran }}">
                                <input type="file" class="form-control image" name="foto_kwitansi" accept="image/jpeg , image/jpg, image/png" value="{{ $pengajuan->lampiran }}">
                            </div><br>
                            <span class="help-block" style="font-size: 12px;">Format foto jpg/jpeg/png dan max 4 MB</span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@elseif ($aksi == 3)

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
                <h3 class="card-title">Usulan Pengajuan Perpanjangan STNK</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/ppk/aadb/proses-usulan/perpanjangan-stnk/'.$pengajuan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="3">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">No. Surat BAST</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'bast/aadb/perpanjanganstnk/'.$idBast.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Selesai Proses</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_bast" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jumlah Pengajuan</label>
                        <div class="col-sm-2">
                            <input type="number" name="total_pengajuan" id="jumlahKendaraan" class="form-control" value="{{ $pengajuan->total_pengajuan }}" readonly>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Informasi Kendaraan</label>
                        </div>
                    </div>
                    @foreach($pengajuan->usulanSTNK as $dataStnk)
                    <div id="section-kendaraan">
                        <div class="row form-group">
                            <label class="col-sm-3 col-form-label">Nama Kendaraan</label>
                            <div class="col-sm-3">
                                <input type="hidden" name="detail_usulan_id[]" value="{{  $dataStnk->id_form_usulan_perpanjangan_stnk }}">
                                <input type="text" class="form-control" value="{{ $dataStnk->merk_tipe_kendaraan }}" readonly>
                            </div>
                            <label class="col-sm-3 col-form-label">Masa Berlaku STNK</label>
                            <span id="mb-stnk1" class="col-sm-3"><input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($dataStnk->masa_berlaku_stnk)->isoFormat('Y-MM-DD') }}" readonly></span>
                        </div>
                        <div class="row form-group">
                            <label class="col-sm-3">Masa Berlaku STNK (*) <br> <small>Setelah Diperpanjang</small></label>
                            <span id="mb-stnk1" class="col-sm-3"><input type="date" name="mb_stnk_baru[]" class="form-control" required></span>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group row">
                        <label class="col-sm-12 text-muted">Bukti Pembayaran</label>
                        <label class="col-sm-3 col-form-label">Total Biaya Perpanjangan STNK (*)</label>
                        <div class="col-sm-9">
                            <input type="number" name="total_biaya" class="form-control" placeholder="Nominal Biaya Perpanjangan STNK" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Foto STNK</label>
                        <div class="col-sm-9">
                            <input type="hidden" class="form-control image" name="foto_lama" value="{{ $pengajuan->lampiran }}">
                            <input type="file" class="form-control image" name="foto_stnk" accept="application/pdf" value="{{ $pengajuan->lampiran }}">
                            <span class="help-block" style="font-size: 12px;">Jika lebih dari 1 kendaraan, foto STNK dijadikan 1 dalam file dengan format PDF.</span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>

@elseif ($aksi == 4)

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
                <h3 class="card-title">Usulan Pengajuan Pengadaan Voucher BBM </h3>
            </div>
            <div class="card-body">
            <form action="{{ url('super-user/ppk/aadb/proses-usulan/voucher-bbm/'.$pengajuan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ rand(1000,9999) }}">
                    <input type="hidden" name="jenis_form" value="4">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat BAST</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'bast/aadb/voucherbbm/'.$idBast.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Selesai Proses</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_bast" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Kendaraan</label>
                        <div class="col-sm-2">
                            <input type="number" class="form-control" value="{{ $pengajuan->total_pengajuan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-12">
                            <label class="text-muted">Informasi Kendaraan</label>
                        </div>
                    </div>
                    @foreach($pengajuan->usulanVoucher as $dataVoucher)
                    <div id="section-kendaraan" class="mb-4">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Kendaraan</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="detail_usulan_id[]" value="{{  $dataVoucher->id_form_usulan_voucher_bbm }}">
                                <input type="text" class="form-control" value="{{ $dataVoucher->merk_tipe_kendaraan }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}" readonly>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">&nbsp;</label>
                        <div class="col-sm-10">
                            <button class="btn btn-primary" id="btnSubmit" onclick="return confirm('Buat pengajuan servis kendaraan ?')">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endif

@section('js')
<script>
    $('.image').change(function() {
        let reader = new FileReader();

        reader.onload = (e) => {
            $('#preview-image-before-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
    // Kode OTP
    $(function() {
        let j = 1;
        let id = "{{ $aksi }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "Proses Usulan"
            jQuery.ajax({
                url: '/super-user/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    alert('Berhasil mengirim kode OTP')
                    resOTP = res
                }
            });
        });
        $(document).on('click', '#btnCheckOTP', function() {
            let inputOTP = $('#inputOTP').val()
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
    });
</script>
@endsection

@endsection
