<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function download(string $jenis)
    {
        if (!in_array($jenis, ['peralatan', 'pemeliharaan', 'peminjaman'])) abort(404);

        $records = match ($jenis) {
            'peralatan' => Peralatan::with(['jenis'])->latest()->get(),
            'pemeliharaan' => Pemeliharaan::with(['peralatan.jenis'])->latest()->get(),
            'peminjaman' => Peminjaman::latest()->get(),
        };

        $pdf = Pdf::loadView('pdf.laporan', [
            'jenis' => $jenis,
            'records' => $records,
            'date' => now()->translatedFormat('d F Y')
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_" . ucfirst($jenis) . "_" . now()->format('Ymd') . ".pdf");
    }
}