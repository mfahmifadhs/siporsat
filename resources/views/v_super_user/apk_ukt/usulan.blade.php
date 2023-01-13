@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Buat Usulan Kerumahtanggaan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/ukt/dashboard') }}"> Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/ukt/usulan/daftar/seluruh-usulan') }}"> Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Buat Usulan</li>
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
        <div class="card card-primary card-outline">
            <div class="card-header text-capitalize ">
                <h3 class="card-title">usulan pengajuan urusan kerumahtanggaan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/ukt/usulan/proses/pengajuan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="{{ $aksi }}">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/UKT/'.$aksi.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('YYYY-MM-DD') }}" readonly>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div id="main-ukt">
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-8 text-muted float-left mt-2">Informasi Pekerjaan</label>
                            <label class="col-sm-4 text-muted text-right">
                                <a id="btn-total" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah List Baru
                                </a>
                            </label>
                        </div>
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Pekerjaan*</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-uppercase" name="lokasi_pekerjaan[]" placeholder="Lokasi Pekerjaan/ Judul Pekerjaan" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Spesifikasi Pekerjaan*</label>
                            <div class="col-sm-10">
                                <textarea name="spesifikasi_pekerjaan[]" class="form-control" rows="10" placeholder="Detail atau Spesifikasi Pekerjaan" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan[]" rows="3" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                            </div>
                        </div>
                        <div id="section-ukt"></div>

                        @if (Auth::user()->pegawai->jabatan_id != 7)
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-10 text-right">
                                <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah anda ingin melakukan pengajuan pekerjaan ?')">
                                    <i class="fas fa-paper-plane"></i> SUBMIT
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        let total = 1
        let i = 0
        // More Item
        $('#btn-total').click(function() {
            ++i;
            $("#section-ukt").append(
                `<div class="ukt">
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-8 text-muted float-left mt-2">Lokasi Perbaikan / Struktural</label>
                        <label class="col-sm-4 text-muted text-right">
                            <a class="btn btn-danger remove-list">
                                <i class="fas fa-trash"></i> Hapus List
                            </a>
                        </label>
                    </div>
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pekerjaan*</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="lokasi_pekerjaan[]" placeholder="Lokasi Pekerjaan / Judul Pekerjaan" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Spesifikasi Pekerjaan*</label>
                        <div class="col-sm-10">
                            <textarea name="spesifikasi_pekerjaan[]" class="form-control" rows="10" placeholder="Detail atau Spesifikasi Pekerjaan" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="keterangan[]" rows="3" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                        </div>
                    </div>
                    <div id="section-ukt"></div>
                </div>`
            )
            $('.spesifikasi').summernote({
                height: 150,
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                    ['color', ['color']],
                    ['para', ['ol', 'ul', 'paragraph', 'height']],
                ]
            })
        })

        $(document).on('change', '.bidang-kerusakan', function() {
            let target = $(this).data('idtarget')
            let jenisKerusakan = $(this).val()
            if (jenisKerusakan) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/ukt/js/bidang-kerusakan/` + jenisKerusakan + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#bidangKerusakan" + target).empty();
                            $("#bidangKerusakan" + target).select2();
                            $("#bidangKerusakan" + target).append('<option value="">-- Pilih Jenis Kerusakan --</option>');
                            $.each(res, function(index, row) {
                                $("#bidangKerusakan" + target).append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#bidangKerusakan" + target).empty();
                        }
                    }
                })
            } else {
                $("#bidangKerusakan" + target).empty();
            }
        })

        $(document).on('click', '.remove-list', function() {
            $(this).parents('.ukt').remove();
        })

        $('.spesifikasi').summernote({
            height: 150,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
            ]
        })

    })
</script>
@endsection

@endsection
