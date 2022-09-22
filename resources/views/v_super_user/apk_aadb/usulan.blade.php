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

@if ($aksi == 'pengadaan')

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
                <h3 class="card-title">Usulan Pengajuan Pengadaan Kendaraan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/aadb/usulan/proses/pengadaan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan_pengadaan" value="{{ rand(1000,9999) }}">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis AADB</label>
                        <div class="col-sm-10">
                            <select name="jenis_aadb" class="form-control" required>
                                <option value="bmn">BMN</option>
                                <option value="sewa">Sewa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis </label>
                        <div class="col-sm-10">
                            <select name="jenis_kendaraan" class="form-control" required>
                                @foreach($jenisKendaraan as $dataJenisKendaraan)
                                <option value="{{ $dataJenisKendaraan->id_jenis_kendaraan }}">{{ $dataJenisKendaraan->jenis_kendaraan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Merk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="merk_kendaraan" placeholder="Contoh : Toyota, Honda" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tipe</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tipe_kendaraan" placeholder="Contoh: CRV, HRV" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun_kendaraan" placeholder="Tahun Kendaraan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Penggunaan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="rencana_pengguna" placeholder="Contoh: Untuk Operasional" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Verifikasi Kode OTP</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" name="kode_otp_usulan" id="inputOTP"  placeholder="Masukan Kode OTP" required>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-success btn-xs mt-2" id="btnCheckOTP">Cek OTP</a>
                            <a class="btn btn-primary btn-xs mt-2" id="btnKirimOTP">Kirim OTP</a>
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

@elseif ($aksi == 'servis')

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
                <form action="" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Pengajuan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Pilih Kendaraan</label>
                        <div class="col-sm-9">
                            <select name="jenis_aadb" class="form-control">
                                <option value="">Honda CRV 2011</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Kilometer Kendaraan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tanggal_usulan" placeholder="Kilometer Kendaraan Terakhir">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Servis</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Servis (KM)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tanggal_usulan" placeholder="Contoh: 50000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal Terakhir Ganti Oli</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Jatuh Tempo Ganti Oli (KM)</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="tanggal_usulan" placeholder="Contoh: 50000">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">&nbsp;</label>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" onclick="return confirm('Buat pengajuan pengadaan kendaraan ?')">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@elseif ($aksi == 'perpanjangan-stnk')


@elseif ($aksi == 'voucher-bbm')


@endif

@section('js')
<script>
    $(function() {
        let j = 1;
        let id = "{{ $aksi }}"

        let resOTP = ''
        $(document).on('click', '#btnKirimOTP', function() {
            let tujuan = "{{ $aksi }}"
            jQuery.ajax({
                url: '/super-user/oldat/sendOTP?tujuan=' + tujuan,
                type: "GET",
                success: function(res) {
                    // console.log(res)
                    alert('Berhasil mengirim kode OTP')
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
