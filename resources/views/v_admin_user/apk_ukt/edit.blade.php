@extends('v_user.layout.app')

@section('content')

<section class="content-header">
    <div class="container">
        <div class="row text-capitalize">
            <div class="col-sm-6">
                <h4>Edit Usulan</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('ukt.show', ['aksi' => 'pengajuan', 'id' => '*']) }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Edit Usulan</li>
                </ol>
            </div>
        </div>
    </div>
</section>

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
        <div class="col-md-12 form-group">
            <a href="{{ route('ukt.show', ['aksi' => 'pengajuan', 'id' => '*']) }}" class="print mr-2">
                <i class="fas fa-arrow-circle-left"></i> Kembali
            </a>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header text-capitalize ">
                <h3 class="card-title">Edit Usulan Kerumahtanggaan</h3>
            </div>
            <form action="{{ url('admin-user/ukt/update/usulan/'. $id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->format('Y-m-d') }}">
                        </div>
                    </div>
                    @foreach($usulan->detailUsulanUkt as $row)
                    <div class="">
                        <hr class="mt-5" style="border: 1px solid grey;">
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted float-left mt-2">
                                    <a href="{{ url('admin-user/ukt/delete/detail/'. $row->id_form_usulan_detail) }}" onclick="return confirm('Hapus?')">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                    Informasi Pekerjaan
                                </label>
                                <input type="hidden" name="id_detail[]" value="{{ $row->id_form_usulan_detail }}">
                            </div>
                            <div class="col-md-12">
                                <label class="col-form-label">Pekerjaan*</label>
                                <input type="text" class="form-control text-uppercase" name="lokasi_pekerjaan[]" value="{{ $row->lokasi_pekerjaan }}">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="col-form-label">Spesifikasi Pekerjaan*</label>
                                <textarea name="spesifikasi_pekerjaan[]" class="form-control" rows="5">{{ $row->spesifikasi_pekerjaan }}</textarea>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="col-form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan[]" rows="5">{{ $row->keterangan }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="text-right">
                        <a id="btn-total" class="btn btn-primary btn-sm mt-0">
                            <i class="fas fa-plus-circle"></i> Tambah Pekerjaan
                        </a>
                    </div>

                    <div id="main-ukt">
                        <div id="section-ukt"></div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold" onclick="return confirm('Apakah anda ingin melakukan pengajuan pekerjaan ?')">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
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
                    <hr class="mt-5" style="border: 1px solid grey;">
                    <div class="form-group row mb-5">
                        <div class="col-md-6">
                            <label class="text-muted float-left mt-2">Informasi Pekerjaan</label>
                            <input type="hidden" name="id_detail[]" value="">
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-danger btn-sm remove-list">
                                <i class="fas fa-trash"></i> Hapus List
                            </a>
                        </div>
                        <div class="col-md-12">
                            <label class="col-form-label">Pekerjaan*</label>
                            <input type="text" class="form-control" name="lokasi_pekerjaan[]" placeholder="Lokasi Pekerjaan / Judul Pekerjaan" required>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="col-form-label">Spesifikasi Pekerjaan*</label>
                            <textarea name="spesifikasi_pekerjaan[]" class="form-control" rows="5" placeholder="Detail atau Spesifikasi Pekerjaan" required></textarea>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="col-form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan[]" rows="5" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
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
