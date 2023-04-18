@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0">Alat Tulis Kantor (ATK)</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/dashboard/roum') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('admin-user/atk/gudang/daftar-transaksi/'. $id) }}">Daftar Transaksi</a></li>
                    <li class="breadcrumb-item active">Tambah Transaksi</li>
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
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                @if ($message = Session::get('failed'))
                <div class="alert alert-danger">
                    <p class="fw-light" style="margin: auto;">{{ $message }}</p>
                </div>
                @endif
                <a href="{{ URL::previous() }}" class="print mr-2">
                    <i class="fas fa-arrow-circle-left"></i> Kembali
                </a>
            </div>
            <!-- Barang Masuk -->
            @if ($id == 'Pembelian')
            <div class="col-md-12 form-group mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Riwayat Pembelian Barang (Barang Masuk)</h3>
                    </div>
                    <form action="{{ url('admin-user/atk/gudang/proses/pembelian') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Informasi Kwitansi</label>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Tanggal*</label>
                                <input class="col-md-6 form-control border-dark" type="datetime-local" name="tanggal_transaksi" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Nomor Kwitansi*</label>
                                <input class="col-md-6 form-control border-dark" type="text" name="nomor_kwitansi" placeholder="Nomor kwitansi pembelian" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Nama Vendor*</label>
                                <input class="col-md-10 form-control border-dark" type="text" name="nama_vendor" placeholder="Nama vendor" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Alamat Vendor*</label>
                                <input class="col-md-10 form-control border-dark" type="text" name="alamat_vendor" placeholder="Alamat lengkap vendor" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Total Jenis Barang*</label>
                                <input class="col-md-4 form-control border-dark" type="number" name="total_barang" value="0" oninput="this.value = Math.abs(this.value)">

                                <label class="col-md-2 col-form-label text-center">Total Biaya*</label>
                                <div class="col-md-4"><span id="totalBiaya"></span></div>
                                <!-- <input class="col-md-4 form-control border-dark" type="text" name="total_biaya" placeholder="Total Biaya Keseluruhan, Contoh: Rp 120.000.000" required> -->
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Keterangan*</label>
                                <textarea class="col-md-10 form-control border-dark" name="keterangan_transaksi" placeholder="Keteragan Tambahan"></textarea>
                            </div>

                            <label class="text-muted mt-4">Informasi Barang</label>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="table-barang" class="table table-bordered" style="font-size: 14px;border:1px solid;">
                                        <thead style="background-color:whitesmoke;">
                                            <tr>
                                                <th class="text-center" style="border-right: 1px solid;width:0%;">No</th>
                                                <th style="border-right: 1px solid;">Kode Barang</th>
                                                <th style="border-right: 1px solid;">Nama Barang</th>
                                                <th class="text-center" style="border-right: 1px solid;width:10%;">Volume</th>
                                                <th class="text-center" style="border-right: 1px solid;width:10%;">Satuan</th>
                                                <th style="border-right: 1px solid;width:15%;" class="text-center">Harga Satuan</th>
                                                <th style="border-right: 1px solid;width:15%;" class="text-center">Jumlah Biaya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang as $i => $atk)
                                            <tr>
                                                <td class="text-center border-dark py-3">{{ $i + 1 }}</td>
                                                <td class="border-dark">
                                                    <input class="form-control form-control-sm" type="hidden" value="{{ $atk->id_atk }}" name="atk_id[]">
                                                    <span class="form-control form-control-sm font-weight-bold" readonly>{{ $atk->kode_ref }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <span class="form-control form-control-sm font-weight-bold" readonly>{{ $atk->deskripsi_barang }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <input class="form-control form-control-sm text-center" type="number" name="volume_transaksi[]" value="0" oninput="this.value = Math.abs(this.value)" required>
                                                </td>
                                                <td class="border-dark">
                                                    <span class="form-control form-control-sm text-center font-weight-bold" readonly>{{ $atk->satuan_barang }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <input type="number" class="form-control form-control-sm text-center" name="harga_satuan[]" value="0" oninput="this.value = Math.abs(this.value)">
                                                </td>
                                                <td class="border-dark">
                                                    <input type="number" class="form-control form-control-sm text-center" name="jumlah_biaya[]" value="0" oninput="this.value = Math.abs(this.value)">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-md font-weight-bold float-right" onclick="return confirm('Apakah data sudah benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Barang Keluar -->
            @else
            <div class="col-md-12 form-group mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Riwayat Permintaan Barang (Barang Keluar)</h3>
                    </div>
                    <form action="{{ url('admin-user/atk/gudang/proses/permintaan') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <label class="text-muted">Informasi {{ $id }}</label>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Tanggal*</label>
                                <input class="col-md-6 form-control border-dark" type="datetime-local" name="tanggal_transaksi" required>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Unit Kerja*</label>
                                <select class="form-control col-md-6 border-dark" name="unit_kerja_id">
                                    <option value="">-- Pilih Unit Kerja</option>
                                    @foreach ($uker as $data)
                                    <option value="{{ $data->id_unit_kerja }}">{{ $data->unit_kerja }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-md-2 col-form-label">Total Jenis Barang*</label>
                                <input class="col-md-6 form-control border-dark" type="number" name="total_barang" value="0" oninput="this.value = Math.abs(this.value)">
                            </div> -->
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Keterangan*</label>
                                <textarea class="col-md-6 form-control border-dark" name="keterangan_transaksi" placeholder="Keteragan Tambahan"></textarea>
                            </div>

                            <label class="text-muted mt-4">Informasi Barang</label>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <table id="table-barang" class="table table-bordered" style="font-size: 14px;border:1px solid;">
                                        <thead style="background-color:whitesmoke;">
                                            <tr>
                                                <th class="text-center" style="border-right: 1px solid;width: 0%;">No</th>
                                                <th style="border-right: 1px solid;width: 10%;">Kode Barang</th>
                                                <th style="border-right: 1px solid;">Nama Barang</th>
                                                <th style="border-right: 1px solid;">Keterangan</th>
                                                <th class="text-center" style="border-right: 1px solid;width: 10%;">Stok</th>
                                                <th style="border-right: 1px solid;width: 15%;" class="text-center">Permintaan</th>
                                                <th class="text-center" style="border-right: 1px solid;width: 10%;">Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barang as $i => $atk)
                                            <tr>
                                                <td class="text-center border-dark py-3">{{ $i + 1 }}</td>
                                                <td class="border-dark">
                                                    <input class="form-control form-control-sm" type="hidden" value="{{ $atk['id_atk'] }}" name="atk_id[]">
                                                    <span class="form-control form-control-sm font-weight-bold" readonly>{{ $atk['kode_ref'] }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <span class="form-control form-control-sm font-weight-bold" readonly>{{ $atk['deskripsi'] }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <span class="form-control form-control-sm font-weight-bold" readonly>{{ $atk['keterangan'] }}</span>
                                                </td>
                                                <td class="border-dark">
                                                    <input class="form-control form-control-sm text-center" type="text" value="{{ $atk['jumlah'] }}" readonly>
                                                </td>
                                                <td class="border-dark">
                                                    <input class="form-control form-control-sm text-center" type="number" name="volume_transaksi[]" value="0" oninput="this.value = Math.abs(this.value)" required>
                                                </td>
                                                <td class="border-dark">
                                                    <span class="form-control form-control-sm text-center font-weight-bold" readonly>{{ $atk['satuan'] }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-md font-weight-bold float-right" onclick="return confirm('Apakah data sudah benar ?')">
                                <i class="fas fa-paper-plane"></i> SUBMIT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@section('js')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(function() {
        // Tabel

        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": true,
            "sort": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
        })
        $("#table-alkom").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "info": false,
            "paging": false,
            "searching": false,
            "sort": false
        })

        // Format Rupiah
        var formatInput = document.getElementById('totalBiaya')
        var hargaInt = ''
        var hargaValue = ''

        function renderInput() {
            var template =
                `<input type="hidden" name="total_biaya" id="harga" class="form-control" placeholder="Masukkan Harga" value="${formatInt(hargaInt)}" />
                 <input type="text" id="harga_format" class="col-md-12 form-control border-dark" placeholder="Masukkan Harga" value="${formatRupiah(hargaValue, 'Rp. ')}" />`
            formatInput.innerHTML = template
            var harga = document.getElementById('harga');
            var hargaFormatted = document.getElementById('harga_format');
            hargaFormatted.addEventListener('keyup', function(e) {
                hargaInt = this.value.replace('Rp. ', '')
                hargaValue = this.value.replace('Rp. ', '')
                renderInput();
            });
            hargaFormatted.focus()
            PosEnd(hargaFormatted)
        }

        function formatInt(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '' : '';
                rupiah += separator + ribuan.join('');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function PosEnd(end) {
            var len = end.value.length;

            // Mostly for Web Browsers
            if (end.setSelectionRange) {
                end.focus();
                end.setSelectionRange(len, len);
            } else if (end.createTextRange) {
                var t = end.createTextRange();
                t.collapse(true);
                t.moveEnd('character', len);
                t.moveStart('character', len);
                t.select();
            }
        }

        renderInput();
    })
</script>

@endsection
@endsection
