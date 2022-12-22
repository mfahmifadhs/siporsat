@extends('v_admin_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-8 text-capitalize">
                <h4 class="m-0">proses {{ $usulan->jenis_form }} ATK</h4>
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
                <h3 class="card-title text-capitalize">proses {{ $usulan->jenis_form }} ATK </h3>
            </div>
            <div class="card-body">
                <form action="{{ url('admin-user/atk/usulan/proses-input/'. $usulan->jenis_form) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @php
                    $totalUsulan = $total->total_form + 1;
                    $tahun = \Carbon\Carbon::now()->isoFormat('Y');
                    @endphp
                    @if ($total->jenis_form == 'pengadaan')
                    <input type="hidden" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.01.03/2/'.$totalUsulan.'/'.$tahun }}" readonly>
                    @else
                    <input type="hidden" class="form-control text-uppercase" name="no_surat_bast" value="{{ 'KR.02.04/2/'.$totalUsulan.'/'.$tahun }}" readonly>
                    @endif
                    <input type="hidden" name="form_id" value="{{ $id }}">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ $usulan->no_surat_usulan }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tanggal_usulan" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Pengusul</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                        <label class="col-sm-1 col-form-label">Unit Kerja</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" value="{{ $usulan->unit_kerja }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Rencana Pemakaian (*)</label>
                        <div class="col-sm-10">
                            <textarea name="rencana_pengguna" class="form-control" rows="3" readonly>{{ $usulan->rencana_pengguna }}</textarea>
                        </div>
                    </div>
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-12 col-form-label text-muted">Informasi Barang</label>
                        <label class="col-sm-2 col-form-label">Jumlah Pengajuan</label>
                        <label class="col-sm-4 col-form-label">: {{ $usulan->total_pengajuan }} barang</label>
                    </div>
                    @foreach($usulan->detailUsulanAtk as $detailUsulan)
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Barang</label>
                        <div class="col-sm-4">
                            <input type="hidden" class="form-control" name="form_detail_id[]" value="{{ $detailUsulan->id_form_usulan_detail }}" readonly>
                            <input type="text" class="form-control" value="{{ $detailUsulan->kategori_atk }}" readonly>
                        </div>
                        <label class="col-sm-1 col-form-label">Merk/Tipe</label>
                        <div class="col-sm-5">
                            <input type="hidden" class="form-control" name="atk_id[]" value="{{ $detailUsulan->atk_id }}" readonly>
                            <input type="text" class="form-control" value="{{ $detailUsulan->merk_atk }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="jumlah[]" value="{{ $detailUsulan->jumlah_pengajuan }}" readonly>
                        </div>
                        <label class="col-sm-1 col-form-label">Satuan</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="satuan[]" value="{{ $detailUsulan->satuan }}" readonly>
                        </div>
                    </div>
                    @if($usulan->jenis_form == 'pengadaan')
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Harga Barang (*) </label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="harga[]" placeholder="Masukan Harga Barang" required>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @if($usulan->jenis_form == 'pengadaan')
                    <hr style="border: 0.5px solid grey;">
                    <div class="form-group row">
                        <label class="col-sm-12 col-form-label text-muted">Bukti Kwitansi Pembelian</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Kontrak (*)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nomor_kontrak" placeholder="Masukan Nomor Kontrak" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nomor Kwitansi (*)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="nomor_kwitansi" placeholder="Masukan Nomor Kwitansi" required>
                        </div>
                        <label class="col-sm-2 col-form-label">Nilai Kwitansi (*)</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control" name="nilai_kwitansi" placeholder="Masukan Nilai Kwitansi" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">File Kwitansi (*)</label>
                        <div class="col-sm-4">
                            <p>
                                <img id="preview-image-before-upload" src="https://cdn-icons-png.flaticon.com/512/1611/1611318.png" class="img-responsive img-thumbnail mt-2" style="width: 20%;">
                            </p>
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> Upload Kwitansi
                                <input type="file" class="form-control image" name="file_kwitansi" accept="image/jpeg , image/jpg, image/png" required>
                            </div><br>
                            <span class="help-block" style="font-size: 12px;">Format file pdf/jpg/jpeg/png dan max 4 MB</span>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label class="col-sm-2">&nbsp;</label>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah data terisi dengan benar ?');">
                                Submit
                            </button>
                        </div>

                    </div>
                </form>
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
</script>
@endsection

@endsection
