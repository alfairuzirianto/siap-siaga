<?php

namespace App\Livewire\Laporan;

use App\Models\Peralatan;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Pusat Laporan | SiapSiaga')]
class Index extends Component
{
    public function render()
    {
        if (!auth()->user()->isSupervisor()) abort(403);

        $ringkasan = [
            'peralatan' => [
                'total' => Peralatan::count(),
                'tersedia' => Peralatan::where('status', Peralatan::STATUS_TERSEDIA)->count(),
                'dipinjam' => Peralatan::where('status', Peralatan::STATUS_DIPINJAM)->count(),
            ],
            'pemeliharaan' => [
                'total' => Pemeliharaan::count(),
                'biaya_estimasi' => Pemeliharaan::sum('biaya') ?? 0,
            ],
            'peminjaman' => [
                'total' => Peminjaman::count(),
                'selesai' => Peminjaman::where('status', Peminjaman::SELESAI)->count(),
                'aktif' => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            ]
        ];

        return view('livewire.laporan.index', compact('ringkasan'));
    }
}