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
        $data = [
            'totalPeralatan'    => Peralatan::count(),
            'totalPemeliharaan' => Pemeliharaan::count(),
            'peminjamanAktif'   => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
            'recentLogs'        => ActivityLog::with('user')->latest()->limit(5)->get(),
        ];

        return view('livewire.dashboard.index', compact('data'));
    }
}
