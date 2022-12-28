@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Barang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('admin-user/rdn/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/rdn/rumah-dinas/daftar/seluruh-rumah') }}">Daftar Rumah</a></li>
                    <li class="breadcrumb-item active">Barang</li>
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
                <div class="card">
                    <div class="card-body box-profile text-capitalize">
                        <div class="text-center">
                            @if($rumah->foto_rumah == null)
                            <img src="{{ asset('dist_admin/img/1224838.png') }}" class="img-thumbnail mt-2" style="width: 100%;">
                            @else
                            <img src="{{ asset('gambar/rumah_dinas/'. $rumah->foto_rumah) }}" class="img-thumbnail mt-2" style="width: 100%;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $rumah->lokasi_kota }}</h3>
                        <p class="text-muted text-center">{{ $rumah->alamat_rumah }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#informasi-barang" data-toggle="tab">Informasi Rumah Dinas</a></li>
                            <li class="nav-item"><a class="nav-link" href="#riwayat-barang" data-toggle="tab">Riwayat</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="informasi-barang">
                                <form action="{{ url('admin-user/rdn/rumah-dinas/proses-ubah/'. $rumah->id_rumah_dinas) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id_penghuni" value="{{ $penghuni->id_penghuni }}">
                                    <div class="form-group row">
                                        <label class="text-muted col-md-12">Informasi Penghuni</label>
                                        <label class="col-md-2 col-form-label">Penghuni</label>
                                        <div class="col-md-10">
                                            <select name="id_pegawai" class="form-control select2-pegawai penghuni" disabled>
                                                <option value="{{ $penghuni->pegawai_id }}">{{ $penghuni->nama_pegawai }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Jabatan </label>
                                        <div class="col-md-10">
                                            <span id="jabatan"><input type="text" class="form-control" placeholder="{{ $penghuni->keterangan_pegawai }}" readonly></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">No. SIP </label>
                                        <div class="col-md-10">
                                            <input type="text" name="nomor_sip" class="form-control" value="{{ $penghuni->nomor_sip }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Masa Berakhir SIP </label>
                                        <div class="col-md-10">
                                            <input type="date" name="masa_berakhir_sip" class="form-control" value="{{ \Carbon\Carbon::parse($penghuni->masa_berakhir_sip)->isoFormat('Y-MM-DD') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Jenis Sertifikat </label>
                                        <div class="col-md-10">
                                            <input type="text" name="jenis_sertifikat" class="form-control" value="{{ $penghuni->jenis_sertifikat }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Status Penghuni </label>
                                        <div class="col-md-10">
                                            <select class="form-control" name="status_penghuni" disabled>
                                                @if($penghuni->status_penghuni == 1)
                                                <option value="1">Aktif</option>
                                                <option value="2">Tidak Aktif</option>
                                                @else
                                                <option value="2">Tidak Aktif</option>
                                                <option value="1">Aktif</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="text-muted col-md-12">Informasi Rumah</label>
                                        <label class="col-md-2 col-form-label">Golongan</label>
                                        <div class="col-md-10">
                                            <select name="golongan_rumah" class="form-control" disabled>
                                                @if($penghuni->golongan_rumah == 'I')
                                                <option value="I">I</option>
                                                <option value="II">II</option>
                                                @else
                                                <option value="II">II</option>
                                                <option value="I">I</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">NUP Rumah</label>
                                        <div class="col-md-10">
                                            <input type="text" name="nup_rumah" class="form-control" value="{{ $rumah->nup_rumah }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Lokasi Kota </label>
                                        <div class="col-md-10">
                                            <input type="text" name="lokasi_kota" class="form-control" value="{{ $rumah->lokasi_kota }}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Luas Bangunan</label>
                                        <div class="col-md-10 input-group">
                                            <input type="number" name="luas_bangunan" class="form-control" value="{{ $rumah->luas_bangunan }}" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span>m<sup>2</sup></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Luas Tanah</label>
                                        <div class="col-md-10 input-group">
                                            <input type="number" name="luas_tanah" class="form-control" value="{{ $rumah->luas_tanah }}" readonly>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span>m<sup>2</sup></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Kondisi Rumah </label>
                                        <div class="col-md-10">
                                            <select name="kondisi_rumah_id" class="form-control" disabled>
                                                @foreach($kondisi as $dataKondisi)
                                                <option value="{{ $dataKondisi->id_kondisi_rumah }}" <?php if ($rumah->kondisi_rumah_id == $dataKondisi->id_kondisi_rumah) echo "selected"; ?>>
                                                    {{ $dataKondisi->kondisi_rumah }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Alamat Lengkap </label>
                                        <div class="col-md-10">
                                            <textarea type="text" name="alamat_rumah" class="form-control" readonly>{{ $rumah->alamat_rumah }}</textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="riwayat-barang">
                                @foreach($rumah->PenghuniRumah as $i => $riwayatPenghuni)
                                <div class="timeline timeline-inverse">
                                    <div class="time-label">
                                        <span class="bg-danger">
                                            {{ \Carbon\Carbon::parse($riwayatPenghuni->tanggal_pengguna)->isoFormat('DD MMMM Y') }}
                                        </span>
                                    </div>
                                    <div>
                                        <i class="fas fa-user bg-primary"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="far fa-date"></i> {{ \Carbon\Carbon::parse($riwayatPenghuni->tanggal_pengguna)->isoFormat('DD MMMM Y') }}</span>

                                            <h3 class="timeline-header text-capitalize">
                                                <a href="#">{{ $riwayatPenghuni->nama_pegawai }}</a> <br> {{ $riwayatPenghuni->jabatan.' '.$riwayatPenghuni->keterangan_pegawai }}
                                            </h3>

                                            <div class="timeline-body">
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <label>Nama Penghuni : </label>
                                                        <p>{{ $riwayatPenghuni->nama_pegawai }}</p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label>Kondisi Rumah : </label>
                                                        <p>{{ $riwayatPenghuni->kondisi_rumah }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

    $(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        // Select-2
        $(".select2-pegawai").select2({
            ajax: {
                url: "{{ url('admin-user/select2/pegawai') }}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term // search term
                    };
                },
                processResults: function(response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

    });

    // Menampilkan informasi barang yang dipilih
    $(document).on('change', '.penghuni', function() {
        let penghuni = $(this).val()
        if (penghuni) {
            $.ajax({
                type: "GET",
                url: "/admin-user/jabatan?penghuni=" + penghuni,
                dataType: 'JSON',
                success: function(res) {
                    if (res) {
                        $("#jabatan").empty();
                        $.each(res, function(id_pegawai, keterangan_pegawai) {
                            $("#jabatan").append(
                                '<input type="text" class="form-control" value="' + keterangan_pegawai + '" readonly>'
                            )
                        });
                    } else {
                        $("#jabatan").empty();
                    }
                }
            });
        } else {
            $("#barang" + target).empty();
        }
    });
</script>
@endsection

@endsection
