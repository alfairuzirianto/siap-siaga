<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use App\Models\Peralatan;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Daftar Peminjaman | SiapSiaga')]
class Index extends Component
{
    use WithPagination;

    public string $tujuan_keperluan = '';
    public array $selectedPeralatan = [];

    public string $search = '';
    public string $filterStatus = '';
    protected $updatesQueryString = ['search', 'filterStatus'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== Peminjaman::SELESAI) {
            session()->flash('error', 'Gagal menghapus. Peminjaman yang sedang aktif harus diselesaikan terlebih dahulu melalui halaman detail.');
            return;
        }

        $peminjaman->delete();

        session()->flash('success', 'Data riwayat peminjaman berhasil dihapus.');
    }

    public function render()
    {
        $peralatans = Peralatan::where('status', Peralatan::STATUS_TERSEDIA)->get();
        $peminjamans = Peminjaman::with(['details.peralatan.jenis']);

        if ($this->search) {
            $peminjamans->where(fn ($query) =>
                $query->where('nomor_peminjaman', 'like', '%' . $this->search . '%')
                    ->orWhere('tujuan_keperluan', 'like', '%' . $this->search . '%')
                    ->orWhereHas('nama_pengguna', fn ($q) =>
                        $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    )
            );
        }
        
        if ($this->filterStatus) {
            $peminjamans->where(fn ($query) =>
                $query->where('status', $this->filterStatus)
            );
        }

        $peminjamans = $peminjamans->latest()->paginate(10);

        return view('livewire.peminjaman.index', compact('peralatans', 'peminjamans'));
    }
}
