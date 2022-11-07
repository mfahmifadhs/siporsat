@extends('v_user.layout.app')

@section('content')

<section class="content-header">
    <div class="container">
        <div class="panel-heading font-weight-bold">Profil</div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    @if ($user->status_google2fa == 0)
                    <div class="card-header">
                        <p>Silahkan scan barcode di bawah ini dengan aplikasi <b>Google Authenticator</b>. <br>
                            Mohon klik submit setelah anda selesai scan barcode.</p>
                    </div>
                    <div class="card-body">
                        <div>
                            {!! $QR_Image !!}
                        </div>
                    </div>
                    @endif
                    <div class="card-footer">
                        @if ($user->status_google2fa == 1)
                        <a href="{{ url('unit-kerja/profil/reset-autentikasi/'. $user->id) }}" class="btn btn-danger" onclick="return confirm('Apakah anda ingin mereset ulang 2fa autentikasi ?')">
                            <i class="fas fa-sync"></i> Reset Autentikasi
                        </a>
                        @else
                        <form action="{{ url('unit-kerja/profil/regist-user/'. Auth::user()->id ) }}" method="POST">
                            @csrf
                            <input type="hidden" name="secretkey" value="{{ $secretkey }}">
                            <button type="submit" class="btn btn-primary btn-sm">SUBMIT</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="col-md-12 form-group">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                        </div>
                        @endif
                        @if ($message = Session::get('failed'))
                        <div class="alert alert-danger">
                            <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="card-header">
                        <div class="form-group row">
                            <div class="col-sm-2 text-center">
                                <i class="fas fa-user-circle fa-5x bg-pri"></i>
                            </div>
                            <div class="col-sm-8 mt-3 text-capitalize">
                                <span class="font-weight-bold">{{ $user->nama_pegawai }}</span> <br>
                                <small>{{ {{ ucfirst(strtolower($user->unit_kerja))  }}</small>
                            </div>
                            <div class="col-sm-2 text-right">
                                <!-- <a href="{{ url('super-user/profil/ubah/'. $user->id) }}" class="btn btn-warning btn-sm mt-4">
                                    <i class="fas fa-edit"></i> Ubah Profil
                                </a> -->
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-capitalize">
                        <label>Nama Pegawai</label>
                        <div class="card-tools">
                            {{ ucfirst(strtolower($user->nama_pegawai)) }}
                        </div>
                    </div>
                    <div class="card-header text-capitalize">
                        <label>Jabatan</label>
                        <div class="card-tools">
                            @if ($user->tim_kerja_id != null)
                            {{ ucfirst(strtolower($user->keterangan_pegawai.' '.$user->tim_kerja)) }}
                            @else
                            {{ ucfirst(strtolower($user->keterangan_pegawai)) }}
                            @endif
                        </div>
                    </div>
                    <div class="card-header text-capitalize">
                        <label>Unit Kerja</label>
                        <div class="card-tools">
                            {{ ucfirst(strtolower($user->unit_kerja)) }}
                        </div>
                    </div>
                    <div class="card-header">
                        <label>Username</label>
                        <div class="card-tools">
                            {{ $user->username }}
                        </div>
                    </div>
                    <div class="card-header text-capitalize">
                        <label>Role</label>
                        <div class="card-tools">
                            {{ ucfirst(strtolower($user->level)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
