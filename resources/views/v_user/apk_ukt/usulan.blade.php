@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Usulan Kerumah Tanggaan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li> -->
                    <li class="breadcrumb-item active mt-2">Pekerjaan Kerumah Tanggaan</li>
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
                <h3 class="card-title">usulan pengajuan pekerjaan kerumahtanggaan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('unit-kerja/ukt/usulan/proses/pengajuan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_form" value="{{ $aksi }}">
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
                            <label class="col-sm-8 text-muted float-left mt-2">Lokasi Pekerjaan</label>
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
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-10 text-right">
                                <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah anda ingin melakukan pengajuan pekerjaan ?')">
                                   <i class="fas fa-paper-plane"></i> SUBMIT
                                </button>
                            </div>
                        </div>
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
        $('.spesifikasi').summernote({
            height: 150,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
            ]
        })
        // More Item
        $('#btn-total').click(function() {
            ++i;
            $("#section-ukt").append(
                `<div class="ukt">
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-8 text-muted float-left mt-2">Lokasi Pekerjaan</label>
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
                    url: `{{ url('unit-kerja/ukt/js/bidang-kerusakan/` + jenisKerusakan + `') }}`,
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

    })
</script>
@endsection

@endsection
