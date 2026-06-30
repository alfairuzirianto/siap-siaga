<?php

namespace App\Livewire\Peralatan;

use App\Models\Peralatan;
use Livewire\Component;

class Show extends Component
{
    public ?Peralatan $peralatan = null;
    public string $activeTab = 'pemeliharaan';

    public function mount(Peralatan $peralatan)
    {
        $this->peralatan = $peralatan->load([
            'jenis',
            'creator',
            'updater',
            'pemeliharaan' => fn($q) => $q->latest('tanggal_pemeliharaan'),
            'peminjamanDetail.peminjaman' => fn($q) => $q->latest()
        ]);
    }

    public function switchTab(string $tab)
    {
        if (in_array($tab, ['pemeliharaan', 'peminjaman'])) {
            $this->activeTab = $tab;
        }
    }
    
    public function render()
    {
        return view('livewire.peralatan.show', ['title' => 'Detail ' . $this->peralatan->nomor_seri]);
    }
}