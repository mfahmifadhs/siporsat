@php
    if (Auth::user()->level_id == '2') {
        $extend = 'v_admin_user.layout.app';
        $url    = 'admin-user/verif/usulan/cek';
    } elseif (Auth::user()->level_id == '3') {
        $extend = 'v_super_user.layout.app';
        $url     = 'super-user/verif/usulan/cek';
    } elseif (Auth::user()->level_id == 4) {
        $extend = 'v_user.layout.app';
        $url     = 'unit-kerja/verif/usulan/cek';
    }
@endphp

@extends($extend)

@section('content')

<!-- content-wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"></small></h1>
                </div>
            </div>
        </div>
    </div>

    <section class="container">
        <div class="row justify-content-center align-items-center " style="height: 70vh;">
            <div class="col-md-8 col-md-offset-2 text-center">
                <div class="panel panel-default">
                    <div class="panel-heading font-weight-bold">Verifikasi Dokumen</div>
                    <hr>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ url($url) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="modul" value="{{ Auth::user()->sess_modul }}">
                            <input type="hidden" name="form_id" value="{{ Auth::user()->sess_form_id }}">
                            <div class="form-group">
                                <p>Mohon untuk memasukan kode <strong>OTP</strong> yang diterima pada aplikasi <b>Google Authenticator</b>. <br>
                                    Pastikan, anda memasukan kode OTP terkini, karena kode OTP akan berubah setiap 30 detik.</p>

                                @if($errors->any())
                                    <b style="color: red">{{$errors->first()}}</b><br>
                                @endif
                                <label for="one_time_password" class="col-md-4 control-label">Masukkan Kode OTP</label>


                                <div class="col-md-12">
                                    <input id="one_time_password" type="number" class="form-control text-center" name="one_time_password" minLength="6" maxLength="6" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Verifikasi Dokumen ?')">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



    @endsection
