@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h4 class="m-0">Tambah ATK</h4>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if ($id == 3)
                <form action="{{ url('admin-user/atk/barang/proses-tambah-barang/baru') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kelompok Baarng</label>
                        <div class="col-sm-10">
                            <select name="" class="form-control sub-barang">
                                <option value="">-- PILIH JENIS BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kategori Barang</label>
                        <div class="col-sm-10">
                            <select id="kategori" name="jenis_atk" class="form-control kategori">
                                <option value="">-- PILIH KATEGORI BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row jumlah">
                        <label class="col-sm-2 col-form-label">Jumlah Pengajuan</label>
                        <div class="col-sm-1">
                            <input type="number" name="total_pengajuan" id="jumlahAtk" class="form-control" value="1" placeholder="Jumlah Barang">
                        </div>
                        <div class="col-sm-1">
                            <button id="btn-total" class="btn btn-primary btn-block">Pilih</button>
                        </div>
                    </div>
                    <div id="section-atk"></div>
                </form>
                @endif
                @if ($id == 4)
                <form action="{{ url('admin-user/atk/barang/proses-tambah-detail/baru') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kelompok Baarng</label>
                        <div class="col-sm-10">
                            <select name="" class="form-control sub-barang">
                                <option value="">-- PILIH JENIS BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kategori Barang</label>
                        <div class="col-sm-10">
                            <select id="kategori" name="jenis_atk" class="form-control kategori">
                                <option value="">-- PILIH KATEGORI BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Barang *</label>
                        <div class="col-sm-10">
                            <select id="jenis" name="kategori_atk" class="form-control jenis" required>
                                <option value="">-- PILIH BARANG --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row jumlah">
                        <label class="col-sm-2 col-form-label">Jumlah Pengajuan</label>
                        <div class="col-sm-1">
                            <input type="number" name="total_pengajuan" id="jumlahAtk" class="form-control" value="1" placeholder="Jumlah Barang">
                        </div>
                        <div class="col-sm-1">
                            <button id="btn-total" class="btn btn-primary btn-block">Pilih</button>
                        </div>
                    </div>
                    <div id="section-atk"></div>
                </form>
                @endif
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        $(".sub-barang").select2({
            ajax: {
                url: `{{ url('admin-user/atk/select2/1/kategori') }}`,
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
        })

        // Daftar Jenis
        $(document).on('change', '.sub-barang', function() {
            let kategori = $(this).val()
            console.log(kategori)
            if (kategori) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('admin-user/atk/select2/2/` + kategori + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#kategori").empty();
                            $("#kategori").select2();
                            $("#kategori").append('<option value="">-- PILIH KATEGORI BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#kategori").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#kategori").empty();
                        }
                    }
                })
            } else {
                $("#kategori" + target).empty();
            }
        })
        // Daftar Barang
        $(document).on('change', '.kategori', function() {
            let jenis = $(this).val()
            if (jenis) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('admin-user/atk/select2/3/` + jenis + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#jenis").empty();
                            $("#jenis").select2();
                            $("#jenis").append('<option value="">-- PILIH BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#jenis").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#jenis").empty();
                        }
                    }
                })
            } else {
                $("#jenis").empty();
            }
        })
        // Daftar Merk
        $(document).on('change', '.jenis', function() {
            let barang = $(this).val()
            if (barang) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('admin-user/atk/select2/4/` + barang + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#kodeBarang").empty();
                            $("#kodeBarang").select2();
                            $.each(res, function(index, row) {
                                $("#kodeBarang").append(
                                    '<input type="text" name="id_atk" class="form-control" value="' + index + '" readonly>'
                                )
                            });
                        } else {
                            $("#kodeBarang").empty();
                        }
                    }
                })
            } else {
                $("#kodeBarang").empty();
            }
        })
        $('#btn-total').click(function() {
            total = ($('#jumlahAtk').val())
            j = 1
            $("#section-atk").empty()
            for (let i = 1; i <= total; i++) {
                $("#section-atk").append(
                    `<div class="form-group row">
                        <label class="col-sm-2 col-form-label">Barang ` + i + `</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="barang[]" placeholder="Nama Barang`+i+`">
                        </div>
                    </div>
                    @if ($id == 4)
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Satuan ` + i + ` *</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control text-uppercase" name="satuan[]" placeholder="Satuan `+i+`" required>
                            </div>
                            <label class="col-sm-2 col-form-label">Keterangan ` + i + `</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control text-uppercase" name="keterangan[]" placeholder="Keterangan `+i+`">
                            </div>
                        </div>
                    @endif
                    `
                )
            }
            $('#section-atk').append(
                `<div class="form-group row">
                    <label class="col-sm-2">&nbsp;</label>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah data sudah terisi dengan benar ?')">SUBMIT</button>
                    </div>
                </div>`
            )

            // $( ".jumlah" ).hide();
            $("#btn-total").prop("disabled", true);
        })

    })
</script>
@endsection


@endsection
