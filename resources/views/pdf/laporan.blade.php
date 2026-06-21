<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Rekapitulasi {{ ucfirst($jenis) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
        
        @page { 
            size: A4 landscape; 
        }

        body {
            font-size: 9pt;
            line-height: 1.4; 
            color: #1e293b;
            background: #fff;
            margin: 0.5in 0.6in 0.6in 0.6in;
        }

        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .header-logo { height: 65px; width: auto; vertical-align: top; }
        .header-title-box { text-align: right; vertical-align: top; }
        .main-title { font-size: 16pt; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px; }
        .sub-title { font-size: 9pt; color: #64748b; margin-top: 2px; }

        .infobar-table { width: 100%; border-collapse: collapse; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 20px; }
        .infobar-table td { padding: 10px 14px; font-size: 8.5pt; color: #334155; }
        .infobar-label { color: #64748b; text-transform: uppercase; font-size: 7.5pt; font-weight: bold; display: block; margin-bottom: 2px; }
        .infobar-value { color: #0f172a; }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .data-table th { background: #1e3a8a; color: #ffffff; font-size: 8pt; font-weight: bold; text-transform: uppercase; padding: 9px 10px; text-align: left; border: 1px solid #1e3a8a; }
        .data-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; color: #334155; vertical-align: middle; }
        .data-table tr:nth-child(even) td { background: #f8fafc; }
        
        .badge { display: inline-block; padding: 2px 6px; font-size: 7.5pt; font-weight: bold; border-radius: 4px; text-transform: uppercase; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef9c3; color: #a16207; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; }
        .badge-info    { background: #e0f2fe; color: #0369a1; }

        .text-right { text-align: right; }

        .footer-container { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; width: 250px; text-align: center; font-size: 9pt; }
        .signature-space { height: 65px; }
        .signature-line { font-weight: bold; text-decoration: underline; text-transform: uppercase; color: #0f172a; }
        .signature-sub { color: #64748b; font-size: 8pt; margin-top: 2px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <img src="{{ public_path('assets/logo/pln-up3.png') }}" class="header-logo" alt="Logo">
            </td>
            <td class="header-title-box">
                <div class="main-title">Laporan {{ ucfirst($jenis) }} Siaga</div>
                <div class="sub-title">Sistem Informasi Pengelolaan Peralatan Siaga (SiapSiaga)</div>
            </td>
        </tr>
    </table>

    <table class="infobar-table">
        <tr>
            <td style="width: 50%; border: 1px solid #e2e8f0;">
                <span class="infobar-label">Kategori Dokumen</span>
                <span class="infobar-value"><strong>Rekapitulasi Data {{ ucfirst($jenis) }}</strong></span>
            </td>
            <td style="width: 50%; border: 1px solid #e2e8f0;">
                <span class="infobar-label">Waktu Cetak Berkas</span>
                <span class="infobar-value"><strong>{{ $date }}</strong></span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <!-- LAPORAN DATA INVENTARIS PERALATAN -->
        @if($jenis === 'peralatan')
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Nomor Seri</th>
                    <th style="width: 25%;">Jenis Peralatan</th>
                    <th style="width: 15%;">Kapasitas</th>
                    <th style="width: 20%;">Lokasi</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->nomor_seri }}</td>
                        <td>{{ $row->jenis?->nama_jenis }}</td>
                        <td>{{ $row->kapasitas ? number_format($row->kapasitas, fmod($row->kapasitas, 1) == 0 ? 0 : 1, ',', '.') : '—' }} {{ $row->satuan ?? '' }}</td>
                        <td>{{ $row->lokasi ?? '—' }}</td>
                        <td>
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
                    <tr><td colspan="6" style="text-align: center; color: #94a3b8;">Belum ada arsip data peralatan.</td></tr>
                @endforelse
            </tbody>

        <!-- LAPORAN HISTORI PEMELIHARAAN (MAINTENANCE) -->
        @elseif($jenis === 'pemeliharaan')
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nomor Pemeliharaan</th>
                    <th style="width: 15%;">Alat</th>
                    <th style="width: 35%;">Deskripsi Perbaikan</th>
                    <th style="width: 20%;">Tanggal Pemeliharaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->nomor_pemeliharaan }}</td>
                        <td>{{ $row->peralatan?->jenis?->nama_jenis }} — {{ $row->peralatan?->nomor_seri }}</td>
                        <td style="color: #475569; font-size: 8.5pt;">{{ $row->keterangan ?? '—' }}</td>
                        <td>{{ $row->tanggal_pemeliharaan->translatedFormat('d F Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align: center; color: #94a3b8;">Belum ada arsip transaksi pemeliharaan aset.</td></tr>
                @endforelse
            </tbody>
        <!-- LAPORAN TRANSAKSI PEMINJAMAN -->
        @else
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Rincian</th>
                    <th style="width: 30%;">Tujuan Keperluan</th>
                    <th style="width: 30%;">Rencana Durasi</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->nomor_peminjaman }}<br>{{ $row->pengguna?->nama_lengkap }}<br>{{ $row->pengguna?->unit }}</td>
                        <td style="color: #475569; font-size: 8.5pt;">{{ $row->tujuan_keperluan }}</td>
                        <td>{{ $row->tgl_rencana_pinjam?->translatedFormat('d M Y') . '—' . $row->tgl_rencana_kembali?->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($row->status === 'Selesai')
                                <span class="badge badge-success" style="margin-right: 10px;">Selesai</span>
                                @if ($row->tgl_realisasi_kembali <= $row->tgl_rencana_kembali)
                                    <span class="badge badge-success">Tepat Waktu</span>
                                @else
                                    <span class="badge badge-danger">Terlambat</span>
                                @endif
                            @elseif($row->status === 'Sedang Dipinjam')
                                <span class="badge badge-warning">Aktif</span>
                            @elseif(str_contains(strtolower($row->status), 'diajukan'))
                                <span class="badge badge-info">Pending</span>
                            @else
                                <span class="badge badge-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; color: #94a3b8;">Belum ada arsip riwayat sirkulasi peminjaman.</td></tr>
                @endforelse
            </tbody>
        @endif
    </table>
</body>
</html>