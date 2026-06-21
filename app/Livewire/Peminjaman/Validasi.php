<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Approval Peminjaman | SiapSiaga')]
class Validasi extends Component
{
    use WithPagination;

    public bool $isPeminjaman = true;
    public string $filterStatus = '';

    public function mount(Request $request)
    {
        if ($request->routeIs('validasi.peminjaman')) {
            $this->filterStatus = Peminjaman::PINJAM_DIAJUKAN;
            $this->isPeminjaman = true;
        } else {
            $this->filterStatus = Peminjaman::KEMBALI_DIAJUKAN;
            $this->isPeminjaman = false;
        }
    }


    public function render()
    {
        if (!auth()->user()->isSupervisor()) {
            abort(403, 'Akses ditolak. Khusus Otoritas Supervisor.');
        }

        $pengajuans = Peminjaman::with(['pengguna', 'details.peralatan.jenis'])
            ->when($this->filterStatus, fn ($query) =>
                $query->where('status', $this->filterStatus)
            )
            ->latest()
            ->paginate(10);

        $statuses = $this->isPeminjaman
            ? Peminjaman::STATUS_PEMINJAMAN
            : Peminjaman::STATUS_PENGEMBALIAN;

        return view('livewire.peminjaman.validasi', compact('pengajuans', 'statuses'));
    }
}