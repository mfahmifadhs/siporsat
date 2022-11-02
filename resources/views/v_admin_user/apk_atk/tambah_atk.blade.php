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
                @if ($kategori == 'atk')
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
                        <select id="kategori" name="" class="form-control kategori">
                            <option value="">-- PILIH KATEGORI BARANG --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Barang</label>
                    <div class="col-sm-10">
                        <select id="jenis" name="" class="form-control jenis">
                            <option value="">-- PILIH BARANG --</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Kode Barang</label>
                    <div class="col-sm-10">
                        <span id="kodebarang"><input class="form-control" placeholder="KODE BARANG" readonly></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Merk/Tipe Barang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Masukan Merk/Tipe Barang Secara Spesifik">
                    </div>
                </div>
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
                                    '<input type="text" name="id_atk" class="form-control" value="'+index+'" readonly>'
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
    })
</script>
@endsection


@endsection
