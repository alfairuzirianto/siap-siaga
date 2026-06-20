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
            'totalEquipment'    => Peralatan::count(),
            'maintenanceCount'  => Pemeliharaan::count(),
            'activeBorrow'      => Peminjaman::where('status', Peminjaman::DIPINJAM)->count(),
        ];

        if ($user->isSupervisor()) {
            $data['pendingApprovals'] = Peminjaman::query()
                ->where('status', [Peminjaman::PINJAM_DIAJUKAN, Peminjaman::KEMBALI_DIAJUKAN])
                ->count();
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
