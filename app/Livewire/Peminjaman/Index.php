<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Services\PeminjamanService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Daftar Peminjaman | SiapSiaga')]
class Index extends Component
{
    use WithPagination;

    public string $tujuan_keperluan = '';
    public string $tgl_rencana_pinjam = '';
    public string $tgl_rencana_kembali = '';
    public array $selectedPeralatan = [];

    public string $search = '';
    public string $filterStatus = '';
    protected $updatesQueryString = ['search', 'filterStatus'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->tgl_rencana_pinjam = now()->format('Y-m-d');
        $this->tgl_rencana_kembali = now()->addDays(3)->format('Y-m-d');
    }

    public function batal(int $id, PeminjamanService $service)
    {
        if (!auth()->user()->isPengguna()) abort(403);

        $peminjaman = Peminjaman::where('pengguna_id', auth()->id())->findOrFail($id);
        
        if ($peminjaman->status !== Peminjaman::STATUS_DIAJUKAN) {
            session()->flash('error', 'Hanya pengajuan yang menunggu approval yang dapat dibatalkan.');
            return;
        }

        $service->batalPinjam($peminjaman);
        session()->flash('success', 'Pengajuan berhasil dibatalkan.');
    }

    public function render()
    {
        $user = auth()->user();
        $peralatans = Peralatan::where('status', Peralatan::STATUS_TERSEDIA)->get();
        $peminjamans = Peminjaman::with(['pengguna', 'details.peralatan.jenis', 'approverPinjam', 'approverKembali']);

        if ($user->isPengguna()) {
            $peminjamans->where('pengguna_id', $user->id);
        } else {
            if ($this->search) {
                $peminjamans->where(fn ($query) =>
                    $query->where('nomor_peminjaman', 'like', '%' . $this->search . '%')
                      ->orWhere('tujuan_keperluan', 'like', '%' . $this->search . '%')
                      ->orWhereHas('pengguna', fn ($q) =>
                          $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                        )
                );
            }
            if ($this->filterStatus) {
                $peminjamans->where(fn ($query) =>
                    $query->where('status', $this->filterStatus)
                );
            }
        }

        $peminjamans = $peminjamans->latest()->paginate(10);

        return view('livewire.peminjaman.index', compact('peralatans', 'peminjamans'));
    }
}
