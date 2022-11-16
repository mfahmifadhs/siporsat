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
                            <div class="col-sm-7 mt-3 text-capitalize">
                                <span class="font-weight-bold">{{ $user->nama_pegawai }}</span> <br>
                                <small>{{ ucfirst(strtolower($user->unit_kerja))  }}</small>
                            </div>
                            <div class="col-sm-3 text-right">
                                <a class="btn btn-warning btn-xs mt-3 font-weight-bold" data-toggle="modal" data-target="#editProfile">
                                    <i class="fas fa-edit"></i> Ubah Profil
                                </a>
                                <a class="btn btn-warning btn-xs mt-2 font-weight-bold" data-toggle="modal" data-target="#editPassword">
                                    <i class="fas fa-lock"></i> Ubah Password
                                </a>
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
                    <div id="modal">
                        <!-- Modal Edit Profil-->
                        <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="editProfile" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="{{ url('unit-kerja/profil/edit-profil/'. Auth::user()->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_pegawai" value="{{ $user->id_pegawai }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProfile"><i class="fas fa-user-circle"></i> Ubah Profil</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">NIP</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="nip" value="{{ $user->nip_pegawai }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Nama Pegawai</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="nama_pegawai" value="{{ $user->nama_pegawai }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Jabatan</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="keterangan_pegawai" value="{{ $user->keterangan_pegawai }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Unit Kerja</label>
                                                        <div class="col-md-9">
                                                            <input type="hidden" class="form-control" value="{{ $user->id_unit_kerja }}">
                                                            <input type="text" class="form-control" value="{{ $user->unit_kerja }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">No. Hp Aktif</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="nohp_pegawai" value="{{ $user->nohp_pegawai }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Username</label>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" name="username" value="{{ $user->username }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda ingin mengubah profil ?')">
                                                Ubah
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Edit Password-->
                        <div class="modal fade" id="editPassword" tabindex="-1" role="dialog" aria-labelledby="editPassword" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <form action="{{ url('unit-kerja/profil/edit-password/'. Auth::user()->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_pegawai" value="{{ $user->id_pegawai }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPassword"><i class="fas fa-user-circle"></i> Ubah Profil</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group row ">
                                                        <label class="col-form-label col-md-3">Password Lama</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group-append">
                                                                <input type="password" name="old_password" class="form-control" id="password1" placeholder="Password" minlength="8">
                                                                <div class="input-group-text">
                                                                    <a type="button" onclick="viewPass1()"><span class="fas fa-eye"></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Password Baru</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group-append">
                                                                <input type="password" name="password" class="form-control" id="password2" placeholder="Password" minlength="8">
                                                                <div class="input-group-text">
                                                                    <a type="button" onclick="viewPass2()"><span class="fas fa-eye"></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-form-label col-md-3">Konfirmasi Password</label>
                                                        <div class="col-md-9">
                                                            <div class="input-group-append">
                                                                <input type="password" name="password_confirmation" class="form-control" id="password3" placeholder="Password" minlength="8">
                                                                <div class="input-group-text">
                                                                    <a type="button" onclick="viewPass3()"><span class="fas fa-eye"></span></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah anda ingin mengubah profil ?')">
                                                Ubah
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
    </div>
</section>

<script type="text/javascript">
    function viewPass1() {
        var x = document.getElementById("password1");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function viewPass2() {
        var x = document.getElementById("password2");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    function viewPass3() {
        var x = document.getElementById("password3");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>


@endsection
