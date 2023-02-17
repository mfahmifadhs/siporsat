@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2 text-capitalize">
            <div class="col-sm-6">
                <h1 class="m-0">proses penyelesaian {{ $usulan->jenis_form }} ATK </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}">Daftar Pengajuan</a></li>
                    <li class="breadcrumb-item active">Buat Berita Acara</li>
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
            <form class="form-pengajuan" action="{{ url('super-user/ppk/atk/pengajuan/proses/'. $usulan->id_form_usulan) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <input type="hidden" name="id_form_usulan" value="{{ $usulan->id_form_usulan }}">
                    <input type="hidden" name="pegawai_id" value="{{ $usulan->id_pegawai }}">
                    <span id="kode_otp"></span>
                    @csrf
                    <!-- <div class="form-group row">
                        <div class="col-md-12"><label class="text-muted">Informasi Pengusul</label></div>
                        <label class="col-sm-2 col-form-label">Nomor Surat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.04/2/'.$idBast.'/'.Carbon\carbon::now()->isoFormat('Y') }}" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat Usulan </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" value="{{ $usulan->no_surat_usulan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jabatan Pengusul </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-capitalize" value="{{ $usulan->keterangan_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit Kerja </label>
                        <div class="col-sm-10">
                            <input type="hidden" name="unit_kerja_id" value="{{ $usulan->id_unit_kerja }}">
                            <input type="text" class="form-control text-capitalize" value="{{ $usulan->unit_kerja }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-md-12"><label class="text-muted">Informasi Barang</label></div>
                    </div>
                    <div class="form-group row mt-4">
                        <div class="col-sm-12">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width: 2%;">No</th>
                                        <th style="width: 20%;">Nama Barang</th>
                                        <th style="width: 25%;">Merk/Tipe</th>
                                        <th style="width: 15%;">Jumlah Pengajuan</th>
                                        @if ($usulan->jenis_form == 'distribusi')
                                        <th style="width: 15%;">Jumlah Disetujui</th>
                                        @endif
                                        <th>Satuan</th>
                                        <th>Status</th>
                                        <th style="width: 20%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <?php $no = 1; ?>
                                <tbody style="font-size: 13px;">
                                    @if ($usulan->jenis_form == 'pengadaan')
                                    @foreach($usulan->pengadaanAtk as $i => $dataPengadaan)
                                    <tr>
                                        <td> {{ $i + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="modul" value="pengadaan">
                                            <input type="hidden" name="id_pengadaan[]" value="{{ $dataPengadaan->id_form_usulan_pengadaan }}">
                                            {{ $dataPengadaan->nama_barang }}
                                        </td>
                                        <td>{{ $dataPengadaan->spesifikasi }}</td>
                                        <td>
                                            <input type="number" class="text-center form-control" name="jumlah_pengajuan[]" value="{{ $dataPengadaan->jumlah }}">
                                        </td>
                                        <td>{{ $dataPengadaan->satuan }}</td>
                                        <td class="text-center">
                                            <input type="hidden" value="ditolak" name="konfirmasi_atk[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" name="konfirmasi_atk[{{$i}}]" id="checkbox_id{{$i}}" value="diterima" required> <br>
                                            <label for="checkbox_id{{$i}}">Diterima</label>
                                        </td>
                                        <td>
                                            <input name="keterangan[]" class="form-control" style="font-size: 13px;">
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    @foreach($usulan->permintaanAtk as $i => $dataPermintaan)
                                    <tr>
                                        <td> {{ $i + 1 }}</td>
                                        <td>
                                            {{ $dataPermintaan->nama_barang }}
                                        </td>
                                        <td class="pt-3">{{ $dataPermintaan->spesifikasi }}</td>
                                        <td class="pt-3">{{ $dataPermintaan->jumlah }}</td>
                                        <td class="pt-3">{{ $dataPermintaan->jumlah_disetujui }}</td>
                                        <td class="pt-3">{{ $dataPermintaan->satuan }}</td>
                                        <td class="text-center text-uppercase pt-3">
                                            <label for="checkbox_id{{$i}}">{{ $dataPermintaan->status }}</label>
                                        </td>
                                        <td class="pt-3">{{ $dataPermintaan->keterangan }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-md font-weight-bold float-right" onclick="return confirm('Apakah barang sudah tersedia ?')">
                        <i class="fas fa-paper-plane"></i> SUBMIT
                    </button>
                </div>
            </form>
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
        $('.spesifikasi').summernote({
            height: 150,
            toolbar: [
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
            ]
        })

        $("#table-barang").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "searching": false,
            "paging": false,
            "info": false,
            "sort": false,
        }).buttons().container().appendTo('#table-barang_wrapper .col-md-6:eq(0)');
    })

    $('.image').change(function() {
        let idtarget = $(this).data('idtarget');
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#preview-image-before-upload' + idtarget).attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);

    });
</script>
@endsection

@endsection
