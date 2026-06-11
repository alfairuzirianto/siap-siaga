<?php

namespace App\Livewire\Dashboard;

use App\Models\ActivityLog;
use App\Models\Approval;
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
            'activeBorrow'      => Peminjaman::whereIn('status', [
               Peminjaman::STATUS_DIAJUKAN,
               Peminjaman::STATUS_DISETUJUI,
               Peminjaman::STATUS_DIPINJAM,
            ])
            ->count(),
        ];

        if ($user->isSupervisor()) {
            $data['pendingApprovals'] = Approval::query()
                ->where('unit_id', $user->unit_id)
                ->where('status', 'pending')
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
