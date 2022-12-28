@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <!-- <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li> -->
                    <li class="breadcrumb-item active mt-2">Dashboard</li>
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
            <div class="card-header text-capitalize ">
                <h3 class="card-title">usulan pengajuan {{ $aksi }} gedung/bangunan </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/gdn/usulan/proses/'. $aksi) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/GDN/'.$aksi.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="hidden" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('YYYY-MM-DD') }}" readonly>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::now()->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div id="main-gdn">
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-8 text-muted float-left mt-2">Lokasi Perbaikan / Struktural</label>
                            <label class="col-sm-4 text-muted text-right">
                                <a id="btn-total" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Tambah List Baru
                                </a>
                            </label>
                        </div>
                        <hr style="border: 0.5px solid grey;">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Lokasi Perbaikan (*)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control text-uppercase" name="lokasi_bangunan[]" placeholder="Contoh: Gedung Sujudi/Gedung Adhyatma" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Lokasi Spesifik (*)</label>
                            <div class="col-sm-10">
                                <textarea name="lokasi_spesifik[]" class="form-control" rows="3" placeholder="Contoh: Lantai 4, Ruang Rapat" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis Perbaikan</label>
                            <div class="col-sm-4">
                                <select class="form-control bidang-kerusakan" data-idtarget="">
                                    <option value="">-- Pilih Jenis Perbaikan --</option>
                                    <option value="AR">ARSITEKTURAL (AR)</option>
                                    <option value="ME">MEKANIKAL ENGINEERING (ME)</option>
                                    <option value="ST">STRUKTURAL (ST)</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Bidang Perbaikan</label>
                            <div class="col-sm-4">
                                <select class="form-control" name="bid_kerusakan_id[]" id="bidangKerusakan">
                                    <option value="">-- Pilih Bidang Kerusakan --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan[]" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                            </div>
                        </div>
                        <div id="section-gdn"></div>
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah anda ingin mengajukan usulan perbaikan ?')">SUBMIT</button>
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
        // More Item
        $('#btn-total').click(function() {
            ++i;
            $("#section-gdn").append(
                `<div class="gdn">
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
                        <label class="col-sm-2 col-form-label">Lokasi Perbaikan (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="lokasi_bangunan[]" placeholder="Contoh: Gedung Sujudi/Gedung Adhyatma" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Lokasi Spesifik (*)</label>
                        <div class="col-sm-10">
                            <textarea name="lokasi_spesifik[]" class="form-control" rows="3" placeholder="Contoh: Lantai 4, Ruang Rapat" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis Perbaikan</label>
                        <div class="col-sm-4">
                            <select class="form-control bidang-kerusakan" data-idtarget="` + i + `">
                                <option value="">-- Pilih Jenis Perbaikan --</option>
                                <option value="AR">ARSITEKTURAL (AR)</option>
                                <option value="ME">MEKANIKAL ENGINEERING (ME)</option>
                                <option value="ST">STRUKTURAL (ST)</option>
                            </select>
                        </div>
                        <label class="col-sm-2 col-form-label">Bidang Perbaikan</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="bid_kerusakan_id[]" id="bidangKerusakan`+i+`">
                                <option value="">-- Pilih Bidang Kerusakan --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="keterangan[]" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                        </div>
                    </div>
                    <div id="section-gdn"></div>
                </div>`
            )
        })

        $(document).on('change', '.bidang-kerusakan', function() {
            let target = $(this).data('idtarget')
            let jenisKerusakan = $(this).val()
            if (jenisKerusakan) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/gdn/js/bidang-kerusakan/` + jenisKerusakan + `') }}`,
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
            $(this).parents('.gdn').remove();
        });



    })
</script>
@endsection

@endsection
