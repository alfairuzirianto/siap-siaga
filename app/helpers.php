<?php

use App\Models\BeritaAcara;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use Illuminate\Support\Carbon;

// PJM-YYYYMMDD-XXXX
function nomorPeminjaman(): string
{
    $period = now()->format('Ymd');
    $count = Peminjaman::whereDate('created_at', today())->count() + 1;

    return sprintf("PJM-%s-%04d", $period, $count);
}

// MTN-YYYYMMDD-XXXX
function nomorPemeliharaan(): string
{
    $period = now()->format('Ymd');
    $count = Pemeliharaan::whereDate('created_at', today())->count() + 1;

    return sprintf("MTN-%s-%04d", $period, $count);
}

// XXX.BA/{PPB/PBB}/JARKONS/MM.YYYY
function nomorBA(string $jenis): string
{
    $jumlahHandoverBulanIni = BeritaAcara::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    $kodeJenis = match($jenis) {
        BeritaAcara::BA_PEMINJAMAN => 'PPB',
        BeritaAcara::BA_PENGEMBALIAN => 'PBB',
        default  => 'PPB',
    };

    return str_pad($jumlahHandoverBulanIni + 1, 3, '0', STR_PAD_LEFT)
        . '.BA/' . $kodeJenis . '/JARKONS/' . now()->format('m.Y');
}

// 01 Januari 2000, 12:45 WIB
function formatTanggal(string $tanggal, string $format = 'd F Y, H:i'): string
{
    return Carbon::parse($tanggal)->translatedFormat($format);
}