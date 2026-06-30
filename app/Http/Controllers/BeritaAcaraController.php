<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BeritaAcaraController extends Controller
{
    public function download(BeritaAcara $ba)
    {
        if (! $ba->is_valid) abort(404, 'Dokumen resmi belum diterbitkan.');

        $peminjaman = $ba->peminjaman;

        $supervisor = ($ba->jenis_ba === BeritaAcara::BA_PEMINJAMAN)
            ? $peminjaman->approverPinjam
            : $peminjaman->approverKembali;

        $verifyUrl = route('ba.verify', $ba->token);

        $pdf = Pdf::loadView('pdf.berita-acara', compact(
            'ba', 'peminjaman', 'supervisor', 'verifyUrl'
        ))->setPaper('a4', 'portrait');

        return $pdf->download(str_replace('/', '-', $ba->nomor_ba) . '.pdf');
    }

    public function verify(string $token)
    {
        $ba = BeritaAcara::with(['peminjaman', 'peminjaman.details.peralatan.jenis'])
            ->where('token', $token)
            ->first();

        return view('livewire.peminjaman.verify', compact('ba'));
    }
}
