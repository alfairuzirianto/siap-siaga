<?php

namespace App\Livewire\Dashboard;

use App\Models\ActivityLog;
use App\Models\Pemeliharaan;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $user = auth()->user();

        $data = [
            'totalPeralatan'    => Peralatan::count(),
            'totalPemeliharaan' => Pemeliharaan::count(),
            'peminjamanAktif'   => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
        ];

        if ($user->isSupervisor()) {
            $data['validasiPending'] = Peminjaman::query()
                ->whereIn('status', [Peminjaman::PINJAM_DIAJUKAN, Peminjaman::KEMBALI_DIAJUKAN])
                ->count();

            $data['antreanValidasi'] = Peminjaman::with('pengguna')
                ->whereIn('status', [Peminjaman::PINJAM_DIAJUKAN, Peminjaman::KEMBALI_DIAJUKAN])
                ->latest()
                ->limit(5)
                ->get();
        }

        if ($user->isAdmin()) {
            $data['recentLogs'] = ActivityLog::with('user')
                ->latest()
                ->limit(5)
                ->get();
        }

        return view('livewire.dashboard.index', compact('data'));
    }
}
