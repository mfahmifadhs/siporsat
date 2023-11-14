<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $usulan->id_form_usulan }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist_admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist_admin/css/adminlte.min.css') }}">
    <style>
        @media print {
            .pagebreak {
                page-break-after: always;
            }
        }

        .divTable {
            border-top: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            font-size: 16px;
        }

        .divThead {
            border-bottom: 1px solid;
            font-weight: bold;
        }

        .divTbody {
            border-bottom: 1px solid;
            text-transform: capitalize;
        }

        .divTheadtd {
            border-right: 1px solid;
        }

        .divTbodytd {
            padding: 10px;
        }

        .table-data {
            border: 1px solid;
            font-size: 16px;
        }

        .table-data th,
        .table-data td {
            border: 1px solid;
        }

        .table-data thead th,
        .table-data thead td {
            border: 1px solid;
        }

        table.table-borderless {
            border-top: 1px solid;
            border-right: 1px solid;
            border-left: 1px solid;
            font-size: 13px;
        }

        table.table-borderless tr {
            border-bottom: 1px solid;
        }

        table.table-borderless td.col-row {
            border-right: 1px solid;
        }

    </style>
</head>

<body style="font-family: Arial;margin: 20px;">
    <table class="text-center" style="width: 100%;">
        <tr>
            <td><img id="logo1" class="w-24" alt="" style="width: 120px;"></td>
            <td>
                <b class="font-weight-bold" style="font-size: 18px;">KEMENTERIAN KESEHATAN REPUBLIK INDONESIA</b><br>
                <b class="h6 font-weight-bold">SEKRETARIAT JENDERAL</b><br>
                <h6 class="text-xs">
                    Jalan H.R. Rasuna Said Blok X.5 Kavling 4-9 Jakarta Selatan 12950 <br>
                    Telepon (021) 5201590 <i>(Hunting)</i>
                </h6>
            </td>
            <td><img id="logo2" class="w-24" alt="" style="width: 100px;"></td>
        </tr>
    </table>
    <hr style="border: solid 2px;">
    <hr style="border: solid 0.5px;margin-top: -14px;">
    <div class="row" style="font-size: 15px;">
        <div class="col-md-9">
            <div class="form-group row">
                <div class="col-md-2">Nomor</div>
                <div class="col-md-10 text-uppercase">: {{ $usulan->no_surat_usulan }}</div>
                <div class="col-md-2">Hal</div>
                <div class="col-md-10 text-capitalize">:
                    @if ($modul == 'usulan-oldat')
                    {{ $usulan->jenis_form }} barang
                    @elseif ($modul == 'usulan-aadb')
                    {{ ucfirst(strtolower($usulan->jenis_form_usulan)) }} kendaraan
                    @elseif ($modul == 'usulan-atk')
                    {{ $usulan->jenis_form }} ATK
                    @elseif ($modul == 'usulan-gdn')
                    pemeliharaan gedung dan bangunan
                    @elseif ($modul == 'usulan-ukt')
                    permintaan kerumahtanggaan
                    @endif
                </div>
                <div class="col-md-2 mt-4">Pengusul</div>
                <div class="col-md-10 text-capitalize mt-4">: {{ ucfirst(strtolower($usulan->nama_pegawai)) }} </div>
                <div class="col-md-2">Jabatan</div>
                <div class="col-md-10">: {{ $usulan->keterangan_pegawai }} </div>
                <div class="col-md-2">Unit Kerja</div>
                <div class="col-md-10 text-capitalize">: {{ ucfirst(strtolower($usulan->unit_kerja)) }} </div>
                <div class="col-md-2">Jumlah</div>
                <div class="col-md-10 text-capitalize">:
                    {{ $usulan->total_pengajuan }}
                    @if ($modul == 'usulan-oldat' || $modul == 'usulan-atk')
                    barang
                    @elseif ($modul == 'usulan-aadb')
                    kendaraan
                    @elseif ($modul == 'usulan-gdn' || $modul == 'usulan-ukt')
                    pekerjaan
                    @endif
                </div>
                @if($usulan->rencana_pengguna != null)
                <div class="col-md-2">Keterangan</div>
                <div class="col-md-10 text-capitalize">:
                    {{ $usulan->rencana_pengguna }}
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-3 text-right">
            <div class="col-md-12">{{ \Carbon\Carbon::parse($usulan->tanggal_usulan)->isoFormat('DD MMMM Y') }}</div>
        </div>
        <div class="col-12 table-responsive mt-4 mb-5">
            @if ($modul == 'usulan-oldat')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Kode Barang</td>
                    <td class="col-row">Nama Barang</td>
                    <td class="col-row">Merk/Tipe</td>
                    @if($usulan->jenis_form == 'pengadaan')
                    <td class="col-row">Spesifikasi</td>
                    <td class="col-row">Jumlah</td>
                    <td class="">Estimasi Biaya</td>
                    @else
                    <td class="col-row">Tahun</td>
                    <td class="">Keterangan</td>
                    @endif
                </tr>
                <?php $no = 1; ?>
                @if($usulan->jenis_form == 'pengadaan')
                    @foreach($usulan->detailPengadaan as $dataBarang)
                    <tr>
                        <td class="col-row">{{ $no++ }}</td>
                        <td class="col-row">{{ $dataBarang->kategori_barang_id }}</td>
                        <td class="col-row">{{ $dataBarang->kategori_barang }}</td>
                        <td class="col-row">{{ $dataBarang->merk_barang }}</td>
                        <td class="col-row">{!! nl2br(e($dataBarang->spesifikasi_barang)) !!}</td>
                        <td class="col-row">{{ $dataBarang->jumlah_barang.' '.$dataBarang->satuan_barang }}</td>
                        <td class="">Rp {{ number_format($dataBarang->estimasi_biaya, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                @else
                    @foreach($usulan->detailPerbaikan as $dataBarang)
                    <tr>
                        <td class="col-row">{{ $no++ }}</td>
                        <td class="col-row">{{ $dataBarang->kode_barang.'.'.$dataBarang->nup_barang }}</td>
                        <td class="col-row">{{ $dataBarang->kategori_barang }}</td>
                        <td class="col-row">{{ $dataBarang->merk_tipe_barang }}</td>
                        <td class="col-row">{{ $dataBarang->tahun_perolehan }}</td>
                        <td>{{ $dataBarang->keterangan_perbaikan }}</td>
                    </tr>
                    @endforeach
                @endif
            </table>
            @elseif ($modul == 'usulan-atk')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Nama Barang</td>
                    <td class="col-row">Merk/Tipe</td>
                    <td class="col-row">Permintaan</td>
                    <td class="col-row">Disetujui</td>
                    <td class="">Keterangan</td>
                </tr>
                <?php $no = 1; ?>
                @if ($form->jenis_form == 'pengadaan')
                    @foreach($usulan->pengadaanAtk as $dataAtk)
                    <tr>
                        <td class="col-row">{{ $no++ }}</td>
                        <td class="col-row">{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                        <td class="col-row">{{ $dataAtk->spesifikasi }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                        <td>
                            {{ $dataAtk->status }}
                            @if ($dataAtk->keterangan != null)
                            ({{ $dataAtk->keterangan }})
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @elseif ($form->jenis_form == 'distribusi')
                    @foreach($usulan->permintaanAtk as $dataAtk)
                    <tr>
                        <td class="col-row">{{ $no++ }}</td>
                        <td class="col-row">{{ $dataAtk->jenis_barang }} <br> {{ $dataAtk->nama_barang }}</td>
                        <td class="col-row">{{ $dataAtk->spesifikasi }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan }}</td>
                        <td>
                            {{ $dataAtk->status }}
                            @if ($dataAtk->keterangan != null)
                            ({{ $dataAtk->keterangan }})
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    @foreach($usulan->permintaan2Atk as $dataAtk)
                    <tr>
                        <td class="col-row">{{ $no++ }}</td>
                        <td class="col-row">{{ $dataAtk->deskripsi_barang }}</td>
                        <td class="col-row">{{ $dataAtk->catatan }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah.' '. $dataAtk->satuan_barang }}</td>
                        <td class="col-row">{{ (int) $dataAtk->jumlah_disetujui.' '. $dataAtk->satuan_barang }}</td>
                        <td>
                            {{ $dataAtk->status }}
                            @if ($dataAtk->keterangan != null)
                            ({{ $dataAtk->keterangan }})
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
            @elseif ($modul == 'usulan-gdn')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row" style="width: 0%;">No</td>
                    <td class="col-row" style="width: 25%;">Bidang Kerusakan</td>
                    <td class="col-row" style="width: 25%;">Lokasi Perbaikan</td>
                    <td class="col-row" style="width: 25%;">Lokasi Spesifik</td>
                    <td class="" style="width: 25%;">Keterangan</td>
                </tr>
                @foreach($usulan->detailUsulanGdn as $i => $dataGdn)
                <tr>
                    <td class="col-row">{{ $i + 1 }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataGdn->bid_kerusakan)) }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataGdn->lokasi_bangunan)) }}</td>
                    <td class="col-row text-left">{!! nl2br(e($dataGdn->lokasi_spesifik )) !!}</td>
                    <td class="text-left">{!! nl2br(e($dataGdn->keterangan )) !!}</td>
                </tr>
                @endforeach
            </table>
            @elseif ($modul == 'usulan-ukt')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row" style="width: 0%;">No</td>
                    <td class="col-row" style="width: 30%;">Pekerjaan</td>
                    <td class="col-row" style="width: 50%;">Spesifikasi Pekerjaan</td>
                    <td class="" style="width: 20%;">Keterangan</td>
                </tr>
                @foreach($usulan->detailUsulanUkt as $i => $dataUkt)
                <tr>
                    <td class="col-row">{{ $i + 1 }}</td>
                    <td class="col-row text-left">{{ ucfirst(strtolower($dataUkt->lokasi_pekerjaan)) }}</td>
                    <td class="col-row text-left">{!! nl2br(e($dataUkt->spesifikasi_pekerjaan)) !!}</td>
                    <td class="text-left">{!! nl2br(e($dataUkt->keterangan)) !!}</td>
                </tr>
                @endforeach
            </table>
            @elseif ($modul == 'usulan-aadb')
            @if($usulan->jenis_form == '1')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Jenis AADB</td>
                    <td class="col-row">Jenis Kendaraan</td>
                    <td class="col-row">Merk/Tipe</td>
                    <td class="col-row">Kualifikasi</td>
                    <td class="col-row">Jumlah</td>
                    <td class="">Tahun</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanKendaraan as $dataKendaraan)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->jenis_aadb)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->jenis_kendaraan)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataKendaraan->merk_tipe_kendaraan)) }}</td>
                    <td class="col-row">Kendaraan {{ ucfirst(strtolower($dataKendaraan->kualifikasi)) }}</td>
                    <td class="col-row">{{ $dataKendaraan->jumlah_pengajuan }} UNIT</td>
                    <td class="">{{ $dataKendaraan->tahun_kendaraan }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '2')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="col-row">Jadwal Servis</td>
                    <td class="col-row">Jadwal Ganti Oli</td>
                    <td class="">Keterangan</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanServis as $dataServis)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row text-left">
                        {{ strtoupper($dataServis->no_plat_kendaraan) }} <br>
                        {{ ucfirst(strtolower($dataServis->merk_tipe_kendaraan)) }} <br>
                        Kilometer Terakhir : {{ (int) $dataServis->kilometer_terakhir }} Km
                    </td>
                    <td class="col-row text-left">
                        Terakhir Servis : <br>
                        {{ \Carbon\carbon::parse($dataServis->tgl_servis_terakhir)->isoFormat('DD MMMM Y') }} <br>
                        Jatuh Tempo Servis : <br>
                        {{ (int) $dataServis->jatuh_tempo_servis }} Km
                    </td>
                    <td class="col-row text-left">
                        Terakhir Ganti Oli : <br>
                        {{ \Carbon\carbon::parse($dataServis->tgl_ganti_oli_terakhir)->isoFormat('DD MMMM Y') }} <br>
                        Jatuh Tempo Servis : <br>
                        {{ (int) $dataServis->jatuh_tempo_ganti_oli }} Km
                    </td>
                    <td>{{ $dataServis->keterangan_servis }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '3')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">No. Plat</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="col-row">Pengguna</td>
                    <td class="">Masa Berlaku STNK</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($usulan->usulanSTNK as $dataSTNK)
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ strtoupper($dataSTNK->no_plat_kendaraan) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataSTNK->merk_tipe_kendaraan)) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataSTNK->pengguna)) }}</td>
                    <td class="">{{ \Carbon\Carbon::parse($dataSTNK->mb_stnk_lama)->isoFormat('DD MMMM Y') }}</td>
                </tr>
                @endforeach
            </table>
            @elseif($usulan->jenis_form == '4')
            <table class="table table-borderless text-center">
                <tr class="font-weight-bold">
                    <td class="col-row">No</td>
                    <td class="col-row">Bulan Pengadaan</td>
                    <td class="col-row">Jenis AADB</td>
                    <td class="col-row">No. Plat</td>
                    <td class="col-row">Kendaraan</td>
                    <td class="">Kualifikasi</td>
                <?php $no = 1; ?>
                @foreach($usulan->usulanVoucher as $dataVoucher)
                @if($dataVoucher->status_pengajuan == 'true')
                <tr>
                    <td class="col-row">{{ $no++ }}</td>
                    <td class="col-row">{{ \Carbon\Carbon::parse($dataVoucher->bulan_pengadaan)->isoFormat('MMMM Y') }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataVoucher->jenis_aadb)) }}</td>
                    <td class="col-row">{{ strtoupper($dataVoucher->no_plat_kendaraan) }}</td>
                    <td class="col-row">{{ ucfirst(strtolower($dataVoucher->merk_tipe_kendaraan)) }}</td>
                    <td class="">{{ ucfirst(strtolower($dataVoucher->kualifikasi)) }}</td>
                 </tr>
                @endif
                @endforeach
            </table>
            @endif
            @endif
        </div>
        <div class="col-md-12 mt-5" style="font-size: 14px;">
            <div class="col-md-12 text-capitalize">
                <div class="row text-center">
                    <label class="col-sm-6">Yang Mengusulkan, <br> {{ ucfirst(strtolower($usulan->keterangan_pegawai)) }}</label>
                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                    <label class="col-sm-6">Disetujui Oleh, <br> {{ ucfirst(strtolower($pimpinan->keterangan_pegawai)) }}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_pengusul) !!}</label>
                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null)
                    <label class="col-sm-6">{!! QrCode::size(100)->generate('https://siporsat.kemkes.go.id/surat/'.$modul.'/'.$usulan->otp_usulan_kabag) !!}</label>
                    @endif
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="row text-center">
                    <label class="col-sm-6">{{ $usulan->nama_pegawai }}</label>
                    @if ($usulan->otp_usulan_kabag != null || $usulan->otp_usulan_pimpinan != null )
                    <label class="col-sm-6">{{ $pimpinan->nama_pegawai }}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->

    <script>
        function toDataURL(src, callback, outputFormat) {
            var img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                canvas.height = this.height;
                canvas.width = this.width;
                ctx.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL(outputFormat || 'image/png');
                callback(dataURL);
                canvas = null;
            };
            img.src = src;
        }

        toDataURL('https://i.ibb.co/Ws42B2m/logo-kemenkes-icon.png', function(dataUrl) {
            document.getElementById('logo1').src = dataUrl;
        });

        toDataURL('https://i.ibb.co/gyB7drd/logo-germas.png', function(dataUrl) {
            document.getElementById('logo2').src = dataUrl;
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            const element = document.body;
            html2pdf(element, {
                filename: 'cetak.pdf',
                html2canvas: {
                    scale: 2
                },
                margin: 6,
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                output: 'blob'

            }).then(function(pdfBlob) {
                const pdfUrl = URL.createObjectURL(pdfBlob);
                const downloadLink = document.createElement('a');
                downloadLink.href = pdfUrl;
                downloadLink.download = 'cetak.pdf';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            });
        });
    </script>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>


</body>

</html>
	
