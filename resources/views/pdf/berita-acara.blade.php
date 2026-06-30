<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BA {{ $ba->jenis_ba }} {{ $ba->nomor_ba }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt; line-height: 1.5; color: #000; background: #fff;
            margin: 0.6in 1in 1in 1in;
        }
        .judul-box { text-align: center; margin-bottom: 15px; }
        .judul-ba  { font-size: 13pt; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .nomor-ba  { font-size: 11pt; margin-top: 1px; }
        .kop-divider { border: none; border-top: 3px solid #000; border-bottom: 1px solid #000; height: 4px; margin: 15px 0; }
        .teks-pembuka, .paragraf-narasi { text-align: justify; margin: 10px 0; text-indent: 40px; }
        .pihak-container { margin-bottom: 5px; }
        .pihak-title { font-weight: bold; float: left; width: 25px; }
        .pihak-table { border-collapse: collapse; margin-left: 25px; width: calc(100% - 25px); }
        .pihak-table td { padding: 2px 4px; vertical-align: top; font-size: 11pt; }
        .pihak-table td.label { width: 120px; }
        .pihak-table td.sep   { width: 15px; text-align: center; }
        .pihak-sebutan { text-align: center; margin: 5px 0 15px 0; }
        .tempat-tanggal-box { width: 100%; margin-top: 20px; }
        .tempat-tanggal { float: right; width: 50%; text-align: center; }
        .ttd-table { width: 100%; border-collapse: collapse; clear: both; page-break-inside: avoid; }
        .ttd-table td { width: 50%; text-align: center; vertical-align: top; padding: 0 10px; }
        .ttd-label  { font-size: 11pt; font-weight: bold; }
        .ttd-jabatan { font-size: 11pt; line-height: 1.2; height: 40px; } 
        .ttd-qr-box  { margin: 5px auto; width: 80px; height: 80px; }
        .ttd-qr-box img { width: 100%; height: 100%; }
        .ttd-nama   { font-weight: bold; font-size: 11pt; text-transform: uppercase; margin-top: 5px; }
        .page-break { page-break-before: always; }
        .dokumen-title { font-size: 12pt; font-weight: bold; text-align: center; margin-bottom: 25px; text-transform: uppercase; }
        .foto-group { margin-bottom: 25px; page-break-inside: avoid; }
        .foto-grid { width: 100%; border-collapse: collapse; }
        .foto-grid td { width: 50%; text-align: center; padding: 6px; }
        .foto-wrapper { border: 1px solid #000; padding: 5px; display: inline-block; }
        .foto-img { width: 260px; height: 175px; object-fit: cover; }
    </style>
</head>
<body>
    <div style="text-align: right;">
        <img src="{{ public_path('assets/logo/pln-up3.png') }}" style="height:65px;" alt="Logo">
    </div>
    
    <div class="judul-box">
        <div class="judul-ba">
            BERITA ACARA<br>
            {{ $ba->jenis_ba === \App\Models\BeritaAcara::BA_PEMINJAMAN ? 'PINJAM PAKAI BARANG' : 'SERAH TERIMA KEMBALI BARANG' }}
        </div>
        <div class="nomor-ba">No. {{ $ba->nomor_ba }}</div>
    </div>
    <hr class="kop-divider">

    <div class="teks-pembuka">
        Pada hari ini <strong>{{ $ba->created_at->isoFormat('dddd') }}</strong>, 
        Tanggal <strong>{{ $ba->created_at->isoFormat('D MMMM Y') }}</strong> 
        ({{ $ba->created_at->format('d-m-Y') }}), kami yang bertanda tangan dibawah ini masing-masing:
    </div>

    <div class="pihak-container">
        <div class="pihak-title">1.</div>
        <table class="pihak-table">
            <tr><td class="label">Nama</td><td class="sep">:</td><td>{{ strtoupper('Reza Syahputra') }}</td></tr>
            <tr><td class="label">NIP</td><td class="sep">:</td><td>92141015ZY</td></tr>
            <tr><td class="label">Jabatan</td><td class="sep">:</td><td>Ph. Asman Jaringan dan Konstruksi</td></tr>
            <tr><td class="label">Unit</td><td class="sep">:</td><td>{{ strtoupper('UP3 Palembang') }}</td></tr>
        </table>
        <div class="pihak-sebutan">Selanjutnya disebut <strong>Pihak Pertama</strong></div>
    </div>

    <div class="pihak-container">
        <div class="pihak-title">2.</div>
        <table class="pihak-table">
            <tr><td class="label">Nama</td><td class="sep">:</td><td>{{ strtoupper($peminjaman->nama_pengguna ?? '—') }}</td></tr>
            <tr><td class="label">NIP</td><td class="sep">:</td><td>{{ $peminjaman->nip ?? '—' }}</td></tr>
            <tr><td class="label">Jabatan</td><td class="sep">:</td><td>{{ $peminjaman->jabatan ?? '—' }}</td></tr>
            <tr><td class="label">Unit</td><td class="sep">:</td><td>{{ strtoupper($peminjaman->unit ?? '—') }}</td></tr>
        </table>
        <div class="pihak-sebutan">Selanjutnya disebut <strong>Pihak Kedua</strong></div>
    </div>

    <div class="paragraf-narasi">
        @if($ba->jenis_ba === \App\Models\BeritaAcara::BA_PEMINJAMAN)
            Dalam rangka menunjang kelancaran tugas dan ketertiban administrasi, maka <strong>Pihak Pertama</strong> telah menyerahkan kepada <strong>Pihak Kedua</strong> berupa:
        @else
            Berdasarkan masa akhir pemakaian operasional siaga, maka <strong>Pihak Kedua</strong> telah menyerahkan kembali kepada <strong>Pihak Pertama</strong> selaku perwakilan manajemen gudang berupa berkas fisik unit:
        @endif

        @php
            $groupedDetails = $peminjaman->details->groupBy(function($detail) {
                return $detail->peralatan?->jenis?->nama_jenis ?? 'Alat';
            });
        @endphp

        @foreach($groupedDetails as $namaJenis => $items)
            @php
                $serialNumbers = $items->map(function($item) {
                    return $item->peralatan?->nomor_seri ?? '—';
                })->implode(', ');
            @endphp

            <strong>{{ $items->count() }} Unit {{ $namaJenis }} (S/N: {{ $serialNumbers }})</strong>@if(!$loop->last), dan @endif
        @endforeach 

        @if($ba->jenis_ba === \App\Models\BeritaAcara::BA_PEMINJAMAN)
            dalam kondisi <strong>BAIK</strong>.
        @else
            dalam kondisi <strong>LENGKAP DAN AMAN</strong>.
        @endif
    </div>

    @if($ba->jenis_ba === \App\Models\BeritaAcara::BA_PEMINJAMAN)
    <div class="paragraf-narasi">Selanjutnya segala biaya pemeliharaan, perbiakan dan sebagainya jika ada kerusakan ditanggung oleh <strong>Pihak Kedua</strong>, dan terhitung sejak <strong>Berita Acara Pinjam Pakai</strong> ini ditandatangani sampai selesai atau BA Pengembalian diterbitkan. Segala tugas atau kewajiban serta tanggung jawab atas pengolahan barang tersebut beralih dari <strong>Pihak Pertama</strong> kepada <strong>Pihak Kedua</strong>.</div>
    @endif

    <div class="paragraf-narasi" style="text-indent: 0;">Demikian Berita Acara ini dibuat untuk dipergunakan sebagaimana mestinya.</div>

    <div class="tempat-tanggal-box">
        <div class="tempat-tanggal">Palembang, {{ \Carbon\Carbon::parse($ba->created_at)->translatedFormat('d F Y') }}</div>
    </div>

    <table class="ttd-table">
        <tr>
            <td>
                <div class="ttd-label">Pihak Pertama</div>
                <div class="ttd-jabatan">Ph. Asman Jaringan dan Konstruksi<br>{{ strtoupper($peminjaman->unit ?? '—') }}</div>
                <div class="ttd-qr-box">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(\QrCode::format('svg')->size(80)->margin(0)->generate($verifyUrl)) }}">
                </div>
                <div class="ttd-nama">{{ strtoupper('Reza Syahputra') }}</div>
            </td>
            <td>
                <div class="ttd-label">Pihak Kedua</div>
                <div class="ttd-jabatan">{{ $peminjaman->jabatan ?? '—' }}<br>{{ $peminjaman->unit ?? '—' }}</div>
                <div class="ttd-qr-box">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(\QrCode::format('svg')->size(80)->margin(0)->generate($verifyUrl)) }}">
                </div>
                <div class="ttd-nama">{{ $peminjaman->nama_pengguna ?? '—' }}</div>
            </td>
        </tr>
    </table>

    @if($ba->dokumentasi->isNotEmpty())
        <div class="page-break"></div>
        <div class="dokumen-section">
            <div class="dokumen-title">DOKUMENTASI {{ strtoupper($ba->jenis_ba) }}</div>
            @foreach($ba->dokumentasi as $index => $doc)
                <div class="foto-group">
                    <div style="font-size:11pt; font-weight:bold; margin-bottom:10px;">{{ $index + 1 }}. {{ $doc->keterangan }}</div>
                    <table class="foto-grid">
                        <tr>
                            @foreach($doc->foto as $img)
                                <td>
                                    <div class="foto-wrapper">
                                        <img src="{{ storage_path('app/public/' . $img) }}" class="foto-img">
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>