@extends('v_super_user.layout.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Proses Persetujuan Usulan ATK</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('super-user/atk/usulan/daftar/seluruh-usulan') }}">Daftar Usulan</a></li>
                    <li class="breadcrumb-item active">Proses Persetujuan Usulan ATK</li>
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
                <h3 class="card-title">Persetujuan Usulan ATK </h3>
            </div>
            <form action="{{ url('super-user/atk/usulan/proses-diterima/'. $usulan->id_form_usulan) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No. Surat Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control text-uppercase" name="no_surat_usulan" value="{{ $usulan->no_surat_usulan }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pengusul</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="{{ $usulan->nama_pegawai }}" readonly>
                        </div>
                        <label class="col-sm-2 col-form-label">Jabatan</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="{{ $usulan->keterangan_pegawai }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Usulan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\carbon::parse($usulan->tanggal_usulan)->isoFormat('HH:mm / DD MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bulan Pengadaan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('MMMM Y') }}" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Barang</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="{{ $usulan->total_pengajuan }} Barang" readonly>
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <label class="col-sm-2 col-form-label">Informasi Barang</label>
                        <div class="col-sm-12">
                            <table class="table table-bordered text-center">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width: 2%;" class="pb-4">No</th>
                                        <th style="width: 20%;" class="pb-4">Nama Barang</th>
                                        <th style="width: 25%;" class="pb-4">Merk/Tipe</th>
                                        <th style="width: 14%;" class="pb-4">Jumlah</th>
                                        <th class="pb-4">Satuan</th>
                                        <th>
                                            Status <br>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th style="width: 20%;" class="pb-4">Keterangan</th>
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
                                            {{ $dataPengadaan->jenis_barang }} <br> {{ $dataPengadaan->nama_barang }}
                                        </td>
                                        <td>{{ $dataPengadaan->spesifikasi }}</td>
                                        <td>
                                            <input type="number" class="text-center form-control form-control-sm" name="jumlah_pengajuan[]" value="{{ $dataPengadaan->jumlah }}">
                                        </td>
                                        <td>{{ $dataPengadaan->satuan }}</td>
                                        <td class="text-center">
                                            <input type="hidden" value="ditolak" name="konfirmasi_atk[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" name="konfirmasi_atk[{{$i}}]" id="checkbox_id{{$i}}" value="diterima"> <br>
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
                                        <td class="pt-4"> {{ $i + 1 }}</td>
                                        <td class="pt-4">
                                            <input type="hidden" name="modul" value="distribusi">
                                            <input type="hidden" name="id_permintaan[]" value="{{ $dataPermintaan->id_permintaan }}">
                                            {{ $dataPermintaan->nama_barang }}
                                        </td>
                                        <td class="pt-4">{{ $dataPermintaan->spesifikasi }}</td>
                                        <td class="pt-3">
                                            <input type="number" class="text-center form-control form-control-sm" name="jumlah_pengajuan[]" value="{{ $dataPermintaan->jumlah }}" max="{{ $dataPermintaan->jumlah }}">
                                        </td>
                                        <td class="pt-4">{{ $dataPermintaan->satuan }}</td>
                                        <td class="text-center pt-3">
                                            <input type="hidden" value="ditolak" name="konfirmasi_atk[{{$i}}]">
                                            <input type="checkbox" class="confirm-check" name="konfirmasi_atk[{{$i}}]" id="checkbox_id{{$i}}" value="diterima"> <br>
                                            <label for="checkbox_id{{$i}}">Diterima</label>
                                        </td>
                                        <td>
                                            <textarea name="keterangan[]" class="form-control form-control-sm" style="font-size: 13px;"></textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-success" id="btnSubmit" onclick="return confirm('Pengajuan Diterima ?')">
                        <i class="fas fa-check-circle"></i> Terima
                    </button>
                    @if (Auth::user()->id == 5)
                    <a class="btn btn-danger" data-toggle="modal" data-target="#tolakUsulan">
                        <i class="fas fa-times-circle"></i> Tolak
                    </a>
                    @endif
                </div>
            </form>
            <!-- Modal -->
            <div class="modal fade" id="tolakUsulan" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ url('super-user/atk/usulan/proses-ditolak/'. $usulan->id_form_usulan) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <label class="col-form-label">Keterangan Penolakan</label>
                                <textarea name="keterangan" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Pengajuan Ditolak ?')">
                                    <i class="fas fa-times-circle"></i> Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('js')
<script>
    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    // Jumlah Kendaraan
    $(function() {
        $('#selectAll').click(function() {
            if ($(this).prop('checked')) {
                $('.confirm-check').prop('checked', true);
            } else {
                $('.confirm-check').prop('checked', false);
            }
        })
    })
</script>
@endsection

@endsection
