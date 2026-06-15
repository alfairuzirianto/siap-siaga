<?php

namespace App\Livewire\Peralatan;

use App\Models\Peralatan;
use App\Models\PeralatanJenis;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Peralatan | SiapSiaga')]
class Index extends Component
{
    public bool $isCreatingJenis = false;
    public ?int $selectedJenisId = null;
    public ?int $editingJenisId = null;
    public string $selectedJenis = '';
    public string $nama_jenis = '';

    public function selectJenis(int $id, string $nama_jenis)
    {
        if ($this->editingJenisId === $id) return;
        
        $this->selectedJenisId = $id;
        $this->selectedJenis = $nama_jenis;
    }

    public function resetJenis()
    {
        $this->selectedJenisId = null;
        $this->selectedJenis = '';
    }

    public function createJenis()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $this->cancelJenis();
        $this->isCreatingJenis = true;
        $this->nama_jenis = '';
    }

    public function editJenis(int $id, string $new_nama_jenis)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $this->editingJenisId = $id;
        $this->nama_jenis = $new_nama_jenis;
    }

    public function cancelJenis()
    {
        $this->reset(['editingJenisId', 'nama_jenis', 'isCreatingJenis']);
    }

    public function saveJenis()
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $this->validate([
            'nama_jenis' => 'required|string|max:255|unique:peralatan_jenis,nama_jenis,' . ($this->editingJenisId ?? 'NULL'),
        ]);

        if ($this->editingJenisId) {
            $jenis = PeralatanJenis::find($this->editingJenisId);
            if ($jenis) {
                $jenis->update(['nama_jenis' => $this->nama_jenis]);
                session()->flash('success', 'Nama jenis berhasil diperbarui.');
            }
        } else {
            PeralatanJenis::create(['nama_jenis' => $this->nama_jenis]);
            session()->flash('success', 'Jenis peralatan baru berhasil ditambahkan.');
        }

        $this->cancelJenis();
    }

    public function delete(Peralatan $peralatan)
    {
        $this->authorize('delete', $peralatan);
        $peralatan->delete();
        session()->flash('success', 'Peralatan berhasil dihapus.');
    }

    public function render()
    {
        $peralatanJenis = PeralatanJenis::withCount('peralatan as total_alat')
            ->withCount(['peralatan as total_tersedia' => fn($q) => $q->where('status', 'Tersedia')])
            ->withCount(['peralatan as total_dipinjam' => fn($q) => $q->where('status', 'Dipinjam')])
            ->get();

        $peralatans = [];
        if ($this->selectedJenisId) {
            $peralatans = Peralatan::where('peralatan_jenis_id', $this->selectedJenisId)
                ->latest()
                ->get();
        }

        return view('livewire.peralatan.index', compact('peralatanJenis', 'peralatans'));
    }
}