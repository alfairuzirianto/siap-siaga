<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Approval Peminjaman | SiapSiaga')]
class Validasi extends Component
{
    use WithPagination;

    public string $filterStatus = Peminjaman::PINJAM_DIAJUKAN; // filter default

    public function render()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Akses ditolak. Khusus Otoritas Supervisor.');
        }

        $requests = Peminjaman::with(['pengguna', 'details.peralatan.jenis'])
            ->when($this->filterStatus, fn ($query) =>
                $query->where('status', $this->filterStatus)
            )
            ->latest()
            ->paginate(10);

        return view('livewire.peminjaman.validasi', [
            'requests' => $requests
        ]);
    }
}