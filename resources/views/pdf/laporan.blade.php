<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi {{ ucfirst($jenis) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
        
        @page { size: A4 landscape; }

        body { font-size: 8.5pt; margin: 0.4in 0.5in 0.5in 0.5in; line-height: 1.4; color: #334155; background: #fff; }

        /* HEADER BRANDING */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border-bottom: 2px solid #0f172a; padding-bottom: 10px; }
        .header-logo { height: 50px; width: auto; vertical-align: middle; }
        .header-title-box { text-align: right; vertical-align: middle; }
        .main-title { font-size: 15pt; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; }
        .sub-title { font-size: 8.5pt; color: #64748b; margin-top: 1px; }

        /* TOP METADATA INFOBAR */
        .infobar-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .infobar-table td { padding: 8px 12px; font-size: 8pt; background: #f8fafc; border: 1px solid #e2e8f0; }
        .infobar-label { color: #64748b; text-transform: uppercase; font-size: 7pt; font-weight: bold; display: block; margin-bottom: 1px; }
        .infobar-value { color: #0f172a; font-forward: bold; }

        /* ANALYTICAL SUMMARY CARDS (PDF VERSION) */
        .summary-grid { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-left: -8px; margin-right: -8px; margin-bottom: 15px; }
        .summary-card { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; padding: 8px 12px; text-align: left; }
        .summary-card-title { font-size: 7pt; uppercase; color: #64748b; font-weight: bold; tracking-spacing: 0.3px; display: block; }
        .summary-card-value { font-size: 13pt; font-weight: bold; color: #1e3a8a; margin-top: 2px; display: block; }

        /* DATA TABLE METRICS */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 5px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .data-table th { background: #0f172a; color: #ffffff; font-size: 7.5pt; font-weight: bold; text-transform: uppercase; padding: 8px 10px; text-align: left; letter-spacing: 0.3px; border: 1px solid #0f172a; }
        .data-table td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; border-left: 1px solid #f1f5f9; border-right: 1px solid #f1f5f9; color: #334155; vertical-align: middle; }
        .data-table tr:nth-child(even) td { background: #f8fafc; }
        .font-mono-pdf { font-family: Courier, monospace; font-size: 8pt; font-weight: bold; color: #0f172a; }
        
        /* MODERN CLEAN BADGES */
        .badge { display: inline-block; padding: 2px 6px; font-size: 7pt; font-weight: bold; border-radius: 4px; text-transform: uppercase; text-align: center; }
        .badge-success { background: #e2fbe8; color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .badge-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-info    { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }

        /* SIGNATURE SECTION */
        .footer-container { margin-top: 35px; width: 100%; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-box { width: 220px; text-align: center; font-size: 8.5pt; color: #334155; }
        .signature-space { height: 55px; }
        .signature-line { font-weight: bold; text-decoration: underline; color: #0f172a; text-transform: uppercase; }
        .signature-sub { color: #64748b; font-size: 7.5pt; margin-top: 2px; }
    </style>
</head>
<body>
    
    {{-- CORPORATE HEADER --}}
    <table class="header-table">
        <tr>
            <td style="padding-bottom: 5px;">
                <img src="{{ public_path('assets/logo/pln-up3.png') }}" class="header-logo" alt="Logo Perusahaan">
            </td>
            <td class="header-title-box">
                <div class="main-title">Laporan Rekapitulasi {{ ucfirst($jenis) }}</div>
                <div class="sub-title">Sistem Informasi Manajemen Aset Unit Siaga (SiapSiaga)</div>
            </td>
        </tr>
    </table>

    {{-- DYNAMIC METADATA INFORMATION BAR --}}
    <table class="infobar-table">
        <tr>
            <td style="width: 35%;">
                <span class="infobar-label">Klasifikasi Dokumen</span>
                <span class="infobar-value"><strong>Berkas Historis Kesiapan Alat Siaga</strong></span>
            </td>
            <td style="width: 35%;">
                <span class="infobar-label">Metode Penarikan Data</span>
                <span class="infobar-value"><strong>Data Komprehensif (All Records)</strong></span>
            </td>
            <td style="width: 30%;">
                <span class="infobar-label">Tanggal Cetak Ekspor</span>
                <span class="infobar-value"><strong>{{ $date }}</strong></span>
            </td>
        </tr>
    </table>

    {{-- DYNAMIC ANALYTICAL SUMMARY GRID CARDS --}}
    <table class="summary-grid">
        <tr>
            @if($jenis === 'peralatan')
                <td><div class="summary-card"><span class="summary-card-title">TOTAL ASET FISIK</span><span class="summary-card-value">{{ \App\Models\Peralatan::count() }} Unit</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">KONDISI TERSEDIA (READY)</span><span class="summary-card-value" style="color: #166534;">{{ \App\Models\Peralatan::where('status', \App\Models\Peralatan::STATUS_TERSEDIA)->count() }} Unit</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">SEDANG DIOPERASIKAN (DIPINJAM)</span><span class="summary-card-value" style="color: #854d0e;">{{ \App\Models\Peralatan::where('status', \App\Models\Peralatan::STATUS_DIPINJAM)->count() }} Unit</span></div></td>
            @elseif($jenis === 'pemeliharaan')
                <td><div class="summary-card"><span class="summary-card-title">TOTAL LOG TINDAKAN</span><span class="summary-card-value">{{ \App\Models\Pemeliharaan::count() }} Berkas</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">TINDAKAN PREVENTIF</span><span class="summary-card-value" style="color: #0369a1;">{{ \App\Models\Pemeliharaan::where('jenis_pemeliharaan', 'Preventif')->count() }} Tindakan</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">TINDAKAN KOREKTIF</span><span class="summary-card-value" style="color: #991b1b;">{{ \App\Models\Pemeliharaan::where('jenis_pemeliharaan', 'Korektif')->count() }} Tindakan</span></div></td>
            @else
                <td><div class="summary-card"><span class="summary-card-title">TOTAL TRANSAKSI KELUAR-MASUK</span><span class="summary-card-value">{{ \App\Models\Peminjaman::count() }} Berkas</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">DURASI PINJAM AKTIF</span><span class="summary-card-value" style="color: #854d0e;">{{ \App\Models\Peminjaman::where('status', \App\Models\Peminjaman::DIPINJAM)->count() }} Transaksi</span></div></td>
                <td><div class="summary-card"><span class="summary-card-title">ARSIP PINJAM SELESAI</span><span class="summary-card-value" style="color: #166534;">{{ \App\Models\Peminjaman::where('status', \App\Models\Peminjaman::SELESAI)->count() }} Arsip</span></div></td>
            @endif
        </tr>
    </table>

    {{-- MAIN RENDER DATA TABLE --}}
    <table class="data-table">
        @if($jenis === 'peralatan')
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 20%;">Nomor Seri Unit</th>
                    <th style="width: 25%;">Kategori Alat</th>
                    <th style="width: 15%;">Kapasitas Batas</th>
                    <th style="width: 20%;">Lokasi Penempatan</th>
                    <th style="width: 15%; text-align: center;">Status Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td style="text-align: center; color: #94a3b8;">{{ $index + 1 }}</td>
                        <td class="font-mono-pdf">{{ $row->nomor_seri }}</td>
                        <td>{{ $row->jenis?->nama_jenis }}</td>
                        <td>{{ $row->kapasitas ? number_format($row->kapasitas, fmod($row->kapasitas, 1) == 0 ? 0 : 1, ',', '.') : '—' }} {{ $row->satuan ?? '' }}</td>
                        <td>{{ $row->lokasi ?? '—' }}</td>
                        <td style="text-align: center;">
                            @if($row->status === 'Tersedia')
                                <span class="badge badge-success">Tersedia</span>
                            @elseif($row->status === 'Dipinjam')
                                <span class="badge badge-warning">Dipinjam</span>
                            @elseif($row->status === 'Maintenance')
                                <span class="badge badge-info">Maintenance</span>
                            @else
                                <span class="badge badge-danger">Rusak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">Belum tersedia arsip inventaris peralatan siaga dalam sistem.</td></tr>
                @endforelse
            </tbody>

        @elseif($jenis === 'pemeliharaan')
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 20%;">Nomor Log</th>
                    <th style="width: 25%;">Nama Alat & Serial</th>
                    <th style="width: 35%;">Deskripsi Rincian Perbaikan</th>
                    <th style="width: 15%;">Tanggal Eksekusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td style="text-align: center; color: #94a3b8;">{{ $index + 1 }}</td>
                        <td class="font-mono-pdf">{{ $row->nomor_pemeliharaan }}</td>
                        <td><strong>{{ $row->peralatan?->jenis?->nama_jenis }}</strong><br><span style="font-size: 7.5pt; color: #64748b;">S/N: {{ $row->peralatan?->nomor_seri }}</span></td>
                        <td style="color: #475569; font-size: 8pt;">{{ $row->deskripsi ?? '—' }}</td>
                        <td>{{ $row->tanggal_pemeliharaan->translatedFormat('d F Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Belum tersedia berkas log tindakan pemeliharaan peralatan.</td></tr>
                @endforelse
            </tbody>

        @else
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th style="width: 22%;">Nomor Dokumen & Peminjam</th>
                    <th style="width: 28%;">Tujuan Peruntukan Keperluan</th>
                    <th style="width: 30%;">Rencana Durasi Penugasan</th>
                    <th style="width: 15%; text-align: center;">Status Berkas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td style="text-align: center; color: #94a3b8;">{{ $index + 1 }}</td>
                        <td>
                            <span class="font-mono-pdf">{{ $row->nomor_peminjaman }}</span><br>
                            <strong>{{ $row->nama_pengguna }}</strong><br>
                            <span style="font-size: 7.5pt; color: #64748b;">{{ $row->unit }}</span>
                        </td>
                        <td style="color: #475569; font-size: 8pt;">{{ $row->tujuan_keperluan }}</td>
                        <td>{{ $row->tgl_pinjam?->translatedFormat('d M Y') }} s.d {{ $row->tgl_kembali?->translatedFormat('d M Y') }}</td>
                        <td style="text-align: center;">
                            @if($row->status === 'Selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">Aktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Belum tersedia transaksi sirkulasi peminjaman dalam berkas.</td></tr>
                @endforelse
            </tbody>
        @endif
    </table>
</body>
</html>