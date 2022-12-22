@extends('v_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0 ">usulan {{ $aksi }} ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li> -->
                    <li class="breadcrumb-item active">usulan {{ $aksi }} ATK</li>
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
            <div class="card-header">
                <h3 class="card-title text-capitalize">usulan {{ $aksi }} ATK</h3>
            </div>
            <div class="card-body">
                <form action="{{ url('super-user/atk/usulan/proses/'. $aksi) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_usulan" value="{{ $idUsulan }}">
                    <input type="hidden" name="jenis_form" value="1">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ 'usulan/atk/'.$aksi.'/'.$idUsulan.'/'.\Carbon\Carbon::now()->isoFormat('MMMM').'/'.\Carbon\Carbon::now()->isoFormat('Y') }} " readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::now()->isoFormat('Y-MM-DD') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pemakaian (*)</label>
                        <div class="col-sm-10">
                            <textarea name="rencana_pengguna" class="form-control" rows="3" placeholder="Rencana Pemakaian Barang" required></textarea>
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
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kategori" id="kategori">
                                    <option value="">-- KATEGORI BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Jenis</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize jenis" id="jenis">
                                    <option value="">-- JENIS BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize barang" id="barang">
                                    <option value="">-- NAMA BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                            <div class="col-sm-4">
                                <select name="atk_id[]" class="form-control text-capitalize merktipe" id="merktipe">
                                    <option value="">-- MERK/TIPE BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-4">
                                <span id="stok"><input class="form-control" placeholder="STOK BARANG" readonly></span>
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan1-atk"><input class="form-control" placeholder="SATUAN" readonly></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah (*)</label>
                            <div class="col-sm-4">
                                @if ($aksi == 'pengadaan')
                                <input type="number" class="form-control" name="jumlah[]" min="1" placeholder="MASUKAN JUMLAH BARANG" required>
                                @else
                                <span id="jumlahDistribusi"><input class="form-control" placeholder="MASUKAN JUMLAH BARANG" readonly></span>
                                @endif
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan2-atk"><input class="form-control" placeholder="SATUAN" readonly></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Keterangan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="keterangan[]" placeholder="Mohon isi, jika terdapat keterangan permintaan"></textarea>
                            </div>
                        </div>
                        <div id="section-atk"></div>
                        <div class="form-group row">
                            <label class="col-sm-2">&nbsp;</label>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary font-weight-bold" onclick="return confirm('Apakah anda ingin melakukan pengajuan perbaikan ?')">SUBMIT</button>
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

        // Daftar Kategori
        $(".kategori").select2({
            ajax: {
                url: `{{ url('super-user/atk/select2/1/kategori') }}`,
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
        $(document).on('change', '.kategori', function() {
            let kategori = $(this).val()
            if (kategori) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/atk/select2/2/` + kategori + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#jenis").empty();
                            $("#jenis").select2();
                            $("#jenis").append('<option value="">-- JENIS BARANG --</option>');
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

        // Daftar Barang
        $(document).on('change', '.jenis', function() {
            let jenis = $(this).val()
            if (jenis) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/atk/select2/3/` + jenis + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#barang").empty();
                            $("#barang").select2();
                            $("#barang").append('<option value="">-- NAMA BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#barang").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#barang").empty();
                        }
                    }
                })
            } else {
                $("#barang").empty();
            }
        })
        // Daftar Merk
        $(document).on('change', '.barang', function() {
            let barang = $(this).val()
            if (barang) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/atk/select2/4/` + barang + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#merktipe").empty();
                            $("#merktipe").select2();
                            $("#merktipe").append('<option value="">-- MERK/TIPE BARANG --</option>');
                            $.each(res, function(index, row) {
                                $("#merktipe").append(
                                    '<option value="' + row.id + '">' + row.text + '</option>'
                                )
                            });
                        } else {
                            $("#merktipe").empty();
                        }
                    }
                })
            } else {
                $("#merktipe").empty();
            }
        })

        // Daftar Stok
        $(document).on('change', '.merktipe', function() {
            let merktipe = $(this).val()
            if (merktipe) {
                $.ajax({
                    type: "GET",
                    url: `{{ url('super-user/atk/select2/5/` + merktipe + `') }}`,
                    dataType: 'JSON',
                    success: function(res) {
                        if (res) {
                            $("#stok").empty();
                            $("#satuan1-atk").empty();
                            $("#satuan2-atk").empty();
                            $("#jumlahDistribusi").empty();
                            $.each(res, function(index, row) {
                                $("#stok").append(
                                    '<input type="number" class="form-control" value="' + row.stok + '" readonly>'
                                )
                                $("#satuan1-atk").append(
                                    '<input type="text" class="form-control" value="' + row.satuan + '" readonly>'
                                )
                                $("#satuan2-atk").append(
                                    '<input type="text" class="form-control" name="satuan[]" value="' + row.satuan + '" readonly>'
                                )
                                $("#jumlahDistribusi").append(
                                    '<input type="number" class="form-control" name="jumlah[]" min="1" max="' + row.stok + '" required>'
                                )
                            });
                        } else {
                            $("#stok").empty();
                        }
                    }
                })
            } else {
                $("#stok").empty();
            }
        })

        // More Item
        $('#btn-total').click(function() {
            ++i
            $("#section-atk").append(
                `<div class="atk">
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
                            <label class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-4">
                                <select class="form-control text-capitalize kategori` + i + `" data-idtarget=` + i + `>
                                    <option value="">-- KATEGORI BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Jenis</label>
                            <div class="col-sm-4">
                                <select id="jenis` + i + `" class="form-control text-capitalize select2-` + i + `-2 jenis` + i + `" data-idtarget=` + i + `>
                                    <option value="">-- JENIS BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select id="barang` + i + `" class="form-control text-capitalize select2-` + i + `-3 barang` + i + `" data-idtarget=` + i + `>
                                    <option value="">-- NAMA BARANG --</option>
                                </select>
                            </div>
                            <label class="col-sm-2 col-form-label">Merk/Tipe</label>
                            <div class="col-sm-4">
                                <select name="atk_id[]" id="merktipe` + i + `" class="form-control text-capitalize select2-` + i + `-4 merktipe` + i + `" data-idtarget=` + i + `>
                                    <option value="">-- MERK/TIPE BARANG --</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-4">
                                <span id="stok` + i + `"><input class="form-control" placeholder="STOK BARANG" readonly></span>
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan1` + i + `"><input class="form-control" placeholder="SATUAN" readonly></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah (*)</label>
                            <div class="col-sm-4">
                                @if ($aksi == 'pengadaan')
                                <input type="number" class="form-control" name="jumlah[]" min="1" placeholder="MASUKAN JUMLAH BARANG" required>
                                @else
                                <span id="jumlahDistribusi` + i + `"><input class="form-control" placeholder="MASUKAN JUMLAH BARANG" readonly></span>
                                @endif
                            </div>
                            <label class="col-sm-2 col-form-label">Satuan</label>
                            <div class="col-sm-4">
                                <span id="satuan2` + i + `"><input class="form-control" placeholder="SATUAN" readonly></span>
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

            $(".kategori" + i).select2({
                ajax: {
                    url: `{{ url('super-user/atk/select2/1/kategori') }}`,
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
            $(document).on('change', '.kategori' + i, function() {
                let target = $(this).data('idtarget')
                let kategori = $(this).val()
                if (kategori) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('super-user/atk/select2/2/` + kategori + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#jenis" + target).empty();
                                $("#jenis" + target).select2();
                                $("#jenis" + target).append('<option value="">-- JENIS BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#jenis" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                });
                            } else {
                                $("#jenis" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#jenis" + target).empty();
                }
            })

            // Daftar Barang
            $(document).on('change', '.jenis' + i, function() {
                let target = $(this).data('idtarget')
                let jenis = $(this).val()
                if (jenis) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('super-user/atk/select2/3/` + jenis + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#barang" + target).empty();
                                $("#barang" + target).select2();
                                $("#barang" + target).append('<option value="">-- NAMA BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#barang" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                });
                            } else {
                                $("#barang" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#barang" + target).empty();
                }
            })

            // Daftar Merk
            $(document).on('change', '.barang' + i, function() {
                let target = $(this).data('idtarget')
                let barang = $(this).val()
                if (barang) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('super-user/atk/select2/4/` + barang + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#merktipe" + target).empty();
                                $("#merktipe" + target).select2();
                                $("#merktipe" + target).append('<option value="">-- MERK/TIPE BARANG --</option>');
                                $.each(res, function(index, row) {
                                    $("#merktipe" + target).append(
                                        '<option value="' + row.id + '">' + row.text + '</option>'
                                    )
                                });
                            } else {
                                $("#merktipe" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#merktipe" + target).empty();
                }
            })

            // Daftar Stok
            $(document).on('change', '.merktipe' + i, function() {
                let target = $(this).data('idtarget')
                let merktipe = $(this).val()
                if (merktipe) {
                    $.ajax({
                        type: "GET",
                        url: `{{ url('super-user/atk/select2/5/` + merktipe + `') }}`,
                        dataType: 'JSON',
                        success: function(res) {
                            if (res) {
                                $("#stok" + target).empty();
                                $("#satuan1" + target).empty();
                                $("#satuan2" + target).empty();
                                $("#jumlahDistribusi" + target).empty();
                                $.each(res, function(index, row) {
                                    $("#stok" + target).append(
                                        '<input type="number" class="form-control" value="' + row.stok + '" readonly>'
                                    )
                                    $("#satuan1" + target).append(
                                        '<input type="text" class="form-control" value="' + row.satuan + '" readonly>'
                                    )
                                    $("#satuan2" + target).append(
                                        '<input type="text" class="form-control" name="satuan[]" value="' + row.satuan + '" readonly>'
                                    )
                                    $("#jumlahDistribusi" + target).append(
                                        '<input type="number" class="form-control" name="jumlah[]" min="1" max="' + row.stok + '" required>'
                                    )
                                });
                            } else {
                                $("#stok" + target).empty();
                            }
                        }
                    })
                } else {
                    $("#stok" + target).empty();
                }
            })

        })

        $(document).on('click', '.remove-list', function() {
            $(this).parents('.atk').remove();
        });



    })
</script>
@endsection

@endsection
